<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display backup management page
     */
    public function index()
    {
        $backups = $this->backupService->listBackups();
        $statistics = $this->backupService->getStatistics();

        return view('admin.backup.index', compact('backups', 'statistics'));
    }

    /**
     * Create a new backup
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:manual,automatic,full,incremental',
            'description' => 'nullable|string|max:500',
        ]);

        $type = $request->input('type', 'manual');
        $description = $request->input('description', 'Manual backup created by ' . auth()->user()->name);

        $result = $this->backupService->createBackup($type, $description);

        if ($result['success']) {
            // Log backup creation
            AuditService::log('backup_created', null, [], [], "Backup '{$result['backup']->filename}' created");
            
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'backup' => $result['backup']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 500);
    }

    /**
     * Restore from backup
     */
    public function restore(Request $request): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $request->input('filename');

        $result = $this->backupService->restoreBackup($filename);

        if ($result['success']) {
            // Log backup restore
            AuditService::log('backup_restored', null, [], [], "Backup '{$filename}' restored");
            
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 500);
    }

    /**
     * Download backup file
     */
    public function download(string $filename): Response
    {
        try {
            $filepath = $this->backupService->downloadBackup($filename);
            
            // Log backup download
            AuditService::log('backup_downloaded', null, [], [], "Backup '{$filename}' downloaded");

            return response()->download($filepath, $filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
    }

    /**
     * Delete backup
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $request->input('filename');

        $result = $this->backupService->deleteBackup($filename);

        if ($result['success']) {
            // Log backup deletion
            AuditService::log('backup_deleted', null, [], [], "Backup '{$filename}' deleted");
            
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 500);
    }

    /**
     * Validate backup file
     */
    public function validateBackup(Request $request): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $request->input('filename');
        $result = $this->backupService->validateBackup($filename);

        return response()->json($result);
    }

    /**
     * Get backup statistics
     */
    public function statistics(): JsonResponse
    {
        $statistics = $this->backupService->getStatistics();

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Schedule automatic backup
     */
    public function schedule(Request $request): JsonResponse
    {
        $request->validate([
            'frequency' => 'required|in:daily,weekly,monthly',
            'time' => 'required|date_format:H:i',
        ]);

        $frequency = $request->input('frequency');
        $time = $request->input('time');

        $result = $this->backupService->scheduleBackup($frequency, $time);

        if ($result['success']) {
            // Log backup scheduling
            AuditService::log('backup_scheduled', null, [], [], "Backup scheduled for {$frequency} at {$time}");
        }

        return response()->json($result);
    }

    /**
     * Get backup list for API
     */
    public function list(): JsonResponse
    {
        $backups = $this->backupService->listBackups();

        return response()->json([
            'success' => true,
            'data' => $backups
        ]);
    }

    /**
     * Create backup via API
     */
    public function apiCreate(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:manual,automatic,full,incremental',
            'description' => 'nullable|string|max:500',
        ]);

        $type = $request->input('type', 'manual');
        $description = $request->input('description', 'API backup created');

        $result = $this->backupService->createBackup($type, $description);

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Restore backup via API
     */
    public function apiRestore(Request $request): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $request->input('filename');
        $result = $this->backupService->restoreBackup($filename);

        return response()->json($result, $result['success'] ? 200 : 500);
    }
}
