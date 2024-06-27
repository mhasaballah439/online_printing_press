<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\ContactUs;
use App\Models\Notifacations;
use App\Models\Onbording;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PrintPrice;
use App\Models\Settings;
use App\Models\Slider;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Illuminate\Support\Facades\Mail;

class SystemApiController extends Controller
{
    use ApiTrait;

    var $lang;
    var $user;

    public function __construct()
    {
        $this->lang = \request()->get('lang') ? \request()->get('lang') : 'en';
        $this->user = auth()->user();
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $this->user->id,
            'phone' => 'required|unique:users,phone,' . $this->user->id,
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        $user = User::find($this->user->id);
        if ($user) {
            if ($request->hasFile('image')) {
                $file = $user->image;
                $filename = public_path() . '' . $file;
                File::delete($filename);

                $image = $request->file('image');
                $image_name = $image->hashName();
                $image->move(public_path('/uploads/users/'), $image_name);

                $filePath = "/uploads/users/" . $image_name;
            }
            if ($request->name)
                $user->name = $request->name;
            if ($request->email)
                $user->email = $request->email;
            if ($request->phone)
                $user->phone = $request->phone;
            if (isset($filePath))
                $user->image = $filePath;
            $user->save();

            return $this->dataResponse(__('msg.user_updated_successfully', [], $this->lang), user_data(), 200);
        }


    }


    public function getUserData()
    {
        return $this->dataResponse('User found successfully', user_data(), 200);
    }


    public function checkVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        $user = User::where('phone', $request->phone)->first();
        if (!$user)
            return $this->errorResponse(__('msg.user_not_found'), 401);

        if ($request->code != $user->active_sms_code)
            return $this->errorResponse(__('msg.code_error', [], $this->lang), 401);

        $user->active = 1;
        $user->save();
        $token = auth()->login($user);
        $data = [
            'user_data' => user_data(),
            'token' => $token
        ];
        return $this->dataResponse(__('msg.activated_successfully', [], $this->lang), $data, 200);
    }

    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        $user = User::where('phone', $request->phone)->first();
        if (!$user)
            return $this->errorResponse(__('msg.user_not_found', [], $this->lang), 401);

        $user->active_sms_code = rand(1111, 9999);
        $user->save();
        $message = "الكود الخاص بك" . $user->active_sms_code;
        sendToFcm($user->fcm_token, $message, 'تفعيل حسابك في مطبعة الشيوخ');
//        send_sms($message,$user->phone);
        return $this->successResponse(__('msg.sms_send_successfully', [], $this->lang), 200);
    }

    public function sliders()
    {
        $sliders = Slider::Active()->get();

        $data = $sliders->map(function ($slider) {
            return [
                'image' => $slider->image ? asset('/public' . $slider->image) : '',
                'title' => $slider->title ? $slider->title_api($this->lang) : '',
                'desc' => $slider->desc ? $slider->desc_api($this->lang) : '',
            ];
        });

        return $this->dataResponse('Data found successfully', $data, 200);
    }

    public function inBordingData()
    {
        $in_bording = Onbording::Active()->get();

        $data = $in_bording->map(function ($bording) {
            return [
                'image' => $bording->image ? asset('/public' . $bording->image) : '',
                'title' => $bording->title ? $bording->title_api($this->lang) : '',
                'desc' => $bording->desc ? $bording->desc_api($this->lang) : '',
            ];
        });

        return $this->dataResponse('Data found successfully', $data, 200);
    }

    public function settings()
    {
        $setting = Settings::first();
        $data = [
            'phone' => $setting->phone ? $setting->phone : '',
            'address' => $setting->address ? $setting->address : '',
            'email' => $setting->email ? $setting->email : '',
            'seo_title' => $setting->seo_title ? $setting->seo_title : '',
            'seo_desc' => $setting->seo_desc ? $setting->seo_desc : '',
            'seo_keyword' => $setting->seo_keyword ? $setting->seo_keyword : '',
            'whatsapp' => $setting->whatsapp ? $setting->whatsapp : '',
            'facebook' => $setting->facebook ? $setting->facebook : '',
            'instagram' => $setting->instagram ? $setting->instagram : '',
            'twitter' => $setting->twitter ? $setting->twitter : '',
            'color_cover_price' => $setting->color_cover_price,
            'work_pressure_note' => $setting->work_pressure_note,
            'tax' => $setting->tax,
            'active_work_pressure' => (integer)$setting->active_work_pressure,
            'logo' => $setting->logo ? asset('public' . $setting->logo) : '',
            'about_us' => $setting->about_us_api($this->lang),
            'terms_conditions' => $setting->terms_conditions_api($this->lang),
            'policy' => $setting->policy_api($this->lang),
        ];

        return $this->dataResponse('Data found successfully', $data, 200);
    }

    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);

        $contact = new ContactUs();
        $contact->name = $this->user->name;
        $contact->email = $this->user->email;
        $contact->message = $request->message;
        $contact->save();

        $notify = new Notifacations();
        $notify->is_admin = 1;
        $notify->notify = 'رسالة جديدة في تواصل معنا';
        $notify->save();

        return $this->successResponse(__('msg.send_successfully', [], $this->lang), 200);
    }

    public function branches()
    {
        $branches = Branch::Active()->get();
        $data = $branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name_api($this->lang),
                'address' => $branch->address_api($this->lang),
                'image' => $branch->image ? asset('public' . $branch->image) : ''
            ];
        });

        return $this->dataResponse('Branches get success', $data, 200);
    }

    public function cartData(Request $request)
    {
        $cart = Cart::where('user_id', $this->user->id);

        if ($request->cart_items) {
            $branch = Branch::find($request->branch_id);
            if ($branch) {
                if ($branch->st_time <= date('H:i') && $branch->end_time >= date('H:i')) {
                    $cart_items = json_decode($request->cart_items);

                    $cart = $cart->whereIn('id', $cart_items);
                } else {
                    return $this->errorResponse($branch->st_time . '-' . $branch->end_time . 'نحن مغلقون الان نفتح ما بين الساعة ', 401);
                }
            }

        }
        $cart = $cart->get();

        $setting = Settings::first();
        $sub_total = 0;
        $tax_price = 0;
        $total = 0;
        $color_cover_price = 0;
        $dataList = [];
        if (count($cart) > 0) {
            foreach ($cart as $item) {
                $sub_total += $item->sub_total;
                $tax_price += $item->tax_price;
                $total += $item->total;
                $color_cover_price += $item->color_cover_price;
                $dataList[] = [
                    'id' => $item->id,
                    'paper_size' => $item->paper_size,
                    'sub_total' => round($item->sub_total, 2),
                    'tax_price' => round($item->tax_price, 2),
                    'total' => round($item->total, 2),
                    'color_cover_price' => round($item->color_cover_price, 2),
                    'qty' => $item->qty,
                    'tax' => $setting->tax,
                    'price' => $item->price,
                    'printing_color' => (integer)$item->printing_color,
                    'first_page_color' => $item->first_page_color,
                    'packaging_id' => $item->packaging_id,
                    'packaging_type' => $item->packaging_type,
                    'aspects_printing' => $item->aspects_printing,
                    'paper_type' => $item->paper_type,
                    'page_layout' => $item->page_layout,
                    'branch_id' => $item->branch_id,
                    'aspects_printing_name' => $item->aspects_printing_name($this->lang),
                    'paper_type_name' => $item->paper_type_name($this->lang),
                    'page_layout_name' => $item->page_layout_name($this->lang),
                    'packaging_name' => $item->packaging_name($this->lang),
                    'created_at' => date('d/m/Y', strtotime($item->created_at)),
                    'branch_name' => isset($item->branch) ? $item->branch->name_api($this->lang) : '',
                    'file_path' => isset($item->file) && $item->file->file_path ? asset('public' . $item->file->file_path) : '',
                    'file_name' => isset($item->file) && $item->file->file_name ? $item->file->file_name : '',
                    'num_pages' => isset($item->file) && $item->file->num_pages ? $item->file->num_pages : '',
                ];
            }
        }
        $data = [
            'sub_total' => round($sub_total, 2),
            'tax_price' => round($tax_price, 2),
            'total' => round($total, 2),
            'color_cover_price' => round($color_cover_price, 2),
            'cart_list' => $dataList,
        ];
        return $this->dataResponse('cart get success', $data, 200);
    }

    public function addCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'num_pages' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);
        $black_w_price = 0;
        $black_w_print_price = PrintPrice::where('printing_type_id', 1)
            ->where('paper_size', 4)
            ->where('from_paper', '<=', $request->num_pages)
            ->where('to_paper', '>=', $request->num_pages)->first();

        $unlimeted_b_w_print_price = PrintPrice::where('printing_type_id', 1)
            ->where('is_unlimeted', 1)
            ->where('from_paper', '<=', $request->num_pages)->first();

        if ($black_w_print_price)
            $black_w_price = $request->num_pages / $black_w_print_price->num_paper;
        elseif ($unlimeted_b_w_print_price)
            $black_w_price = $request->num_pages / $unlimeted_b_w_print_price->num_paper;


        $cart = new Cart();
        $cart->user_id = $this->user->id;
        $cart->packaging_id = 1;
        $cart->page_layout = 1;
        $cart->aspects_printing = 1;
        $cart->paper_type = 1;
        $cart->first_page_color = 0;
        $cart->paper_size = 4;
        $cart->printing_color = 0;
        $cart->price = (float)$black_w_price;
        $cart->qty = 1;
        $cart->save();
        if ($request->hasFile('file'))
            upload_file($request->file, $request->num_pages, 'media', 'App\Models\Cart', $cart->id);

        return $this->successResponse('cart add success', 200);
    }

    public function updateCart(Request $request)
    {

        $cart = Cart::find($request->cart_id);
        if (!$cart)
            return $this->errorResponse('cart not found', 401);

        $black_w_price = 0;
        $packaging_price = 0;
        $black_w_print_price = PrintPrice::where('printing_type_id', 1)
            ->where('paper_size', $request->paper_size)
            ->where('from_paper', '<=', $cart->file->num_pages)
            ->where('to_paper', '>=', $cart->file->num_pages)->first();

        $unlimeted_b_w_print_price = PrintPrice::where('printing_type_id', 1)
            ->where('is_unlimeted', 1)
            ->where('from_paper', '<=', $cart->file->num_pages)->first();

        if ($black_w_print_price)
            $black_w_price = $cart->file->num_pages / $black_w_print_price->num_paper;
        elseif ($unlimeted_b_w_print_price)
            $black_w_price = $cart->file->num_pages / $unlimeted_b_w_print_price->num_paper;

        $packaging_price = 0;
        if ($request->packaging_id && $request->packaging_id > 1) {
            $packaging_print_price = PrintPrice::where('printing_type_id', 2)
                ->where('paper_size', $request->paper_size)
                ->where('packaging_type', $request->packaging_id)
                ->where('from_paper', '<=', $cart->file->num_pages)
                ->where('to_paper', '>=', $cart->file->num_pages)->first();

            $unlimeted_packaging_print_price = PrintPrice::where('printing_type_id', 2)
                ->where('is_unlimeted', 1)
                ->where('packaging_type', $request->packaging_id)
                ->where('from_paper', '<=', $cart->file->num_pages)->first();

            if ($packaging_print_price)
                $packaging_price = $packaging_print_price->num_paper;
            elseif ($unlimeted_packaging_print_price)
                $packaging_price = $unlimeted_packaging_print_price->num_paper;

        }

        $total = $black_w_price;

        $setting = Settings::first();

        $cart->price = (float)$total;
        $cart->packaging_price = (float)$packaging_price;
        if ($request->packaging_id)
            $cart->packaging_id = $request->packaging_id;
        if ($request->aspects_printing)
            $cart->aspects_printing = $request->aspects_printing;
        if ($request->paper_type)
            $cart->paper_type = $request->paper_type;
        if ($request->packaging_type)
            $cart->packaging_type = $request->packaging_type;
        if ($request->first_page_color == 1)
            $cart->first_page_color = 1;
        if ($request->first_page_color == 0)
            $cart->first_page_color = 0;
        if ($request->printing_color == 1)
            $cart->printing_color = 1;
        if ($request->printing_color == 0)
            $cart->printing_color = 0;
        if ($request->paper_size)
            $cart->paper_size = $request->paper_size;
        if ($request->qty)
            $cart->qty = $request->qty;
        if ($request->page_layout)
            $cart->page_layout = $request->page_layout;
        if ($request->branch_id)
            $cart->branch_id = $request->branch_id;
        if ($request->first_page_color == 1)
            $cart->color_cover_price = $setting->color_cover_price;
        else
            $cart->color_cover_price = 0;


        $cart->save();

        return $this->successResponse('cart updated success', 200);
    }

    public function deleteCart(Request $request)
    {

        $cart = Cart::find($request->cart_id);
        if (!$cart)
            return $this->errorResponse('cart not found', 401);

        $file = isset($cart->files) ? $cart->files()->where('mediable_type', 'App\Models\Cart')->where('mediable_id', $cart->id)->first() : null;
        if ($file) {
            $file = $file->file_path;
            $filename = public_path() . '/' . $file;
            File::delete($filename);

        }
        $cart->delete();
        return $this->successResponse('cart deleted success', 200);
    }

    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carts' => 'required',
            'branch_id' => 'required',
        ]);

        if ($validator->fails())
            return $this->errorResponse($validator->errors()->first(), 401);
        $setting = Settings::first();
        $last_item_id = 0;

        $last_item = Order::orderBy('id', 'DESC')->first();
        if ($last_item)
            $last_item_id = $last_item->id;

        $order = new Order();
        $order->user_id = $this->user->id;
        $order->status_id = 1;
        $order->is_payment = 1;
        $order->code = str_pad($last_item_id + 1, 5, "0", STR_PAD_LEFT);
        $order->tax = $setting->tax;
        $order->branch_id = $request->branch_id;
        $order->save();

        $carts = $request->carts;
        if (!is_array($carts))
            $carts = json_decode($carts);

        foreach ($carts as $cart_id) {
            $cart = Cart::find($cart_id);
            if ($cart):
                $order_details = new OrderDetail();
                $order_details->order_id = $order->id;
                $order_details->paper_size = $cart->paper_size;
                $order_details->qty = $cart->qty;
                $order_details->price = $cart->price;
                $order_details->first_page_color = $cart->first_page_color;
                $order_details->paper_type = $cart->paper_type;
                $order_details->packaging_price = $cart->packaging_price;
                $order_details->aspects_printing = $cart->aspects_printing;
                $order_details->packaging_id = $cart->packaging_id;
                $order_details->printing_color = $cart->printing_color;
                $order_details->packaging_type = $cart->packaging_type;
                $order_details->page_layout = $cart->page_layout;
                $order_details->color_cover_price = $cart->color_cover_price;
                $order_details->save();


                $cart->file->update([
                    'mediable_type' => 'App\Models\OrderDetail',
                    'mediable_id' => $order_details->id
                ]);

                $cart->delete();
            endif;
        }

        $notify = new Notifacations();
        $notify->is_admin = 1;
        $notify->user_id = 0;
        $notify->notify = $order->code . 'طلب جديد # ';
        $notify->save();
        return $this->successResponse('order created success', 200);
    }

    public function userNotifacations()
    {
        $notifacations = Notifacations::where('user_id', $this->user->id)->get();
        $data = $notifacations->map(function ($notifiy) {
            return [
                'id' => $notifiy->id,
                'message' => $notifiy->notify,
                'created_at' => $notifiy->created_at,
            ];
        });

        return $this->dataResponse('Notification get success', $data, 200);
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', $this->user->id)->get();
        $data = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'status' => $order->status_name_api($this->lang),
                'branch' => $order->branch->name_api($this->lang) ?? '',
                'code' => '#' . $order->code,
                'created_at' => date('d/m/Y', strtotime($order->created_at)),
                'order_sub_total' => (float)$order->order_sub_total,
                'total_tax' => (float)$order->total_tax,
                'sum_color_cover_price' => (float)$order->sum_color_cover_price,
                'total' => (float)$order->total,
            ];
        });

        return $this->dataResponse('orders get success', $data, 200);
    }

    public function ordersDetails(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order)
            return $this->errorResponse('order not found', 401);

        $detailsList = isset($order->details) && count($order->details) > 0 ? $order->details->map(function ($item) {
            return [
                'id' => $item->id,
                'paper_size' => $item->paper_size,
                'sub_total' => (float)$item->sub_total,
                'total' => (float)$item->total,
                'color_cover_price' => (float)$item->color_cover_price,
                'qty' => $item->qty,
                'price' => $item->price,
                'printing_color' => (integer)$item->printing_color,
                'first_page_color' => $item->first_page_color,
                'packaging_id' => $item->packaging_id,
                'packaging_type' => $item->packaging_type,
                'aspects_printing_name' => $item->aspects_printing_name($this->lang),
                'paper_type_name' => $item->paper_type_name($this->lang),
                'page_layout_name' => $item->page_layout_name($this->lang),
                'packaging_name' => $item->packaging_name($this->lang),
                'file_path' => isset($item->file) && $item->file->file_path ? asset('public' . $item->file->file_path) : '',
                'file_name' => isset($item->file) && $item->file->file_name ? $item->file->file_name : '',
                'num_pages' => isset($item->file) && $item->file->num_pages ? $item->file->num_pages : '',
            ];
        }) : [];
        $data = [
            'id' => $order->id,
            'status' => $order->status_name_api($this->lang),
            'branch' => $order->branch->name_api($this->lang) ?? '',
            'code' => '#' . $order->code,
            'created_at' => date('d/m/Y', strtotime($order->created_at)),
            'order_sub_total' => (float)$order->order_sub_total,
            'total_tax' => (float)$order->total_tax,
            'sum_color_cover_price' => (float)$order->sum_color_cover_price,
            'total' => (float)$order->total,
            'details_list' => $detailsList
        ];


        return $this->dataResponse('orders get success', $data, 200);
    }

    public function payOrder(Request $request)
    {

        $terminalId = "alshyuh";
        $password = "alshyuh@URWAY_685";
        $key = "e32cfed5249ba242a8f2aeadb1979faa3f7e8f1f74a7e65f029a60a673cb628f";

        $last_item_id = 0;
        $last_item = Order::orderBy('id', 'DESC')->first();
        if ($last_item)
            $last_item_id = $last_item->id;
        $code = str_pad($last_item_id + 1, 5, "0", STR_PAD_LEFT);

        $cart = Cart::where('user_id', $this->user->id)->get();
        $total = 0;
        if (count($cart) > 0) {
            foreach ($cart as $item)
                $total += $item->total;
        } else {
            return $this->errorResponse('Cart is empty', 401);
        }
        $track_id = $code;
        $trans_id = $code;
        $amount = $total;

        $txn_details = $track_id . '|' . $terminalId . '|' . $password . '|' . $key . '|' . $amount . '|SAR';
        $hash = hash('sha256', $txn_details);


        $fields = array(
            'trackid' => $track_id,
            'terminalId' => $terminalId,
            'customerEmail' => $this->user->email ? $this->user->email : rand(0000000,9999999).'@gmail.com',
            'action' => "1",  // action is always 1
            'merchantIp' => '18.169.125.58', //"9c4d8b042bce6494b0975e8791313f037342fb05269380e347c46399e9a0c72e",
            'password' => $password,
            'currency' => 'SAR',
            'country' => "SA",
            'amount' => $amount,
            "udf1" => "Test1",
            "udf2" => url('/order-success'), //Response page URL
            "udf3" => "",
            "udf4" => "",
            "udf5" => "Test5",
            'requestHash' => $hash
        );
        $data = json_encode($fields);

        $ch = curl_init('https://payments.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest'); // Will be provided by URWAY
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        if (!empty($result["targetUrl"]) && !empty($result["payid"])) {
            $url = $result["targetUrl"] . '?paymentid=' . $result["payid"];

            $data = [
                'url' => $url
            ];
            return $this->dataResponse('Payment success', $data, 200);

        } else {
            return $this->errorResponse('Payment error', 401);
        }
    }
    public function deleteAccount()
    {
        $user = User::find($this->user->id);
        if ($user)
            $user->delete();
        return $this->successResponse('deleted success', 200);
    }
}
