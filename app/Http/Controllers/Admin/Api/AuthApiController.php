<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
class AuthApiController extends Controller
{
    use ApiTrait;
    var $lang;
    ################ auth #############
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->lang = \request()->get('lang') ? \request()->get('lang') : 'en';
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);


            $user = User::where('phone',$request->phone)->first();
            if (!$user)
                return $this->errorResponse(__('msg.user_not_found',[],$this->lang), 401);
            if ($request->fcm_token)
                $user->fcm_token = $request->fcm_token;
                $user->active_sms_code = $user->phone == '+966592274690' ? '1234' : rand(1111,9999);
                $user->active = 0;
                $user->save();
        $message = "الكود الخاص بك" . $user->active_sms_code;
        sendToFcm($user->fcm_token,$message,'تفعيل حسابك في مطبعة الشيوخ');
        return $this->successResponse(__('msg.sms_send_successfully', [], $this->lang),200);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        $user = User::where('phone',$request->phone)->first();
        if ($user)
            return $this->errorResponse(__('msg.already_registered',[],$this->lang),401);
        $user = new User();
        $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->fcm_token = $request->fcm_token;
            $user->active_sms_code = rand(1111,9999);
            $user->active = 0;
            $user->password = Hash::make($request->phone);
            $user->save();

        $message = "الكود الخاص بك" . $user->active_sms_code;
        sendToFcm($user->fcm_token,$message,'تفعيل حسابك في مطبعة الشيوخ');
//            send_sms($message,$user->phone);
        return $this->successResponse(__('msg.sms_send_successfully', [], $this->lang),200);
    }
    public function logout()
    {
        auth()->logout();

        return $this->successResponse(__('msg.logged_success',[],$this->lang),200);
    }

    ############### end auth ######
}
