<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ChatAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatMessage::query()
            ->selectRaw('MIN(id) as first_id, MAX(id) as last_id, session_id, user_id, MIN(created_at) as first_at, MAX(created_at) as last_at')
            ->groupBy('session_id', 'user_id');

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('sender_name', 'like', '%' . $keyword . '%')
                    ->orWhere('sender_email', 'like', '%' . $keyword . '%');
            });
        }

        $threads = $query
            ->orderByDesc('last_at')
            ->paginate(20);

        // Load latest preview + basic meta for each thread
        $threadData = $threads->getCollection()->map(function ($row) {
            $lastMessage = ChatMessage::find($row->last_id);
            $firstMessage = ChatMessage::find($row->first_id);

            return (object)[
                'session_id' => $row->session_id,
                'user_id' => $row->user_id,
                'first_at' => Carbon::parse($row->first_at),
                'last_at' => Carbon::parse($row->last_at),
                'preview' => $lastMessage?->message,
                'last_sender' => $lastMessage?->sender_name,
                'name' => $firstMessage?->sender_name,
                'email' => $firstMessage?->sender_email,
                'unread_count' => ChatMessage::where('session_id', $row->session_id)
                    ->whereNull('read_at')
                    ->where('sender_type', '!=', 'support')
                    ->count(),
            ];
        });

        $threads->setCollection($threadData);

        return view('admin.chat.index', [
            'threads' => $threads,
            'keyword' => $request->input('keyword'),
        ]);
    }

    public function show($sessionId)
    {
        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        if ($messages->isEmpty()) {
            abort(404);
        }

        $first = $messages->first();

        // Mark as read
        ChatMessage::where('session_id', $sessionId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.chat.show', [
            'sessionId' => $sessionId,
            'messages' => $messages,
            'customerName' => $first->sender_name ?? 'Khách',
            'customerEmail' => $first->sender_email,
        ]);
    }

    public function reply(Request $request, $sessionId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $admin = $request->user();
        if (!$admin) {
            abort(403);
        }

        $base = ChatMessage::where('session_id', $sessionId)->first();
        if (!$base) {
            abort(404);
        }

        ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $base->user_id,
            'support_user_id' => $admin->id,
            'sender_type' => 'support',
            'sender_name' => $admin->name ?? 'Hỗ trợ',
            'sender_email' => $admin->email,
            'message' => $request->input('message'),
        ]);

        return redirect()
            ->route('admin.chat.show', $sessionId)
            ->with('success', 'Đã gửi trả lời cho khách.');
    }
}


