<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use App\Models\Backup;
use Illuminate\Console\Command;

class RestoreBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:restore {filename : The backup filename to restore} {--force : Force restore without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database from a backup file';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService)
    {
        $filename = $this->argument('filename');
        $force = $this->option('force');

        // Check if backup exists
        $backup = Backup::where('filename', $filename)->first();
        
        if (!$backup) {
            $this->error("❌ Backup file '{$filename}' not found in database records.");
            return 1;
        }

        if (!$backupService->validateBackup($filename)) {
            $this->error("❌ Backup file '{$filename}' is not valid.");
            return 1;
        }

        if (!$force) {
            $this->warn("⚠️  WARNING: This will completely replace your current database!");
            $this->warn("⚠️  All current data will be lost!");
            
            if (!$this->confirm("Are you sure you want to continue?")) {
                $this->info("Restore cancelled.");
                return 0;
            }
        }

        $this->info("Restoring database from backup: {$filename}");
        $this->line("📁 Backup Info:");
        $this->line("   - Size: {$backup->formatted_size}");
        $this->line("   - Created: {$backup->created_at->format('Y-m-d H:i:s')}");
        $this->line("   - Type: {$backup->type}");
        
        if ($backup->metadata) {
            $this->line("   - Tables: {$backup->metadata['table_count']}");
            $this->line("   - Records: {$backup->metadata['total_records']}");
        }

        $result = $backupService->restoreBackup($filename);

        if ($result['success']) {
            $this->info("✅ Database restored successfully!");
            $this->line("🔄 You may need to clear cache and restart services.");
        } else {
            $this->error("❌ Restore failed: {$result['message']}");
            return 1;
        }

        return 0;
    }
}
