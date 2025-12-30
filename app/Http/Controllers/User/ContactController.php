<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Contact as ContactModel;
use Illuminate\Support\Facades\DB;


class ContactController extends Controller
{
    public function contact()
    {
        // generate an image captcha (base64 PNG) and store the answer in session
        $captcha = $this->generateImageCaptcha();
        // $captcha = ['code' => '1234', 'image' => 'data:image/png;base64,...']
        session(['contact_captcha' => $captcha['code']]);
        return view('user/contact')->with('captcha_image', $captcha['image']);
    }

    // AJAX endpoint to get a fresh image captcha
    public function captchaImage(Request $request)
    {
        $captcha = $this->generateImageCaptcha();
        session(['contact_captcha' => $captcha['code']]);
        return response()->json(['success' => true, 'image' => $captcha['image']]);
    }

    // helper to create a simple PNG captcha using GD and return code+data-url
    protected function generateImageCaptcha($length = 4)
    {
        if (!extension_loaded('gd')) {
            // fallback to numeric string and no image
            $code = (string) rand(1000, 9999);
            return ['code' => $code, 'image' => ''];
        }

        $width = 140;
        $height = 48;
        $im = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($im, 245, 245, 245);
        $textc = imagecolorallocate($im, 34, 34, 34);
        $noiseC = imagecolorallocate($im, 180, 180, 180);
        imagefilledrectangle($im, 0, 0, $width, $height, $bg);

        // generate code (digits)
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= (string) rand(0, 9);
        }

        // add noise: random lines
        for ($i = 0; $i < 6; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $x2 = rand(0, $width);
            $y2 = rand(0, $height);
            imageline($im, $x1, $y1, $x2, $y2, $noiseC);
        }

        // add noise: pixels
        for ($i = 0; $i < 400; $i++) {
            imagesetpixel($im, rand(0, $width), rand(0, $height), $noiseC);
        }

        // draw each digit with slight vertical offset and random angle (using imagettftext if available)
        $useTtf = function_exists('imagettftext');
        if ($useTtf) {
            // try to use a system font; if not available, fallback to imagestring
            $possible = [public_path('fonts/arial.ttf'), public_path('fonts/Roboto-Regular.ttf')];
            $font = null;
            foreach ($possible as $p) { if (file_exists($p)) { $font = $p; break; } }
            if (!$font) $useTtf = false;
        }

        if ($useTtf) {
            $fontSize = 18;
            $x = 12;
            for ($i = 0; $i < strlen($code); $i++) {
                $angle = rand(-18, 18);
                $char = $code[$i];
                $y = rand(28, 36);
                imagettftext($im, $fontSize, $angle, $x, $y, $textc, $font, $char);
                $x += 24;
            }
        } else {
            // fallback to built-in font
            $x = 18;
            for ($i = 0; $i < strlen($code); $i++) {
                $y = rand(8, 18);
                imagestring($im, 5, $x, $y, $code[$i], $textc);
                $x += 26;
            }
        }

        ob_start();
        imagepng($im);
        $png = ob_get_clean();
        imagedestroy($im);

        $data = 'data:image/png;base64,' . base64_encode($png);
        return ['code' => $code, 'image' => $data];
    }

    // Captcha functionality removed. Contact form now submits directly.

    // Handle contact form submission and validate the captcha
    public function send(Request $request)
    {
        $isAjax = $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
            'captcha' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // validate captcha
        $expected = session('contact_captcha');
        $givenCaptcha = $request->input('captcha');
        if ($expected === null || (int) $givenCaptcha !== (int) $expected) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Captcha is incorrect.'], 400);
            }
            return redirect()->back()->withErrors(['captcha' => 'Captcha không đúng.'])->withInput();
        }

        // Store contact into DB for admin panel inside a transaction.
        try {
            DB::beginTransaction();
            $contact = ContactModel::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
                'ip' => $request->ip(),
                'user_agent' => substr($request->header('User-Agent') ?? '', 0, 512),
            ]);
            DB::commit();
            // clear captcha after successful submit
            session()->forget('contact_captcha');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contact DB save error: ' . $e->getMessage());
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Database error: Could not save message.'], 500);
            }
            return redirect()->back()->withErrors(['db' => 'Không thể lưu thông điệp, vui lòng thử lại.'])->withInput();
        }

        // Send email/notification to admin with contact details
        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
        $body = "New contact message:\n\n" .
            "Name: " . $request->input('name') . "\n" .
            "Email: " . $request->input('email') . "\n" .
            "Message:\n" . $request->input('message') . "\n\n" .
            "IP: " . $request->ip() . "\n" .
            "User Agent: " . substr($request->header('User-Agent') ?? '', 0, 200);

        try {
            Mail::raw($body, function ($m) use ($adminEmail) {
                $m->to($adminEmail)->subject('New contact message from website');
            });
        } catch (\Exception $e) {
            Log::error('Contact mail error: ' . $e->getMessage());
            if (app()->environment('local') || config('app.debug')) {
                // In dev, keep user flow but store message in session so admin can inspect
                session()->push('dev_contact_messages', $body);
                if ($isAjax) {
                    return response()->json(['success' => true, 'message' => 'Message recorded (dev).']);
                }
                return redirect()->back()->with('success', 'Message recorded (dev).');
            }
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Mail config error.'], 500);
            }
            return redirect()->back()->withErrors(['mail' => 'Không thể gửi thông báo tới quản trị. Kiểm tra cấu hình mail.']);
        }

        if ($isAjax) {
            return response()->json(['success' => true, 'message' => 'Message sent successfully.']);
        }
return redirect()
    ->route('user.contact')
    ->with('success', 'Message sent successfully.');



    }

}
