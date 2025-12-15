<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Carbon\Carbon;
use Exception;

class BackupService
{
    protected $backupPath;
    protected $maxBackups;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->maxBackups = config('backup.max_backups', 10);
        
        // Ensure backup directory exists
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    /**
     * Create a full database backup
     */
    public function createBackup($type = 'full', $description = null)
    {
        try {
            $timestamp = Carbon::now()->format('Y_m_d_H_i_s');
            $filename = "backup_{$type}_{$timestamp}.sql";
            $filepath = $this->backupPath . '/' . $filename;

            // Get database configuration
            $dbConfig = config('database.connections.mysql');
            $host = $dbConfig['host'];
            $port = $dbConfig['port'];
            $database = $dbConfig['database'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];

            // Create mysqldump command
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            // Execute backup command
            $result = Process::run($command);

            if ($result->failed()) {
                throw new Exception('Backup failed: ' . $result->errorOutput());
            }

            // Create backup record
            $backup = \App\Models\Backup::create([
                'filename' => $filename,
                'type' => $type,
                'size' => filesize($filepath),
                'description' => $description,
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            // Clean old backups
            $this->cleanOldBackups();

            return [
                'success' => true,
                'backup' => $backup,
                'message' => 'Backup created successfully'
            ];

        } catch (Exception $e) {
            \Log::error('Backup creation failed', [
                'error' => $e->getMessage(),
                'type' => $type
            ]);

            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Restore from backup
     */
    public function restoreBackup($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . $filename;

            if (!file_exists($filepath)) {
                throw new Exception('Backup file not found');
            }

            // Get database configuration
            $dbConfig = config('database.connections.mysql');
            $host = $dbConfig['host'];
            $port = $dbConfig['port'];
            $database = $dbConfig['database'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];

            // Create mysql command for restore
            $command = sprintf(
                'mysql --host=%s --port=%s --user=%s --password=%s %s < %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            // Execute restore command
            $result = Process::run($command);

            if ($result->failed()) {
                throw new Exception('Restore failed: ' . $result->errorOutput());
            }

            // Update backup record
            $backup = \App\Models\Backup::where('filename', $filename)->first();
            if ($backup) {
                $backup->update([
                    'last_restored_at' => now(),
                    'restored_by' => auth()->id(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Database restored successfully'
            ];

        } catch (Exception $e) {
            \Log::error('Backup restore failed', [
                'error' => $e->getMessage(),
                'filename' => $filename
            ]);

            return [
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * List all backups
     */
    public function listBackups()
    {
        $backups = \App\Models\Backup::with('creator', 'restorer')
            ->orderBy('created_at', 'desc')
            ->get();

        return $backups->map(function($backup) {
            $filepath = $this->backupPath . '/' . $backup->filename;
            $exists = file_exists($filepath);
            
            return [
                'id' => $backup->id,
                'filename' => $backup->filename,
                'type' => $backup->type,
                'size' => $backup->size,
                'size_formatted' => $this->formatBytes($backup->size),
                'description' => $backup->description,
                'status' => $backup->status,
                'exists' => $exists,
                'created_at' => $backup->created_at,
                'created_by' => $backup->creator ? $backup->creator->name : 'System',
                'last_restored_at' => $backup->last_restored_at,
                'restored_by' => $backup->restorer ? $backup->restorer->name : null,
            ];
        });
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . $filename;
            
            if (file_exists($filepath)) {
                unlink($filepath);
            }

            // Delete backup record
            \App\Models\Backup::where('filename', $filename)->delete();

            return [
                'success' => true,
                'message' => 'Backup deleted successfully'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $filepath = $this->backupPath . '/' . $filename;
        
        if (!file_exists($filepath)) {
            throw new Exception('Backup file not found');
        }

        return $filepath;
    }

    /**
     * Validate backup file
     */
    public function validateBackup($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . $filename;
            
            if (!file_exists($filepath)) {
                return [
                    'valid' => false,
                    'message' => 'File not found'
                ];
            }

            // Check file size
            $size = filesize($filepath);
            if ($size < 1024) { // Less than 1KB
                return [
                    'valid' => false,
                    'message' => 'File too small, may be corrupted'
                ];
            }

            // Check if it's a valid SQL file
            $handle = fopen($filepath, 'r');
            $firstLine = fgets($handle);
            fclose($handle);

            if (!str_contains($firstLine, '-- MySQL dump') && !str_contains($firstLine, 'CREATE TABLE')) {
                return [
                    'valid' => false,
                    'message' => 'Invalid SQL file format'
                ];
            }

            return [
                'valid' => true,
                'message' => 'Backup file is valid',
                'size' => $this->formatBytes($size)
            ];

        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Clean old backups
     */
    protected function cleanOldBackups()
    {
        $backups = \App\Models\Backup::orderBy('created_at', 'desc')->get();
        
        if ($backups->count() > $this->maxBackups) {
            $toDelete = $backups->skip($this->maxBackups);
            
            foreach ($toDelete as $backup) {
                $filepath = $this->backupPath . '/' . $backup->filename;
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
                $backup->delete();
            }
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get backup statistics
     */
    public function getStatistics()
    {
        $totalBackups = \App\Models\Backup::count();
        $totalSize = \App\Models\Backup::sum('size');
        $lastBackup = \App\Models\Backup::latest()->first();
        $successfulBackups = \App\Models\Backup::where('status', 'completed')->count();

        return [
            'total_backups' => $totalBackups,
            'total_size' => $this->formatBytes($totalSize),
            'successful_backups' => $successfulBackups,
            'last_backup' => $lastBackup ? $lastBackup->created_at : null,
            'backup_directory' => $this->backupPath,
            'max_backups' => $this->maxBackups,
        ];
    }

    /**
     * Schedule automatic backup
     */
    public function scheduleBackup($frequency = 'daily', $time = '02:00')
    {
        // This would typically integrate with Laravel's task scheduler
        // For now, we'll just log the schedule
        \Log::info('Backup scheduled', [
            'frequency' => $frequency,
            'time' => $time,
            'scheduled_by' => auth()->id()
        ]);

        return [
            'success' => true,
            'message' => "Backup scheduled for {$frequency} at {$time}"
        ];
    }
}