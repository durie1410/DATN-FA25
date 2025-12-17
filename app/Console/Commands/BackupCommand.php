<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackupService;
use App\Services\AuditService;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create {--type=automatic : Type of backup (automatic, manual, full, incremental)} {--description= : Description for the backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup';

    protected $backupService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type');
        $description = $this->option('description') ?: "Automatic backup created at " . now()->format('Y-m-d H:i:s');

        $this->info("Creating {$type} backup...");

        $result = $this->backupService->createBackup($type, $description);

        if ($result['success']) {
            $this->info("✅ Backup created successfully!");
            $this->info("📁 Filename: {$result['backup']->filename}");
            $this->info("📊 Size: {$result['backup']->formatted_size}");
            $this->info("📅 Created: {$result['backup']->created_at->format('Y-m-d H:i:s')}");
            
            // Log backup creation
            AuditService::log('backup_created', null, [], [], "Automatic backup '{$result['backup']->filename}' created via command");
            
            return 0;
        } else {
            $this->error("❌ Backup failed: {$result['message']}");
            return 1;
        }
    }
}
