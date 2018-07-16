<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\item;
use App\itemOrderTypes;
use App\subCategory;
use App\category;
use Illuminate\Support\Facades\DB;

class itemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = DB::table('items')
        ->select(
            'items.id as id', 
            'items.item_name as item_name',
            'items.bar_code as bar_code',
            'items.status as status', 
            'items.created_at as created_at', 
            'items.updated_at as updated_at',
            'categories.cate_name as cate_name',
            'sub_categories.sub_cate_name as sub_cate_name'
        )
        ->join('categories','categories.id','=','items.cate_id')
        ->leftjoin('sub_categories','sub_categories.id','=','items.sub_cate_id')
        ->OrderBy('items.created_at','asc')
        ->get();

        return view("item.items")->with('items',$item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data                   = array();
        $data['categories']     = category::OrderBy('created_at','asc')->get();
        $data['itemOrderTypes'] = itemOrderTypes::OrderBy('created_at','asc')->get();

        return view('item.itemCreate')->with('data',$data);
    }

    public function fetch_sub_category(Request $request)
    {
        $cate_id = $request->input('category_id');

        $subCategories = DB::table('sub_categories')
        ->select('*')
        ->where('sub_categories.cate_id',$cate_id)
        ->OrderBy('created_at','asc')
        ->get();

        return $subCategories;
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
            'item_name'     => 'required',
            'category_id'   => 'required',
            'order_type_id' => 'required'
        ]);

        $item                = new item();
        $item->item_name     = $request->item_name;
        $item->bar_code      = $request->bar_code;
        $item->cate_id       = $request->category_id;
        $item->sub_cate_id   = $request->sub_category_id != null ? $request->sub_category_id : null;
        $item->status        = isset($request->status) ? 1 : 0;
        $item->order_type_id = $request->order_type_id;
        $item->remarks       = $request->remarks;
        $item->updated_at    = null;
        $item->save();

        return redirect('item')->with('status', 'You have successfully Created new Item!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = array();

        $data['item']           = item::findOrFail($id);
        $data['categories']     = category::OrderBy('created_at','asc')->get();
        $data['itemOrderTypes'] = itemOrderTypes::OrderBy('created_at','asc')->get();
        $data['sub_category']   = DB::table('sub_categories')
        ->select('*')
        ->where('sub_categories.cate_id',$data['item']->cate_id)
        ->get();

        return view('item.itemCreate')->with('data',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
            'item_name'   => 'required',
            'category_id' => 'required',
        ]);

        $item              = item::find($id);
        $item->item_name   = $request->item_name;
        $item->bar_code    = $request->bar_code;
        $item->cate_id     = $request->category_id;
        $item->sub_cate_id = $request->sub_category_id != null ? $request->sub_category_id : null;
        $item->status      = isset($request->status) ? 1 : 0;
        $item->remarks     = $request->remarks;
        $item->save();

        return redirect('item')->with('status', 'You have successfully updated the item!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        item::where('id',$id)->delete();
        return redirect('/item')->with('status_delete','Category Deleted !');
    }
}
