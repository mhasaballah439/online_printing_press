<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintingType;
use Illuminate\Http\Request;

class PrintingTypeController extends Controller
{
    public function index(){
        $printing_type = PrintingType::orderBy('id','DESC')->get();
        return view('admin.printing_type.index',compact('printing_type'));
    }

    public function create(){
        return view('admin.printing_type.add');
    }

    public function store(Request $request){
        $printing_type = new PrintingType();
        $printing_type->name_ar = $request->name_ar;
        $printing_type->name_en = $request->name_en;
        $printing_type->save();
        if ($request->save == 1)
            return redirect()->route('admin.printing_type.edit', $printing_type->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.printing_type.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $printing_type = PrintingType::find($id);
        return view('admin.printing_type.edit',compact('printing_type'));
    }

    public function update(Request $request,$id){
        $printing_type = PrintingType::find($id);
        $printing_type->name_ar = $request->name_ar;
        $printing_type->name_en = $request->name_en;
        $printing_type->save();
        if ($request->save == 1)
            return redirect()->route('admin.printing_type.edit', $printing_type->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.printing_type.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request){
        $printing_type = PrintingType::find($request->id);
        if ($printing_type){
            $printing_type->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

}
