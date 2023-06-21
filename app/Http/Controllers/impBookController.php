<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Models\Book;
use App\Models\Import;
use App\Models\importDetail;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class impBookController extends Controller
{
    public function impbook(ImportRequest $request)
    {
        DB::beginTransaction();
        try {
            $total_qty = 0;
            $total_price = 0;



            //insertdata to imp

            $import = new Import();
            $import->order_id = $request->input('order_id');
            $order_id = $request->input('order_id');
            $order = Order::find($order_id);
            if (!$order) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Order not found"
                ], 404);
            }
            $import->total_qty = 0;
            $import->total_price = 0;
            $import->date = Carbon::now();
            $import->save();
            //insert data to saleDetail
            foreach ($request->detail as $item) {
                // dd($item);
                $impDetail = new importDetail();
                $impDetail->order_id = $order->id;
                $impDetail->book_id = $item['book_id'];
                $impDetail->detail_qty = $item['detail_qty'];
                $impDetail->detail_price = $item['detail_price'];
                $impDetail->discount = $item['discount'];
                $impDetail->total_price = $item['qty'] * $item['detail_price'] - $item['discount'];
                $impDetail->save();

                $total_qty += $item['quantity'];
                $total_price += $item['buy_price'];
                // $discount += $item['discount'];

                $book = Book::find($item['book_id']);
                $book->quantity += $item['detail_qty'];
                $book->save();
            }
            //

            //loud chamnrn in sale
            //add new
            $import->total_qty = $total_qty;
            $import->total_price = $total_price;
            $import->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "ນໍາເຂົ້າສໍາເລັດ"
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            //throw $ex;
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }
}




















    //
  //  public function impbook(Order  $order)
    //{
        // DB::beginTransaction();
        // try {
        //     //  indata to import
        //     $import = new Import();
        //     $import->order_id = $order->id;
        //     $import->total_qty = 0;
        //     $import->total_price = 0;
        //     $import->date = Carbon::now();
        //     $import->save();
        //              //insert data to saleDetail
        //     foreach ($request->detail as $item) {
        //         // dd($item);
        //         $order = Order::find($item['order_id']);
        //         if (!$order) {
        //             DB::rollBack();
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => "order not found"
        //             ], 404);
        //         }
        //         $impDetail = new importDetail();
        //         $impDetail->order_id = $order->id;
        //         $impDetail->book_id = $item['book_id'];
        //         $impDetail->qty = $item['quantity'];
        //         $impDetail->buy_price = $item['buy_price'];
        //         $impDetail->discount = $item['discount'];
        //         $impDetail->total_discount = $item['qty'] * $item['buy_price'] - $item['discount'];
        //         $impDetail->save();

        //         $total_qty += $item['quantity'];
        //         $total_price += $item['buy_price'];
        //        // $discount += $item['discount'];

        //        $book = Book::find($book_id);
        //        $book->quantity += $quantity;
        //        $book->save();
        //     }
        //     //
        //     if ($book->quantity <  $item['qty']) {

        //         DB::rollBack();
        //         return response()->json([
        //             'success' => false,
        //             'message' => "ຈຳນວນສິນຄ້າ $book->name ເຫຼືອພຽງ $book->quantity ລາຍການ"
        //         ], 400);
        //     }
        //     //loud chamnrn in sale
        //     $book->quantity -= $item['qty'];
        //     $book->save();

        //     //add new
        //     $sale->total_quantity = $total_quantity;
        //     $sale->discount = $discount;
        //     $sale->total_price = $total_price;
        //     $sale->total = $total_price - $discount;
        //     $sale->save();

        //     DB::commit();
        //     return response()->json([
        //         'success' => true,
        //         'message' => "ຂາຍສຳເລັດ"
        //     ]);
        // } catch (Exception $ex) {
        //     DB::rollBack();
        //     //  throw $ex;
        //     return response()->json([
        //         'success' => false,
        //         'message' => $ex->getMessage()
        //     ]);
        // }
        // DB::beginTransaction();
        // try {
        //     $orderDetails = $order->details;

        //     $totalQuantity = 0;
        //     $totalPrice = 0;
        //     $totalDiscount = 0;
        //     if (!is_null($orderDetails)) {
        //         foreach ($orderDetails as $orderDetail) {
        //             // Rest of the code within the loop
        //             // ..
        //             $bookId = $orderDetail->book_id;
        //             $quantity = $orderDetail->quantity;
        //             $buyPrice = $orderDetail->buy_price;
        //             $discount = $orderDetail->discount;
        //             dd($orderDetail);
        //             // Perform your import operations here
        //             // For example, update the inventory, create new records, etc.

        //             // Example: Update the book's quantity
        //             $book = Book::find($bookId);
        //             $book->quantity += $quantity;
        //             $book->save();

        //             // Update the total quantity, total price, and total discount
        //             $totalQuantity += $quantity;
        //             $totalPrice += $buyPrice;
        //             $totalDiscount += $discount;
        //         }
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => "false"
        //         ]);
        //     }

        //     $order->total_quantity = $totalQuantity;
        //     $order->total_price = $totalPrice;
        //     $order->date = Carbon::now();
        //     $order->status = 'imported';
        //     // Update other fields as needed


        //     DB::commit();
        //     return response()->json([
        //         'success' => true,
        //         'message' => "ນໍໍາເຂົ້າສໍາເລັດ"
        //     ]);
        // } catch (Exception $ex) {
        //     DB::rollBack();
        //     //  throw $ex;
        //     return response()->json([
        //         'success' => false,
        //         'message' => $ex->getMessage()
        //     ]);
        // }
