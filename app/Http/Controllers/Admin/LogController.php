<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // For demo purposes, we'll create some sample logs
        $logs = $this->getSampleLogs($request);
        
        // Get statistics
        $totalLogs = count($logs);
        $loginLogs = collect($logs)->where('type', 'login')->count();
        $errorLogs = collect($logs)->where('type', 'error')->count();
        $securityLogs = collect($logs)->where('type', 'security')->count();
        
        // Get users for filter
        $users = \App\Models\User::select('id', 'name')->get();
        
        return view('admin.logs.index', compact(
            'logs', 
            'totalLogs', 
            'loginLogs', 
            'errorLogs', 
            'securityLogs',
            'users'
        ));
    }
    
    public function show($id)
    {
        // Get log detail
        $log = $this->getSampleLog($id);
        
        return response()->json($log);
    }
    
    public function export(Request $request)
    {
        $logs = $this->getSampleLogs($request);
        
        $filename = 'logs_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Thời gian', 'Người dùng', 'Loại', 'Hành động', 'Mô tả', 'IP Address', 'User Agent']);
            
            // Add data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log['id'],
                    $log['created_at'],
                    $log['user_name'] ?? 'System',
                    $log['type'],
                    $log['action'],
                    $log['description'],
                    $log['ip_address'] ?? 'N/A',
                    $log['user_agent'] ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function exportSingle($id)
    {
        $log = $this->getSampleLog($id);
        
        $filename = 'log_' . $id . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($log)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }
    
    public function clearOld(Request $request)
    {
        $days = $request->get('days', 30);
        $cutoffDate = Carbon::now()->subDays($days);
        
        // In a real application, you would delete from the actual logs table
        // For demo purposes, we'll simulate this
        
        $deletedCount = rand(50, 200); // Simulate deleted logs
        
        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
            'message' => "Đã xóa {$deletedCount} logs cũ hơn {$days} ngày"
        ]);
    }
    
    public function realTime()
    {
        // Get recent logs for real-time monitoring
        $logs = $this->getRecentLogs();
        
        return response()->json([
            'logs' => $logs,
            'timestamp' => now()->toISOString()
        ]);
    }
    
    private function getSampleLogs(Request $request)
    {
        // Sample logs data for demonstration
        $sampleLogs = [
            [
                'id' => 1,
                'created_at' => now()->subMinutes(5),
                'user_id' => 1,
                'user_name' => 'Admin User',
                'type' => 'login',
                'action' => 'User Login',
                'description' => 'User admin@library.com logged in successfully',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 2,
                'created_at' => now()->subMinutes(10),
                'user_id' => 2,
                'user_name' => 'Staff User',
                'type' => 'create',
                'action' => 'Book Created',
                'description' => 'New book "Laravel Advanced Programming" was created',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 3,
                'created_at' => now()->subMinutes(15),
                'user_id' => 1,
                'user_name' => 'Admin User',
                'type' => 'update',
                'action' => 'User Updated',
                'description' => 'User profile for john@example.com was updated',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 4,
                'created_at' => now()->subMinutes(20),
                'user_id' => 3,
                'user_name' => 'Regular User',
                'type' => 'delete',
                'action' => 'Book Deleted',
                'description' => 'Book "Old PHP Guide" was deleted',
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 5,
                'created_at' => now()->subMinutes(25),
                'user_id' => null,
                'user_name' => null,
                'type' => 'error',
                'action' => 'System Error',
                'description' => 'Database connection timeout occurred',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System'
            ],
            [
                'id' => 6,
                'created_at' => now()->subMinutes(30),
                'user_id' => 1,
                'user_name' => 'Admin User',
                'type' => 'security',
                'action' => 'Failed Login Attempt',
                'description' => 'Failed login attempt for admin@library.com from suspicious IP',
                'ip_address' => '203.0.113.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 7,
                'created_at' => now()->subMinutes(35),
                'user_id' => 2,
                'user_name' => 'Staff User',
                'type' => 'logout',
                'action' => 'User Logout',
                'description' => 'User staff@library.com logged out',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 8,
                'created_at' => now()->subMinutes(40),
                'user_id' => 1,
                'user_name' => 'Admin User',
                'type' => 'create',
                'action' => 'Category Created',
                'description' => 'New category "Web Development" was created',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 9,
                'created_at' => now()->subMinutes(45),
                'user_id' => 3,
                'user_name' => 'Regular User',
                'type' => 'update',
                'action' => 'Profile Updated',
                'description' => 'User profile information was updated',
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'id' => 10,
                'created_at' => now()->subMinutes(50),
                'user_id' => 2,
                'user_name' => 'Staff User',
                'type' => 'create',
                'action' => 'Borrow Record',
                'description' => 'New borrow record created for book "JavaScript Guide"',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]
        ];
        
        // Apply filters
        $filteredLogs = collect($sampleLogs);
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $filteredLogs = $filteredLogs->filter(function($log) use ($search) {
                return stripos($log['description'], $search) !== false ||
                       stripos($log['action'], $search) !== false ||
                       stripos($log['user_name'] ?? '', $search) !== false;
            });
        }
        
        if ($request->filled('type')) {
            $filteredLogs = $filteredLogs->where('type', $request->get('type'));
        }
        
        if ($request->filled('user_id')) {
            $filteredLogs = $filteredLogs->where('user_id', $request->get('user_id'));
        }
        
        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->get('date_from'));
            $filteredLogs = $filteredLogs->filter(function($log) use ($dateFrom) {
                return Carbon::parse($log['created_at'])->gte($dateFrom);
            });
        }
        
        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->get('date_to'))->endOfDay();
            $filteredLogs = $filteredLogs->filter(function($log) use ($dateTo) {
                return Carbon::parse($log['created_at'])->lte($dateTo);
            });
        }
        
        return $filteredLogs->values()->all();
    }
    
    private function getSampleLog($id)
    {
        $logs = $this->getSampleLogs(new Request());
        $log = collect($logs)->firstWhere('id', $id);
        
        if (!$log) {
            abort(404, 'Log not found');
        }
        
        // Add additional details for single log view
        $log['old_values'] = [
            'name' => 'Old Name',
            'email' => 'old@example.com'
        ];
        
        $log['new_values'] = [
            'name' => 'New Name',
            'email' => 'new@example.com'
        ];
        
        return $log;
    }
    
    private function getRecentLogs()
    {
        // Return recent logs for real-time monitoring
        return [
            [
                'id' => rand(1000, 9999),
                'created_at' => now()->toISOString(),
                'type' => 'info',
                'description' => 'System health check completed successfully'
            ],
            [
                'id' => rand(1000, 9999),
                'created_at' => now()->subSeconds(30)->toISOString(),
                'type' => 'create',
                'description' => 'New user registration completed'
            ],
            [
                'id' => rand(1000, 9999),
                'created_at' => now()->subSeconds(60)->toISOString(),
                'type' => 'update',
                'description' => 'Book availability status updated'
            ]
        ];
    }
}
