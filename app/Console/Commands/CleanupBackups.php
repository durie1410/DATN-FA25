<?php

namespace App\Console\Commands;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Console\Command;

class CleanupBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleanup {--days=30 : Keep backups newer than this many days} {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old backup files';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService)
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');

        $cutoffDate = now()->subDays($days);
        
        $oldBackups = Backup::completed()
            ->where('created_at', '<', $cutoffDate)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($oldBackups->isEmpty()) {
            $this->info("No old backups found to clean up.");
            return 0;
        }

        $totalSize = $oldBackups->sum('file_size');
        $totalSizeMB = number_format($totalSize / 1024 / 1024, 2);

        $this->info("Found {$oldBackups->count()} backup(s) older than {$days} days");
        $this->line("Total size to be freed: {$totalSizeMB} MB");
        $this->line("");

        if ($dryRun) {
            $this->warn("🔍 DRY RUN - No files will be deleted");
            $this->line("");
            
            $headers = ['Filename', 'Size', 'Created', 'Age'];
            $rows = [];
            
            foreach ($oldBackups as $backup) {
                $rows[] = [
                    $backup->filename,
                    $backup->formatted_size,
                    $backup->created_at->format('Y-m-d H:i'),
                    $backup->age_in_days . ' days'
                ];
            }
            
            $this->table($headers, $rows);
            $this->line("");
            $this->info("Run without --dry-run to actually delete these files.");
        } else {
            if (!$this->confirm("Are you sure you want to delete these {$oldBackups->count()} backup(s)?")) {
                $this->info("Cleanup cancelled.");
                return 0;
            }

            $deletedCount = 0;
            $deletedSize = 0;

            foreach ($oldBackups as $backup) {
                if (\Storage::exists($backup->file_path)) {
                    \Storage::delete($backup->file_path);
                    $deletedSize += $backup->file_size;
                }
                $backup->delete();
                $deletedCount++;
                
                $this->line("🗑️  Deleted: {$backup->filename}");
            }

            $this->line("");
            $this->info("✅ Cleanup completed!");
            $this->line("   - Files deleted: {$deletedCount}");
            $this->line("   - Space freed: " . number_format($deletedSize / 1024 / 1024, 2) . " MB");
        }

        return 0;
    }
}
