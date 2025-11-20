<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiChatController extends Controller
{
    /**
     * Gửi tin nhắn đến AI và nhận phản hồi
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $userId = auth()->id();

        try {
            // Lưu tin nhắn của user
            $userChatMessage = AiChatMessage::create([
                'user_id' => $userId,
                'role' => 'user',
                'message' => $userMessage,
            ]);

            // Lấy lịch sử chat gần đây (10 tin nhắn cuối)
            $chatHistory = AiChatMessage::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->reverse()
                ->map(function ($msg) {
                    return [
                        'role' => $msg->role === 'user' ? 'user' : 'assistant',
                        'content' => $msg->message
                    ];
                })
                ->toArray();

            // Gọi AI để lấy phản hồi
            $aiResponse = $this->getAiResponse($chatHistory);

            // Lưu phản hồi của AI
            $aiChatMessage = AiChatMessage::create([
                'user_id' => $userId,
                'role' => 'assistant',
                'message' => $aiResponse,
            ]);

            return response()->json([
                'success' => true,
                'message' => $aiResponse,
                'timestamp' => $aiChatMessage->created_at->format('H:i'),
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat Error', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'message' => $userMessage
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xin lỗi, tôi đang gặp sự cố. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    /**
     * Lấy lịch sử chat
     */
    public function getHistory(Request $request)
    {
        $userId = auth()->id();

        $messages = AiChatMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get()
            ->map(function ($msg) {
                return [
                    'role' => $msg->role,
                    'message' => $msg->message,
                    'timestamp' => $msg->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Xóa lịch sử chat
     */
    public function clearHistory(Request $request)
    {
        $userId = auth()->id();

        AiChatMessage::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa lịch sử chat',
        ]);
    }

    /**
     * Gọi AI API để lấy phản hồi
     */
    private function getAiResponse($chatHistory)
    {
        $apiKey = env('OPENAI_API_KEY');

        // Nếu không có API key, sử dụng phản hồi mặc định
        if (!$apiKey || $apiKey === 'your-openai-api-key-here') {
            return $this->getDefaultResponse($chatHistory);
        }

        try {
            // Thêm system message
            $messages = array_merge([
                [
                    'role' => 'system',
                    'content' => 'Bạn là trợ lý AI thông minh của Thư Viện LIBHUB. Nhiệm vụ của bạn là hỗ trợ khách hàng và nhân viên về:
- Tìm kiếm sách, tác giả, thể loại
- Hướng dẫn mua sách, đặt hàng
- Giải đáp thắc mắc về dịch vụ thư viện
- Hỗ trợ thanh toán, giao hàng
- Tư vấn sách phù hợp với nhu cầu

Hãy trả lời ngắn gọn, thân thiện và hữu ích. Sử dụng tiếng Việt.'
                ]
            ], $chatHistory);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
                    'messages' => $messages,
                    'max_tokens' => 500,
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi không thể trả lời lúc này.';
            } else {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return $this->getDefaultResponse($chatHistory);
            }

        } catch (\Exception $e) {
            Log::error('AI Response Error', ['error' => $e->getMessage()]);
            return $this->getDefaultResponse($chatHistory);
        }
    }

    /**
     * Phản hồi mặc định khi không có API key hoặc API lỗi
     */
    private function getDefaultResponse($chatHistory)
    {
        $lastMessage = end($chatHistory);
        $userMessage = strtolower($lastMessage['content'] ?? '');

        // Phản hồi thông minh dựa trên từ khóa
        if (str_contains($userMessage, 'xin chào') || str_contains($userMessage, 'hello') || str_contains($userMessage, 'hi')) {
            return "Xin chào! Tôi là trợ lý AI của Thư Viện LIBHUB. Tôi có thể giúp gì cho bạn hôm nay? 😊";
        }

        if (str_contains($userMessage, 'tìm sách') || str_contains($userMessage, 'sách')) {
            return "Bạn có thể sử dụng thanh tìm kiếm ở trên để tìm sách theo tên, tác giả hoặc thể loại. Tôi cũng có thể gợi ý sách phù hợp nếu bạn cho tôi biết bạn thích thể loại nào? 📚";
        }

        if (str_contains($userMessage, 'đặt hàng') || str_contains($userMessage, 'mua')) {
            return "Để đặt hàng, bạn chỉ cần:\n1. Thêm sách vào giỏ hàng\n2. Vào giỏ hàng và chọn 'Thanh toán'\n3. Điền thông tin giao hàng\n4. Chọn phương thức thanh toán (COD, chuyển khoản, hoặc VNPay)\n5. Xác nhận đặt hàng\n\nBạn cần hỗ trợ thêm không? 🛒";
        }

        if (str_contains($userMessage, 'thanh toán') || str_contains($userMessage, 'payment')) {
            return "Chúng tôi hỗ trợ 3 phương thức thanh toán:\n💵 Thanh toán khi nhận hàng (COD)\n🏦 Chuyển khoản ngân hàng\n💳 Thanh toán online qua VNPay (ATM/Visa/MasterCard)\n\nBạn muốn biết thêm chi tiết về phương thức nào? 💰";
        }

        if (str_contains($userMessage, 'giao hàng') || str_contains($userMessage, 'ship')) {
            return "Chúng tôi miễn phí vận chuyển cho tất cả đơn hàng! 🚚\nThời gian giao hàng: 2-5 ngày làm việc tùy khu vực.\nBạn có thể theo dõi đơn hàng trong mục 'Đơn hàng của tôi'. 📦";
        }

        if (str_contains($userMessage, 'giá') || str_contains($userMessage, 'bao nhiêu')) {
            return "Giá sách được hiển thị rõ ràng trên từng sản phẩm. Chúng tôi thường xuyên có chương trình khuyến mãi và giảm giá đặc biệt. Bạn muốn tìm sách trong khoảng giá nào? 💰";
        }

        if (str_contains($userMessage, 'tài khoản') || str_contains($userMessage, 'đăng ký')) {
            return "Bạn có thể đăng ký tài khoản miễn phí để:\n✅ Lưu giỏ hàng\n✅ Theo dõi đơn hàng\n✅ Nhận ưu đãi đặc biệt\n✅ Đánh giá và bình luận sách\n\nClick vào 'Đăng ký' ở góc trên bên phải để bắt đầu! 👤";
        }

        if (str_contains($userMessage, 'cảm ơn') || str_contains($userMessage, 'thank')) {
            return "Rất vui được hỗ trợ bạn! Nếu có thắc mắc gì khác, đừng ngại hỏi tôi nhé. Chúc bạn có trải nghiệm mua sắm vui vẻ! 😊📚";
        }

        if (str_contains($userMessage, 'giờ mở cửa') || str_contains($userMessage, 'thời gian')) {
            return "Thư viện LIBHUB phục vụ:\n🕐 Thứ 2 - Thứ 6: 8:00 - 20:00\n🕐 Thứ 7 - Chủ nhật: 9:00 - 18:00\n\nWebsite hoạt động 24/7 để bạn có thể đặt hàng bất cứ lúc nào! 🌟";
        }

        // Phản hồi mặc định
        return "Cảm ơn bạn đã liên hệ! Tôi là trợ lý AI của Thư Viện LIBHUB. Tôi có thể giúp bạn về:\n\n📚 Tìm kiếm sách\n🛒 Đặt hàng và thanh toán\n🚚 Giao hàng\n👤 Tài khoản\n💰 Giá cả và khuyến mãi\n\nBạn cần hỗ trợ gì? Hãy hỏi tôi nhé! 😊";
    }
}
