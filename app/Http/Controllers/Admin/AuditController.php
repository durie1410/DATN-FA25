<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        $filters = [
            'user_id' => $request->get('user_id'),
            'action' => $request->get('action'),
            'model_type' => $request->get('model_type'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'search' => $request->get('search'),
        ];

        // Remove null values
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        $logs = AuditService::getLogs($filters)->paginate(20);

        // Get filter options
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $modelTypes = AuditLog::select('model_type')->distinct()->whereNotNull('model_type')->pluck('model_type');
        $users = \App\Models\User::select('id', 'name')->get();

        return view('admin.audit.index', compact('logs', 'actions', 'modelTypes', 'users', 'filters'));
    }

    /**
     * Show specific audit log
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user', 'model');
        return view('admin.audit.show', compact('auditLog'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $filters = [
            'user_id' => $request->get('user_id'),
            'action' => $request->get('action'),
            'model_type' => $request->get('model_type'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        $logs = AuditService::getLogs($filters)->get();

        $filename = 'audit_logs_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Thời gian',
                'Người dùng',
                'Hành động',
                'Model',
                'Mô tả',
                'IP Address',
                'URL'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user ? $log->user->name : 'N/A',
                    $log->action,
                    $log->model_type ? class_basename($log->model_type) : 'N/A',
                    $log->formatted_description,
                    $log->ip_address,
                    $log->url
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get audit statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $stats = AuditService::getStatistics($days);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Clear old audit logs
     */
    public function clearOld(Request $request)
    {
        $days = $request->get('days', 90);
        $cutoffDate = now()->subDays($days);

        $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        return response()->json([
            'success' => true,
            'message' => "Đã xóa {$deletedCount} bản ghi audit log cũ hơn {$days} ngày."
        ]);
    }

    /**
     * Get real-time audit logs (for dashboard)
     */
    public function realTime(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        
        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'description' => $log->formatted_description,
                    'user' => $log->user ? $log->user->name : 'Unknown',
                    'time' => $log->created_at->diffForHumans(),
                    'created_at' => $log->created_at->format('H:i:s')
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}























