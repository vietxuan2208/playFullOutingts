<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 20;
        $status = $request->query('status'); // ví dụ ?status=unread hoặc ?status=all

        $query = Contact::query();

        if ($status === 'unread') {
            $query->whereNull('replied_at');
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate($perPage);
        return view('admin.contact', compact('messages'));
    }

    // AJAX: update contact status (mark as read/unread)
    public function updateStatus(Request $request, $id)
    {
        $status = $request->input('status');
        $contact = Contact::findOrFail($id);
        $contact->read = $status ? 1 : 0;
        $contact->save();
        return response()->json(['success' => true, 'read' => $contact->read]);
    }

    // Reply to a contact message and send email to the sender
    public function reply(Request $request, $id): JsonResponse
    {
        $request->validate(['reply' => 'required|string']);
        $contact = Contact::findOrFail($id);

        $replyText = $request->input('reply');

        // Build email body (include original message for context)
        $body = "Hello " . $contact->name . ",\n\n" . $replyText . "\n\n--- Original message ---\n" . $contact->message;

        try {
            Mail::raw($body, function ($m) use ($contact) {
                $m->to($contact->email)->subject('Reply to your message');
            });

            // Save reply and timestamp, mark as read
            $contact->reply = $replyText;
            $contact->replied_at = now();
            $contact->read = 1;
            $contact->save();

            // Trả về dữ liệu mới để frontend cập nhật badge, không xóa row
            return response()->json([
                'success' => true,
                'message' => 'Reply sent',
                'read' => $contact->read,
                'replied_at' => $contact->replied_at->format('Y-m-d H:i'),
            ]);
        } catch (\Exception $e) {
            Log::error('Admin reply error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send reply'], 500);
        }
    }
    public function destroy($id): JsonResponse
    {
        try {
            $contact = Contact::findOrFail($id);

            // Xóa cứng khỏi DB
            $contact->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contact deleted permanently'
            ]);
        } catch (\Exception $e) {
            Log::error('Admin delete contact error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contact'
            ], 500);
        }
    }
}
