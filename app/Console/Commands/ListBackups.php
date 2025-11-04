<?php

namespace App\Console\Commands;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Console\Command;

class ListBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:list {--limit=10 : Number of backups to show} {--type= : Filter by backup type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available backups';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService)
    {
        $limit = $this->option('limit');
        $type = $this->option('type');

        $query = Backup::completed()->orderBy('created_at', 'desc');
        
        if ($type) {
            $query->where('type', $type);
        }

        $backups = $query->limit($limit)->get();

        if ($backups->isEmpty()) {
            $this->info("No backups found.");
            return 0;
        }

        $this->info("📋 Available Backups:");
        $this->line("");

        $headers = ['ID', 'Filename', 'Type', 'Size', 'Created', 'Age', 'Status'];
        $rows = [];

        foreach ($backups as $backup) {
            $rows[] = [
                $backup->id,
                $backup->filename,
                ucfirst($backup->type),
                $backup->formatted_size,
                $backup->created_at->format('Y-m-d H:i'),
                $backup->age_in_days . ' days',
                ucfirst($backup->status)
            ];
        }

        $this->table($headers, $rows);

        // Show statistics
        $stats = $backupService->getBackupStats();
        $this->line("");
        $this->info("📊 Backup Statistics:");
        $this->line("   - Total Backups: {$stats['total_backups']}");
        $this->line("   - Completed: {$stats['completed_backups']}");
        $this->line("   - Failed: {$stats['failed_backups']}");
        $this->line("   - Total Size: " . number_format($stats['total_size'] / 1024 / 1024, 2) . " MB");
        $this->line("   - Recent (7 days): {$stats['recent_backups']}");

        if ($stats['last_backup']) {
            $this->line("   - Last Backup: {$stats['last_backup']->created_at->format('Y-m-d H:i')}");
        }

        return 0;
    }
}
