<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboard(Request $request){
        $branches = Branch::orderBy('id','DESC')->get();

        $branch_id = $request->get('branch_id');
        if ($branch_id && $branch_id > 0){
            $num_branches = 1;
//            admin()->parent_id
                $order_data = Order::where('branch_id',$branch_id)->orderBy('id', 'DESC');
                $orders = $order_data->get();
                $today_orders = $order_data->whereDate('created_at',date('Y-m-d'))->get();

            $total_sales = 0;
            $total_tax = 0;
            $today_total_sales = 0;
            if (count($orders) > 0){
                foreach ($orders as $order) {
                    $total_sales+= $order->total;
                    $total_tax+= $order->total_tax;
                }
            }
            if (count($today_orders) > 0){
                foreach ($today_orders as $item)
                    $today_total_sales+=$item->total;
            }
        }else{
            $num_branches = Branch::count();

            if (admin()->parent_id > 0 && admin()->branch_id > 0) {
                $order_data = Order::where('branch_id', admin()->branch_id)->orderBy('id', 'DESC');
                $orders = $order_data->get();
                $today_orders = $order_data->whereDate('created_at',date('Y-m-d'))->get();
            }
            else {
                $order_data = Order::orderBy('id', 'DESC');
                $orders = $order_data->get();
                $today_orders = $order_data->whereDate('created_at',date('Y-m-d'))->get();
            }
            $total_sales = 0;
            $total_tax = 0;
            $today_total_sales = 0;
            if (count($orders) > 0){
                foreach ($orders as $order) {
                    $total_sales+= $order->total;
                    $total_tax+= $order->total_tax;
                }
            }
            if (count($today_orders) > 0){
                foreach ($today_orders as $item)
                    $today_total_sales+=$item->total;
            }
        }

        $num_users = User::count();
        return view('admin.dashboard',[
            'num_branches' => $num_branches,
            'num_users' => $num_users,
            'branches' => $branches,
            'num_orders' => count($orders),
            'total_sales' => (float)$total_sales,
            'today_total_sales' => (float)$today_total_sales,
            'total_tax' => (float)$total_tax,
        ]);
    }
}
