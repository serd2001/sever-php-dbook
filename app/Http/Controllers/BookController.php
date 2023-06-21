<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create(BookRequest $request)
    {
    }
    public function index(Request $request)
    {
        $books = Book::all()->map(function ($book) {
            $book->image_url = asset('storage/image/' . $book->image);
            return $book;
        });
        return response()->json($books);

        // $book = Book::query()->with(['category']);
        // if ($request->category) {
        //     $book->where('category_id', $request->category);
        // }


        //  $product = Products::all();  
        // return response()->json($book);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check name if has name (+qty only)
        try {
            $existbook = Book::where('name', $request->input('name'))->first(); //check if has name same input (qty+qty input)
            if (isset($existbook)) {
                $existbook->quantity += $request->input('quantity');
                $existbook->save();
                return response()->json([
                    'success' => 'ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ'
                ]);
                //don't have create new
            } else {
                $book = new Book();
                $book->ISBN = $request->input('ISBN');
                $book->name = $request->input('name');
                $book->author = $request->input('author');
                $book->category_id = $request->category_id;
                $book->quantity = $request->input('quantity');
                $book->order_price = $request->input('order_price');
                $book->sale_price = $request->input('sale_price');

                if ($request->file('image')) {
                    $file = $request->file('image');
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('storage/image'), $filename);
                    $book->image = $filename;
                    $imageUrl = asset('storage/image/' . $filename);
                }


                $book->save();
                return response()->json('ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ');
            }
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        if (!empty($book)) {
            return response()->json($book);
        } else {
            return response()->json([
                "message" => "ບໍ່ມີຂໍ້ມູນໄອດີນີ້"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Book::where('id', $id)->exists()) {
            $book = Book::find($id);
            $book->ISBN = $request->input('ISBN');
            $book->name = $request->input('name');
            $book->author = $request->input('author');
            $book->category_id = $request->category_id;
            $book->order_price = $request->input('order_price');
            $book->sale_price = $request->input('sale_price');
            $book->quantity = $request->input('quantity');
            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('storage/image'), $filename);
                $book->image = $filename;
            }


            $book->save();
            return response()->json([
                "message" => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ"
            ], 404);
        } else {
            return response()->json([
                "message" => "ບໍ່ມີຂໍ້ມູນ"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (Book::where('id', $id)->exists()) {
            $book = Book::find($id);
            $book->delete();

            return response()->json([
                "message" => "delete successfully."
            ], 202);
        } else {
            return response()->json([
                "message" => " not found."
            ], 404);
        }
    }
    public function search(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        $name = $request->name;

        if ($name !== '') {
            return response()->json(Book::where('name', 'like', '%' . $name . '%')->with(['category'])->paginate($pageSize));
        } else {
            return response()->json([
                'ບໍ່ມີສີນຄ້າ'
            ]);
        }
    }
}
