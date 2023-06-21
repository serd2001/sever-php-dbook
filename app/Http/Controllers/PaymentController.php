<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Request $request)
    {
        $payment = payment::get();

        // Append image URL to each category
        foreach ($payment as $category) {
            $category->image_url = asset('storage/image/' . $category->image);
        }

        return response()->json($payment);
    }
    public function payment(PaymentRequest $request)
    {

        $payment = new Payment();
        $payment->name = $request->input('name');
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('storage/image'), $filename);
            $payment->image = $filename;

            // Generate the image URL
            $imageUrl = asset('storage/image/' . $filename);
        }

        $payment->save();

        return response()->json([
            'message' => 'Data added successfully',
            // 'image_url' => isset($imageUrl) ? $imageUrl : null
        ]);
    }
}
