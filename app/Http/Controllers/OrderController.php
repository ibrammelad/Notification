<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\apiResponse;
use App\Traits\OrderOperations;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use OrderOperations , apiResponse;
    public function showOrders()
    {
        $orders = Order::total();
        return response()->json($orders);
    }

    public function createOrder(Request $request)
    {
        $validate = [
            'longFrom' => "required|numeric",
            'latFrom' => "required|numeric",
            'longTo' => "required|numeric",
            'latTo' => "required|numeric",
            'cost' => "required|integer",
        ];
        $input = $this->validate($request,$validate);
        $input['status'] = 0;
        $input['user_id'] = 1;
        $order = Order::create($input);
        $this->PushNotificationForAllCaptainAvailable();
        return response()->json($order);
    }

    public function distance1(){
        return $this->distance(22.244, 20.222 , 22.222 , 20.222);
    }

    public function orderCancel(Order $order)
    {
        $order->update(['status' => 2]);
        $this->pushForNotifyOrderCancel($order);
        return response()->json("order is canceled" , 200);

    }



}
