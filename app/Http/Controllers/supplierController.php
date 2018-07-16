<?php

namespace App\Http\Controllers;

use App\item;
use App\supplier;
use App\supplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class supplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = supplier::OrderBy('created_at','asc')->get();
        return view("supplier.suppliers")->with('suppliers',$suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data         = array();
        $data['items'] = DB::table('items')
        ->select('*')
        ->get();

        return view('supplier.supplierCreate')->with('data',$data);
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
            'supplier_name'    => 'required',
            'address'          => 'required',
            'contact_number_1' => 'required',
            'contact_person'   => 'required',
        ]);

        $supplier                   = new supplier();
        $supplier->supplier_name    = $request->supplier_name;
        $supplier->supplier_address = $request->address;
        $supplier->contact_number_1 = $request->contact_number_1;
        $supplier->contact_number_2 = $request->contact_number_2;
        $supplier->contact_person   = $request->contact_person;
        $supplier->email            = $request->email_address;
        $supplier->remarks          = $request->remarks;
        $supplier->status           = isset($request->status) ? 1 : 0;
        $supplier->updated_at       = null;
        $supplier->save();

        return redirect('supplier')->with('status', 'You have successfully Created new Item!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $supplier   = supplier::findOrFail($id);
       return view('supplier.supplierCreate')->with('supplier',$supplier);
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
            'supplier_name'    => 'required',
            'address'          => 'required',
            'contact_number_1' => 'required',
            'contact_person'   => 'required',
        ]);

        $supplier                   = supplier::find($id);
        $supplier->supplier_name    = $request->supplier_name;
        $supplier->supplier_address = $request->address;
        $supplier->contact_number_1 = $request->contact_number_1;
        $supplier->contact_number_2 = $request->contact_number_2;
        $supplier->contact_person   = $request->contact_person;
        $supplier->email            = $request->email_address;
        $supplier->remarks          = $request->remarks;
        $supplier->status           = isset($request->status) ? 1 : 0;
        $supplier->save();

        return redirect('supplier')->with('status', 'You have successfully updated the Supplier!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
    }

    public function add_supply_item($supplier_id)
    {

        $data                = array();
        $data['supplier_id'] = $supplier_id;
        $data['items']        = item::OrderBy('created_at','asc')->get();
        $data['suppliers']    = supplier::OrderBy('created_at','asc')->get();

        $data['supplyitems'] = DB::table('supply_items')
        ->select(
            'items.item_name as item_name',
            'categories.cate_name as cate_name',
            'items.status as status', 
            'sub_categories.sub_cate_name as sub_cate_name',
            'supply_items.created_at as created_at',
            'supply_items.updated_at as updated_at',
            'supply_items.sup_id as sup_id',
            'supply_items.id as id'
        )
        ->join('items','items.id','=','supply_items.item_id')
        ->join('categories','categories.id','=','items.cate_id')
        ->leftjoin('sub_categories','sub_categories.id','=','items.sub_cate_id')
        ->where('supply_items.sup_id','=',$supplier_id)
        ->OrderBy('supply_items.created_at','asc')
        ->get();

        return view("supplier.add_supply_item")->with('data',$data);
    }

    public function add_supply_items(Request $request)
    {

        $this->validate(request(),[
        'item_id'   => 'required',
        'supplier_id' => 'required',
        ]);
        
       $items = $request->item_id;

       foreach ($items as $item) 
       {
            $supplyItem             = new supplyItem();
            $supplyItem->sup_id     = $request->supplier_id;
            $supplyItem->item_id    = $item;
            $supplyItem->updated_at = null;
            $supplyItem->save();
       }

       return $this->get_supply_item($request->supplier_id);
    }

    public function get_supply_item($supplier_id)
    {
       $data                = array();
       $data['supplier_id'] = $supplier_id;

       $data['supplyitems'] = DB::table('supply_items')
        ->select(
            'items.item_name as item_name',
            'categories.cate_name as cate_name',
            'items.status as status', 
            'sub_categories.sub_cate_name as sub_cate_name',
            'supply_items.created_at as created_at',
            'supply_items.updated_at as updated_at',
            'supply_items.sup_id as sup_id',
            'supply_items.id as id'
        )
        ->join('items','items.id','=','supply_items.item_id')
        ->join('categories','categories.id','=','items.cate_id')
        ->leftjoin('sub_categories','sub_categories.id','=','items.sub_cate_id')
        ->where('supply_items.sup_id','=',$supplier_id)
        ->OrderBy('supply_items.created_at','asc')
        ->get();

       $data['items']       = item::OrderBy('created_at','asc')->get();
       $data['suppliers']   = supplier::OrderBy('created_at','asc')->get();
       return view("supplier.add_supply_item")->with('data',$data ,'status', 'You have successfully Added Supply Item!');
    }

    public function remove_supply_items($id,$sup_id)
    {
        supplyItem::where('id',$id)->delete();

       $data                = array();
       $data['supplier_id'] = $sup_id;

       $data['supplyitems'] = DB::table('supply_items')
        ->select(
            'items.item_name as item_name',
            'categories.cate_name as cate_name',
            'items.status as status', 
            'sub_categories.sub_cate_name as sub_cate_name',
            'supply_items.created_at as created_at',
            'supply_items.updated_at as updated_at',
            'supply_items.sup_id as sup_id',
            'supply_items.id as id'
        )
        ->join('items','items.id','=','supply_items.item_id')
        ->join('categories','categories.id','=','items.cate_id')
        ->leftjoin('sub_categories','sub_categories.id','=','items.sub_cate_id')
        ->where('supply_items.sup_id','=',$sup_id)
        ->OrderBy('supply_items.created_at','asc')
        ->get();

       $data['items']       = item::OrderBy('created_at','asc')->get();
       $data['suppliers']   = supplier::OrderBy('created_at','asc')->get();

       return view("supplier.add_supply_item")->with('data',$data ,'status', 'You have successfully removed Supply Item!');
    }
}
