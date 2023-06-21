<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Book;
use App\Models\Sale;
use App\Models\saleDetail;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Database\Transaction;
use PhpParser\Node\Stmt\TryCatch;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            $total_quantity = 0;
            $total_price = 0;
            $discount = 0;
            $total = 0;

            //insertdata to sale
            $sale = new Sale();
            $sale->user_id = $request->user()->id;
            $sale->total_quantity = 0;
            $sale->total_price = 0;
            $sale->discount = $request->input('discount');
            $sale->total = 0;
            $sale->date = Carbon::now();
            $sale->save();
            //insert data to saleDetail
            foreach ($request->detail as $item) {
                // dd($item);
                $book = Book::find($item['book_id']);
                if (!$book) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Book not found"
                    ], 404);
                }
                $saledetail = new saleDetail();
                $saledetail->sale_id = $sale->id;
                $saledetail->book_id = $item['book_id'];
                $saledetail->qty = $item['qty'];
                $saledetail->sale_price = $item['sale_price'];
                $saledetail->discount = $item['discount'];
                // $saledetail->total = $book->sale_price - $item['discount'];
                $saledetail->total = $item['qty'] * $item['sale_price'] - $item['discount'];
                $saledetail->date = Carbon::now();
                $saledetail->save();

                $total_quantity += $item['qty'];
                $total_price += $item['sale_price'];
                $discount += $item['discount'];
            }
            //
            if ($book->quantity <  $item['qty']) {

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "ຈຳນວນສິນຄ້າ $book->name ເຫຼືອພຽງ $book->quantity ລາຍການ"
                ], 400);
            }
            //loud chamnrn in sale
            $book->quantity -= $item['qty'];
            $book->save();

            //add new
            $sale->total_quantity = $total_quantity;
            $sale->discount = $discount;
            $sale->total_price = $total_price;
            $sale->total = $total_price - $discount;
            $sale->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "ຂາຍສຳເລັດ"
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
