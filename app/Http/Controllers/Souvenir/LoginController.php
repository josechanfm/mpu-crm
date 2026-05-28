<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SouvenirUser;
use App\Mail\SouvenirResetPasswordMail;
use App\Mail\SouvenirEmailVerification;
use Inertia\Inertia;
use LdapRecord\Connection;
use LdapRecord\Auth\BindException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\idValidatorController;

class LoginController extends Controller
{
    public function registration(Request $request){
        $request->validate([
            'uuid' => 'required|exists:souvenir_users,uuid',
        ]);
        $user = SouvenirUser::where('uuid', $request->uuid)->first();

        if ($user->active==1) {
            return redirect()->route('souvenir.login');
        }
        $user->email=null;
        $user->save();
        return Inertia::render('Souvenir/Registration',[
            'user'=>$user,
            'uuid'=>$user->uuid,
            'faculties'=>SouvenirUser::$faculties,
            'degrees'=>SouvenirUser::$degrees,
        ]);
    }
    public function register(Request $request){
        try {
            $rules = [
                'uuid' => 'required|exists:souvenir_users,uuid',
                'netId' => 'required|string|max:255',
                'fullName' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'faculty' => 'required|string|max:255',
                'degree' => 'required|string|max:255',
                'graduationYear' => 'required|integer|min:1900|max:' . (date('Y') + 10),
                'email' => 'required|email|unique:souvenir_users,email',
                'password' => 'required|string|min:8|confirmed',
                'terms' => 'required|accepted',
            ];

            $messages = [
                'uuid.required' => 'UUID is required. / UUID 是必需的。',
                'uuid.uuid' => 'Invalid UUID format. / 無效的 UUID 格式。',
                'uuid.exists' => 'UUID not found. / UUID 未找到。',
                'netId.required' => 'NetId is required. / NetId 是必需的。',
                'netId.string' => 'NetId must be text. / NetId 必須是文字。',
                'netId.max' => 'NetId too long. / NetId 太長。',
                'fullName.required' => 'Full name is required. / 全名是必需的。',
                'fullName.string' => 'Full name must be text. / 全名必須是文字。',
                'fullName.max' => 'Full name too long. / 全名太長。',
                'phone.required' => 'Phone number is required. / 電話號碼是必需的。',
                'phone.string' => 'Phone number must be text. / 電話號碼必須是文字。',
                'phone.max' => 'Phone number too long. / 電話號碼太長。',
                'faculty.required' => 'Faculty is required. / 學院是必需的。',
                'faculty.string' => 'Faculty must be text. / 學院必須是文字。',
                'faculty.max' => 'Faculty too long. / 學院太長。',
                'degree.required' => 'Degree is required. / 學位是必需的。',
                'degree.string' => 'Degree must be text. / 學位必須是文字。',
                'degree.max' => 'Degree too long. / 學位太長。',
                'graduationYear.required' => 'Graduation year is required. / 畢業年份是必需的。',
                'graduationYear.integer' => 'Graduation year must be a number. / 畢業年份必須是數字。',
                'graduationYear.min' => 'Graduation year too early. / 畢業年份太早。',
                'graduationYear.max' => 'Graduation year too late. / 畢業年份太晚。',
                'email.required' => 'Email is required. / 電子郵件是必需的。',
                'email.email' => 'Invalid email format. / 無效的電子郵件格式。',
                'email.unique' => 'Email already registered. / 電子郵件已註冊。',
                'password.required' => 'Password is required. / 密碼是必需的。',
                'password.string' => 'Password must be text. / 密碼必須是文字。',
                'password.min' => 'Password must be at least 8 characters. / 密碼至少 8 個字符。',
                'password.confirmed' => 'Password confirmation does not match. / 密碼確認不匹配。',
                'terms.required' => 'You must accept the terms. / 您必須接受條款。',
                'terms.accepted' => 'You must accept the terms. / 您必須接受條款。',
            ];
            $validate = $request->validate($rules, $messages);

            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $user = SouvenirUser::where('uuid', $request->uuid)->first();
            $user->update([
                'netid' => $request->netId,
                'name' => $request->fullName,
                'phone' => $request->phone,
                'faculty' => $request->faculty,
                'degree' => $request->degree,
                'graduationYear' => $request->graduationYear,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'email_verification_code' => $code,
                'active'=> false,
            ]);

            Mail::to($request->email)->send(new SouvenirEmailVerification($code, $user));

            return response()->json(['message' => 'Verification email sent. Please check your email for the code.', 'uuid' => $user->uuid]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return response()->json(['message' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

    public function verifyEmail(Request $request){
        try {
            $request->validate([
                'uuid' => 'required|exists:souvenir_users,uuid',
                'code' => 'required|string|size:6',
            ]);

            $user = SouvenirUser::where('uuid', $request->uuid)->first();

            if ($user->email_verification_code === $request->code) {
                $user->update([
                    'email_verified_at' => now(),
                    'email_verification_code' => null,
                    'active'=> true
                ]);

                return response()->json(['message' => 'Email verified successfully. Registration complete.']);
            }

            return response()->json(['message' => 'Invalid verification code.'], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Email verification error: ' . $e->getMessage());
            return response()->json(['message' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

    public function sendResetPasswordToEmail(Request $request){
        //dd($request->all());
        $request->validate([
            'email' => 'required|email|exists:souvenir_users,email',
        ]);

        $user = SouvenirUser::where('email', $request->email)->first();
        $token = sha1($user->id . '|' . $user->email);
        $user->token=$token;
        $user->save();
        // DB::table('password_resets')->updateOrInsert(
        //     ['email' => $user->email],
        //     ['token' => $token, 'created_at' => now()]
        // );

        $resetUrl = url('/souvenir/reset-password/' . $token . '?email=' . urlencode($user->email));
        Mail::to($user->email)->send(new SouvenirResetPasswordMail($user, $resetUrl));

        return back()->with('status', 'If the email is registered, a password reset link has been sent. / 如果電子郵件已註冊，已發送密碼重置連結。');
    }

    public function showResetPassword(Request $request, $token){
        $email = $request->query('email');
        return Inertia::render('Souvenir/ResetPassword', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:souvenir_users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);
        $user=SouvenirUser::where('email', $request->email)->where('token',$request->token)->first();
        // dd($request->all(), $user);
        // $reset = DB::table('password_resets')
        //     ->where('email', $request->email)
        //     ->where('token', $request->token)
        //     ->first();

        if (!$user) {
            return back()->withErrors(['email' => ['Invalid or expired reset link. / 重置連結無效或已過期。']]);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        //DB::table('password_resets')->where('email', $request->email)->delete();
        $user->token=null;
        $user->save();

        return redirect()->route('souvenir.login')->with('status', 'Password reset successfully. / 密碼重置成功。');
    }

    public function verifyNetid(Request $request){
        $request->validate([
            'netId' => 'required|string|size:8|regex:/^[Pp][0-9]{7}$/',
        ]);
        
        $netId = $request->netId;
        dd($request->all(), $netId);
        $idValidator = new idValidatorController();
        $isValid = $idValidator->_ipm_student_id_version_1_1($netId);
        if($isValid==false){
            return response()->json([
                'valid' => false,
                'message' => 'Invalid NetId format. / NetId 格式無效。',
            ]);
        }else{
            $user = SouvenirUser::where('netId', $netId)->first();

            if($user){
                if($user->email){
                    return response()->json([
                        'valid' => true,
                        'message' => 'NetId is already registered. You may login directly. / NetId 已經註冊，您可以直接登入。',
                        'url' => route('souvenir.login'),
                    ]);
                }else{
                    return response()->json([
                        'valid' => true,
                        'message' => 'NetId is exit the system will redirect to your existing account / NetId 已存在，系統將重定向到您的現有帳戶。',
                        'url'=> route('souvenir.registration', ['uuid'=>$user->uuid]),
                    ]); 
                }
            }else{
                return response()->json([
                    'valid' => true,
                    'message' => 'NetId is not registered. Please complete your registration. / NetId 尚未註冊。請完成您的註冊。',
                ]);
            }
        }
    }

        
    public function login(Request $request){
        return Inertia::render("Souvenir/Login");
    }

    public function loginVarify(Request $request){ 
        $request->validate([
            "email" => "required|email|exists:souvenir_users,email",
            "password" => "required",
        ]);

        $user = SouvenirUser::where("email", $request->email)->where('can_buy',true)->first();
        //dd($user, $request->all(), \Hash::check($request->password, $user->password));
        if ($user && \Hash::check($request->password, $user->password)) {
            session()->put("souvenirUser", $user);
            return to_route('souvenir');
        } else {
            return back()->withErrors(['login' => 'Invalid email or password. / 無效的電子郵件或密碼。']);
        }
    }
    
    public function logout(){
         session()->forget('souvenirUser');
        // Optionally invalidate the session
        session()->invalidate();
        return redirect()->route('souvenir.login');
        //return back()->with(["message"=>'logout successful']);

    }
}
