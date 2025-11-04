<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RealTimeNotificationService;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;

class TestPhase3Features extends Command
{
    protected $signature = 'test:phase3-features';
    protected $description = 'Test Phase 3 features: Mobile Support, Real-time, Social, UI/UX';

    public function handle()
    {
        $this->info('🚀 Testing Phase 3 Features...');
        
        // Test Real-time Notifications
        $this->testRealTimeNotifications();
        
        // Test Social Features
        $this->testSocialFeatures();
        
        // Test Mobile Support
        $this->testMobileSupport();
        
        // Test UI/UX Improvements
        $this->testUIUXImprovements();
        
        $this->info('✅ Phase 3 Features testing completed!');
        
        return 0;
    }

    protected function testRealTimeNotifications()
    {
        $this->info('📱 Testing Real-time Notifications...');
        
        $user = User::where('role', 'user')->first();
        if (!$user) {
            $this->warn('No user found for testing');
            return;
        }
        
        // Test sending notification
        $result = RealTimeNotificationService::sendToUser(
            $user->id,
            'test',
            'Test Notification',
            'This is a test notification for Phase 3 features.',
            ['test' => true, 'phase' => 3]
        );
        
        if ($result) {
            $this->info('✅ Real-time notification sent successfully');
        } else {
            $this->error('❌ Failed to send real-time notification');
        }
        
        // Test getting notifications
        $notifications = RealTimeNotificationService::getUserNotifications($user->id, 5);
        $this->info("📊 User has " . count($notifications) . " notifications");
        
        // Test unread count
        $unreadCount = RealTimeNotificationService::getUnreadCount($user->id);
        $this->info("🔔 Unread notifications: {$unreadCount}");
        
        // Test marking user online
        RealTimeNotificationService::markUserOnline($user->id);
        $this->info('✅ User marked as online');
    }

    protected function testSocialFeatures()
    {
        $this->info('👥 Testing Social Features...');
        
        $user = User::where('role', 'user')->first();
        $book = Book::first();
        
        if (!$user || !$book) {
            $this->warn('No user or book found for testing');
            return;
        }
        
        // Test creating review
        $review = Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'This is a test review for Phase 3 features.',
            'title' => 'Great book!',
            'status' => 'approved',
        ]);
        
        if ($review) {
            $this->info('✅ Review created successfully');
        } else {
            $this->error('❌ Failed to create review');
        }
        
        // Test review relationships
        $this->info("📚 Review belongs to book: " . $review->book->ten_sach);
        $this->info("👤 Review belongs to user: " . $review->user->name);
        
        // Test review scopes
        $approvedReviews = Review::approved()->count();
        $this->info("✅ Approved reviews: {$approvedReviews}");
        
        $pendingReviews = Review::pending()->count();
        $this->info("⏳ Pending reviews: {$pendingReviews}");
    }

    protected function testMobileSupport()
    {
        $this->info('📱 Testing Mobile Support...');
        
        // Check if mobile views exist
        $mobileViews = [
            'mobile-home.blade.php',
            'mobile-books.blade.php',
        ];
        
        foreach ($mobileViews as $view) {
            $path = resource_path("views/{$view}");
            if (file_exists($path)) {
                $this->info("✅ Mobile view exists: {$view}");
            } else {
                $this->warn("⚠️ Mobile view missing: {$view}");
            }
        }
        
        // Check if mobile JavaScript exists
        $mobileJS = public_path('js/realtime-notifications.js');
        if (file_exists($mobileJS)) {
            $this->info('✅ Mobile JavaScript exists: realtime-notifications.js');
        } else {
            $this->warn('⚠️ Mobile JavaScript missing: realtime-notifications.js');
        }
        
        // Check responsive layout
        $layoutPath = resource_path('views/layouts/app.blade.php');
        if (file_exists($layoutPath)) {
            $layoutContent = file_get_contents($layoutPath);
            if (strpos($layoutContent, 'mobile-nav') !== false) {
                $this->info('✅ Mobile navigation implemented');
            } else {
                $this->warn('⚠️ Mobile navigation not found');
            }
            
            if (strpos($layoutContent, '@media') !== false) {
                $this->info('✅ Responsive CSS implemented');
            } else {
                $this->warn('⚠️ Responsive CSS not found');
            }
        }
    }

    protected function testUIUXImprovements()
    {
        $this->info('🎨 Testing UI/UX Improvements...');
        
        // Check if modern views exist
        $modernViews = [
            'modern-home.blade.php',
        ];
        
        foreach ($modernViews as $view) {
            $path = resource_path("views/{$view}");
            if (file_exists($path)) {
                $this->info("✅ Modern view exists: {$view}");
            } else {
                $this->warn("⚠️ Modern view missing: {$view}");
            }
        }
        
        // Check for modern CSS features
        $layoutPath = resource_path('views/layouts/app.blade.php');
        if (file_exists($layoutPath)) {
            $layoutContent = file_get_contents($layoutPath);
            
            $modernFeatures = [
                'glass-card' => 'Glass morphism effect',
                'gradient-bg' => 'Gradient backgrounds',
                'floating' => 'Floating animations',
                'stagger-item' => 'Staggered animations',
                'btn-modern' => 'Modern buttons',
            ];
            
            foreach ($modernFeatures as $class => $description) {
                if (strpos($layoutContent, $class) !== false) {
                    $this->info("✅ {$description} implemented");
                } else {
                    $this->warn("⚠️ {$description} not found");
                }
            }
        }
        
        // Check for animations
        $modernHomePath = resource_path('views/modern-home.blade.php');
        if (file_exists($modernHomePath)) {
            $modernContent = file_get_contents($modernHomePath);
            
            $animations = [
                'slide-in-left' => 'Slide in left animation',
                'slide-in-right' => 'Slide in right animation',
                'slide-in-up' => 'Slide in up animation',
                'pulse' => 'Pulse animation',
                'shimmer' => 'Shimmer effect',
            ];
            
            foreach ($animations as $class => $description) {
                if (strpos($modernContent, $class) !== false) {
                    $this->info("✅ {$description} implemented");
                } else {
                    $this->warn("⚠️ {$description} not found");
                }
            }
        }
    }
}
















