<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintingType;
use App\Models\PrintPrice;
use Illuminate\Http\Request;

class PrintPriceController extends Controller
{
    public function index(){
        $print_price = PrintPrice::orderBy('id','DESC')->get();
        return view('admin.print_price.index',compact('print_price'));
    }

    public function create(){
        $printing_type = PrintingType::get();
        return view('admin.print_price.add',compact('printing_type'));
    }

    public function store(Request $request){
        $print_price = new PrintPrice();
        $print_price->num_paper = $request->num_paper > 0 ? $request->num_paper : 0;
        $print_price->from_paper = $request->from_paper > 0 ? $request->from_paper : 0;
        $print_price->to_paper = $request->to_paper > 0 ? $request->to_paper : 0;
        $print_price->paper_size = $request->paper_size > 0 ? $request->paper_size : 0;
        $print_price->packaging_type = $request->packaging_type > 0 ? $request->packaging_type : 0;
        $print_price->printing_type_id = $request->printing_type_id;
        $print_price->is_unlimeted = $request->is_unlimeted == 'on' ? 1 : 0;
        $print_price->save();
        if ($request->save == 1)
            return redirect()->route('admin.print_price.edit', $print_price->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.print_price.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $print_price = PrintPrice::find($id);
        $printing_type = PrintingType::get();
        return view('admin.print_price.edit',compact('print_price','printing_type'));
    }

    public function update(Request $request,$id){
        $print_price = PrintPrice::find($id);
        $print_price->num_paper = $request->num_paper;
        $print_price->from_paper = $request->from_paper;
        $print_price->to_paper = $request->to_paper;
        $print_price->paper_size = $request->paper_size;
        $print_price->printing_type_id = $request->printing_type_id;
        $print_price->packaging_type = $request->packaging_type;
        $print_price->is_unlimeted = $request->is_unlimeted == 'on' ? 1 : 0;
        $print_price->save();
        if ($request->save == 1)
            return redirect()->route('admin.print_price.edit', $print_price->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.print_price.index')->with('success', __('msg.created_success'));
    }

    public function delete(Request $request){
        $print_price = PrintPrice::find($request->id);
        if ($print_price){
            $print_price->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}
