<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RealTimeNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $limit = $request->get('limit', 20);
        
        $notifications = RealTimeNotificationService::getUserNotifications($user->id, $limit);
        $unreadCount = RealTimeNotificationService::getUnreadCount($user->id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'total' => count($notifications)
            ]
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $notificationId): JsonResponse
    {
        $user = $request->user();
        
        RealTimeNotificationService::markAsRead($user->id, $notificationId);
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        
        RealTimeNotificationService::markAllAsRead($user->id);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();
        $count = RealTimeNotificationService::getUnreadCount($user->id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }

    /**
     * Mark user as online
     */
    public function markOnline(Request $request): JsonResponse
    {
        $user = $request->user();
        
        RealTimeNotificationService::markUserOnline($user->id);
        
        return response()->json([
            'success' => true,
            'message' => 'User marked as online'
        ]);
    }

    /**
     * Mark user as offline
     */
    public function markOffline(Request $request): JsonResponse
    {
        $user = $request->user();
        
        RealTimeNotificationService::markUserOffline($user->id);
        
        return response()->json([
            'success' => true,
            'message' => 'User marked as offline'
        ]);
    }

    /**
     * Send test notification
     */
    public function sendTest(Request $request): JsonResponse
    {
        $user = $request->user();
        
        RealTimeNotificationService::sendToUser(
            $user->id,
            'test',
            'Test Notification',
            'This is a test notification to verify real-time functionality.',
            ['test' => true]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Test notification sent'
        ]);
    }
}























