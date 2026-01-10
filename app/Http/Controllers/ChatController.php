<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = $this->ensureSession($request);
        $userId = $request->user()?->id;

        $messages = ChatMessage::query()
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId);

                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })
            ->latest('created_at')
            ->take(50)
            ->get()
            ->sortBy('created_at')
            ->values();

        return response()->json([
            'messages' => $messages,
            'support_user' => $this->getSupportUser()?->only(['id', 'name', 'email']),
        ]);
    }

    public function store(Request $request)
    {
        $sessionId = $this->ensureSession($request);

        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
        ]);

        $supportUser = $this->getSupportUser();
        $user = $request->user();

        $message = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $user?->id,
            'support_user_id' => $supportUser?->id,
            'sender_type' => $user ? 'user' : 'guest',
            'sender_name' => $data['name'] ?? $user?->name ?? 'Khách',
            'sender_email' => $data['email'] ?? $user?->email,
            'message' => $data['message'],
        ]);

        return response()->json([
            'message' => $message,
        ], 201);
    }

    protected function ensureSession(Request $request): string
    {
        if (!$request->session()->has('chat_session_id')) {
            $request->session()->put('chat_session_id', (string) Str::uuid());
        }

        return (string) $request->session()->get('chat_session_id');
    }

    protected function getSupportUser(): ?User
    {
        $supportUserId = config('library.support_user_id');

        if ($supportUserId) {
            $user = User::find($supportUserId);
            if ($user) {
                return $user;
            }
        }

        return User::where('role', 'support')
            ->orWhere('role', 'admin')
            ->orderBy('id')
            ->first();
    }
}

