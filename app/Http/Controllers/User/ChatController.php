<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $history = $request->input('history', []);

        $systemPrompt = [
            "role" => "system",
            "content" => "
Bạn là trợ lý am hiểu các trò chơi dân gian Việt Nam: kéo co, trốn tìm, ô ăn quan, nhảy dây, rồng rắn lên mây...
Hãy trả lời tự nhiên, thân thiện, dễ hiểu cho trẻ em và phụ huynh.
Không dùng markdown (#, **...).
Được phép xuống dòng.
Trả lời toàn bộ bằng tiếng Việt Nam.
"
        ];

        $messages = array_merge(
            [$systemPrompt],
            $history,
            [
                [
                    "role" => "user",
                    "content" => $message
                ]
            ]
        );

        $payload = [
            "model" => env('HF_MODEL', 'Qwen/Qwen2.5-7B-Instruct'),
            "messages" => $messages,
            "temperature" => 0.7,
            "top_p" => 0.9,
            "max_tokens" => 600
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('HF_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post("https://router.huggingface.co/v1/chat/completions", $payload);

        if ($response->failed()) {
            return response()->json([
                "error" => $response->body()
            ], 500);
        }

        $data = $response->json();

        return response()->json([
            "reply" => $data["choices"][0]["message"]["content"] 
                ?? "Sorry, I can't answer this question yet."
        ]);
    }
}
