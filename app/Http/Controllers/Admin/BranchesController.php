<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BranchesController extends Controller
{
    public function index(){
        $branches = Branch::orderBy('id','DESC')->get();
        return view('admin.branches.index',compact('branches'));
    }

    public function create(){
        return view('admin.branches.add');
    }

    public function store(Request $request){
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image_name = $image->hashName();
            $image->move(public_path('/uploads/branches/'),$image_name);

            $filePath = "/uploads/branches/" . $image_name;
        }
        $branch = new Branch();
        $branch->name_ar = $request->name_ar;
        $branch->name_en = $request->name_en;
        $branch->address_en = $request->address_en;
        $branch->address_ar = $request->address_ar;
        $branch->st_time = $request->st_time;
        $branch->end_time = $request->end_time;
        $branch->active = $request->active == 'on' ? 1 : 0;
        if (isset($filePath) && $filePath)
            $branch->image = $filePath;
        $branch->save();
        if ($request->save == 1)
            return redirect()->route('admin.branches.edit', $branch->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.branches.index')->with('success', __('msg.created_success'));
    }

    public function edit($id){
        $branch = Branch::find($id);
        return view('admin.branches.edit',compact('branch'));
    }

    public function update(Request $request,$id){
        $branch = Branch::find($id);

        if ($branch) {
            if ($request->hasFile('image')) {
                $file = $branch->image;
                $filename = public_path() . '' . $file;
                File::delete($filename);

                $image = $request->file('image');
                $image_name = $image->hashName();
                $image->move(public_path('/uploads/branches/'), $image_name);

                $filePath = "/uploads/branches/" . $image_name;
            }
            $branch->name_ar = $request->name_ar;
            $branch->name_en = $request->name_en;
            $branch->address_en = $request->address_en;
            $branch->address_ar = $request->address_ar;
            $branch->st_time = $request->st_time;
            $branch->end_time = $request->end_time;
            $branch->active = $request->active == 'on' ? 1 : 0;
            if (isset($filePath) && $filePath)
                $branch->image = $filePath;
            $branch->save();
        }
        if ($request->save == 1)
            return redirect()->route('admin.branches.edit', $branch->id)->with('success', __('msg.created_success'));
        else
            return redirect()->route('admin.branches.index')->with('success', __('msg.created_success'));
    }


    public function delete(Request $request){
        $branch = Branch::find($request->id);
        if ($branch){
            $file = $branch->image;
            $filename = public_path() . '/' . $file;
            File::delete($filename);
            $branch->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }

    public function updateStatus(Request $request){
        $branch = Branch::find($request->id);
        if ($branch){
            $branch->active = $request->active == 'true' ? 1 : 0;
            $branch->save();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}
