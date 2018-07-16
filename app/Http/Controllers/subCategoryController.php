<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\subCategory;
use App\category;
use Illuminate\Support\Facades\DB;
class subCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCategories = DB::table('sub_categories')
        ->select(
            "categories.cate_name as cate_name",
            "sub_categories.id as id",
            "sub_categories.sub_cate_name as sub_cate_name",
            "sub_categories.status as status",
            "sub_categories.created_at as created_at",
            "sub_categories.updated_at as updated_at"
        )
        ->join('categories','categories.id','=','sub_categories.cate_id')
        ->OrderBy('created_at','asc')
        ->get();
       return view("subCategory.subCategories")->with('subCategories',$subCategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data               = array();
        $data['categories'] = category::OrderBy('created_at','asc')->get();

        return view('subCategory.subCategoryCreate')->with('data',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
            'category_id'       => 'required',
            'sub_category_name' => 'required',
        ]);
     
        $subCategory                = new subCategory(); 
        $subCategory->cate_id       = $request->category_id;
        $subCategory->sub_cate_name = $request->sub_category_name;
        $subCategory->status        = isset($request->status) ? 1 : 0;
        $subCategory->remarks       = $request->remarks;
        $subCategory->updated_at    = null;
        $subCategory->save();
        return redirect('subCategory')->with('status', 'You have successfully Created new Sub Category!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data        = array();

        $data['categories']  = category::OrderBy('created_at','asc')->get();
        $data['subCategory'] = subCategory::findOrFail($id);

        return view('subCategory.subCategoryCreate')->with('data',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
       $this->validate(request(),[
            'category_id'       => 'required',
            'sub_category_name' => 'required',
        ]);
     
        $subCategory                = subCategory::find($id);
        $subCategory->cate_id       = $request->category_id;
        $subCategory->sub_cate_name = $request->sub_category_name;
        $subCategory->status        = isset($request->status) ? 1 : 0;
        $subCategory->remarks       = $request->remarks;
        $subCategory->save();
        return redirect('subCategory')->with('status', 'You have successfully Created new Sub Category!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        subCategory::where('id',$id)->delete();
        return redirect('/subCategory')->with('status_delete','Category Deleted !');
    }
}
