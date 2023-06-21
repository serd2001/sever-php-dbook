<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showall(Request $request)
    {
        $categories = Category::get();

        // Append image URL to each category
        foreach ($categories as $category) {
            $category->image_url = asset('storage/image/' . $category->image);
        }

        return response()->json($categories);
    }
    public function show($id)
    {
        $category = Category::find($id);
        if (!empty($category)) {
            return response()->json($category);
        } else {
            return response()->json([
                "message" => "ບໍ່ມີຂໍ້ມູນໄອດີນີ້"
            ], 404);
        }
    }
    public function create(CategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->input('name');

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('storage/image'), $filename);
            $category->image = $filename;

            // Generate the image URL
            $imageUrl = asset('storage/image/' . $filename);
        }

        $category->save();

        return response()->json([
            'message' => 'Data added successfully',
            // 'image_url' => isset($imageUrl) ? $imageUrl : null
        ]);
    }
    public function update(CategoryRequest $request, $id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::where('id', $id)->first();
            $category->name = $request->input('name');
            $category->save();
            return response()->json([
                'edit successfuly'
            ]);
        } else {
            return response()->json([
                'no data '
            ]);
        }
    }
    public function delete($id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->delete();
            return response()->json([
                'ລືບຂໍ້ມູນແລ້ວ'
            ]);
        } else {
            return response()->json([
                'ບໍ່ມີຂໍ້ມູນ'
            ]);
        }
    }
    // public function search(Request $request){
    //     if ($name !== '') {
    //         return response()->json(books::where('name', 'like', '%' . $name . '%')->paginate($pageSize));
    //     } else {
    //         return response()->json([
    //             'ບໍ່ມີສີນຄ້າ'
    //         ]);
    // }
}
