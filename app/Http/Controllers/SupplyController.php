<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplyRequest;
use App\Models\Supply;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function create(SupplyRequest $request)
    {
        $supply = new Supply();
        $supply->name = $request->input('name');
        $supply->phone = $request->input('phone');
        $supply->email = $request->input('email');
        $supply->desc = $request->input('desc');
        $supply->save();
        return response()->json([
            'massage' => 'add successfuly'
        ]);
    }
    public function update(SupplyRequest  $request, $id)
    {
        if (Supply::where('id', $id)->exists()) {

            $supply = Supply::where('id', $id)->first();
            $supply->name = $request->input('name');
            $supply->phone = $request->input('phone');
            $supply->email = $request->input('email');
            $supply->desc = $request->input('ddesc');
            $supply->save();
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
        if (Supply::where('id', $id)->exists()) {
            $supply = Supply::find($id);
            $supply->delete();
            return response()->json([
                'ລືບຂໍ້ມູນແລ້ວ'
            ]);
        } else {
            return response()->json([
                'ບໍ່ມີຂໍ້ມູນ'
            ]);
        }
    }
}
