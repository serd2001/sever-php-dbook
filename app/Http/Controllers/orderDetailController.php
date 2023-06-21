<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequst;
use App\Models\Book;
use App\Models\Order;
use App\Models\orderDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class orderDetailController extends Controller
{
    public function order(OrderRequst $request)
    {
        DB::beginTransaction();

        try {
            $total_quantity = 0;
            $total_price = 0;
            $total_discount = 0;
            //insert data to order
            $order = new Order();
            // $order->user_id = Auth::user()->id;
            $order->user_id = $request->user_id;
            $order->supply_id = $request->supply_id;
            $order->status = $request->input('status');
            $order->total_quantity = 0;
            $order->total_price = 0;
            //   $order->total_discount = 0;
            $order->date = Carbon::now();
            $order->save();
            //loop detail
            foreach ($request->detail as $item) {
                $orderDetail = new orderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->book_id = $item['book_id'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->buy_price = $item['buy_price'];
                $orderDetail->discount = $item['discount'];
                $orderDetail->total = $orderDetail->buy_price - $item['discount'];
                $orderDetail->save();

                $total_quantity += $item['quantity'];
                $total_price += $item['quantity'] * $item['buy_price'];
                $total_discount += $item['discount'];
            }
            $order->total_quantity = $total_quantity;
            //  $order->total_discount = $total_discount;

            $order->total_price = $total_price;
            $order->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'success',
                'data' => $order,

            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
