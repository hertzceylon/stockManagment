<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\category;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $category = category::OrderBy('created_at','asc')->get();
       return view("category.categories")->with('categories',$category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.categoryCreate');
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
            'category_name' => 'required',
        ]);

        $category             = new category(); 
        $category->cate_name  = $request->category_name;
        $category->remarks    = $request->remarks;
        $category->status     = isset($request->status) ? 1 : 0;
        $category->updated_at = null;
        $category->save();
        return redirect('category')->with('status', 'You have successfully Created new Category!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = category::findOrFail($id);
        return view('category.categoryCreate',compact('category'));
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
            'category_name' => 'required',
        ]);

        $category            = category::find($id);
        $category->cate_name = $request->category_name;
        $category->remarks    = $request->remarks;
        $category->status     = isset($request->status) ? 1 : 0;
        $category->save();
        return redirect('/category')->with('status', 'Category Modified Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        category::where('id',$id)->delete();
        return redirect('/category')->with('status_delete','Category Deleted !');
    }
}
