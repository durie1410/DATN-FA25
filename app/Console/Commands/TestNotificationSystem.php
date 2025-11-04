<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\NotificationTemplate;
use App\Models\NotificationLog;

class TestNotificationSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the notification system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing Notification System...');
        
        // Test 1: Check if templates exist
        $this->info('1. Checking notification templates...');
        $templates = NotificationTemplate::all();
        $this->info("Found {$templates->count()} templates:");
        
        foreach ($templates as $template) {
            $this->line("  - {$template->name} ({$template->type}) - {$template->channel}");
        }
        
        // Test 2: Test template rendering
        $this->info('2. Testing template rendering...');
        $borrowTemplate = NotificationTemplate::where('type', 'borrow_reminder')->first();
        
        if ($borrowTemplate) {
            $testData = [
                'reader_name' => 'Nguyễn Văn A',
                'book_title' => 'Lập trình PHP',
                'due_date' => '25/12/2024',
                'days_remaining' => 3,
            ];
            
            $rendered = $borrowTemplate->renderContent($testData);
            $this->info('Template rendered successfully:');
            $this->line("Subject: {$rendered['subject']}");
            $this->line("Content: " . substr($rendered['content'], 0, 100) . '...');
        } else {
            $this->error('Borrow reminder template not found!');
        }
        
        // Test 3: Test notification service
        $this->info('3. Testing notification service...');
        $notificationService = new NotificationService();
        
        try {
            // Test template rendering in service
            $testData = [
                'reader_name' => 'Test User',
                'book_title' => 'Test Book',
                'due_date' => '25/12/2024',
                'days_remaining' => 3,
            ];
            
            $result = $notificationService->renderTemplate(
                'Test Subject with {{reader_name}}',
                'Test content with {{reader_name}} and {{book_title}}',
                $testData
            );
            
            $this->info('Template rendering in service works:');
            $this->line("Rendered subject: {$result['subject']}");
            $this->line("Rendered content: " . substr($result['content'], 0, 100) . '...');
            
            $this->info('Notification service test passed!');
        } catch (\Exception $e) {
            $this->error('Notification service error: ' . $e->getMessage());
        }
        
        // Test 4: Check notification logs
        $this->info('4. Checking notification logs...');
        $logs = NotificationLog::count();
        $this->info("Total notification logs: {$logs}");
        
        $this->info('Notification system test completed!');
        
        return 0;
    }
}