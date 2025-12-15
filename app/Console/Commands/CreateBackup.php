<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class CreateBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create {--type=automatic : Type of backup (manual, automatic, scheduled)} {--description= : Description for the backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database backup';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService)
    {
        $type = $this->option('type');
        $description = $this->option('description') ?? "Scheduled {$type} backup";

        $this->info("Creating {$type} backup...");

        $result = $backupService->createBackup($type, $description);

        if ($result['success']) {
            $backup = $result['backup'];
            $this->info("✅ Backup created successfully!");
            $this->line("📁 Filename: {$backup->filename}");
            $this->line("📊 Size: {$backup->formatted_size}");
            $this->line("📅 Created: {$backup->created_at->format('Y-m-d H:i:s')}");
            
            if ($backup->metadata) {
                $this->line("📈 Database Info:");
                $this->line("   - Tables: {$backup->metadata['table_count']}");
                $this->line("   - Total Records: {$backup->metadata['total_records']}");
                $this->line("   - Database Size: {$backup->metadata['database_size']} MB");
            }
        } else {
            $this->error("❌ Backup failed: {$result['message']}");
            return 1;
        }

        return 0;
    }
}
