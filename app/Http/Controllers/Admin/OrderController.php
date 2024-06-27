<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifacations;
use App\Models\Order;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    public function index(){
        if (admin()->parent_id > 0 && admin()->branch_id > 0)
            $orders = Order::where('branch_id',admin()->branch_id)->orderBy('id','DESC')->get();
        else
            $orders = Order::orderBy('id','DESC')->get();
        return view('admin.orders.index',compact('orders'));
    }

    public function show($id){
        $order = Order::find($id);
        $setting = Settings::first();
        return view('admin.orders.show',compact('order','setting'));
    }
    public function delete(Request $request){
        $order = Order::find($request->id);
        if ($order){
            if (isset($order->details) && count($order->details) > 0){
                foreach ($order->details as $item){
                    $file = $item->file->image;
                    $filename = public_path() . '/' . $file;
                    File::delete($filename);
                    $item->delete();
                }
            }
            $order->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function sendOrderSms(Request $request){
        $order = Order::find($request->id);
        if ($order){
            $setting = Settings::first();
            if (isset($order->user) && $order->user->phone)
                send_sms($setting->order_ready_message,$order->user->phone);
            $notify = new Notifacations();
            $notify->is_admin = 0;
            $notify->user_id = $order->user->id;
            $notify->notify = $setting->order_ready_message;
            $notify->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request){
        $order = Order::find($request->order_id);
        if ($order){
            $order->status_id = $request->status_id;
            $order->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}
