<?php

namespace App\Http\Controllers;

use App\item;
use App\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class stockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();

        $data['stocks'] = DB::table('stocks')
        ->select(
            'stocks.id as id',
            'stocks.stock_type as stock_type',
            'stocks.order_type as order_type',
            'stocks.stock_qty as stock_qty',
            'stocks.created_at as created_at',
            'stocks.updated_at as updated_at',
            'stocks.item_price as item_price',
            'stocks.Manufacture_date as Manufacture_date',
            'stocks.expiration_date as expiration_date',
            'items.item_name as item_name',
            'stocks.transaction_name as transaction_name'
        )
        ->join('items','items.id','=','stocks.item_id')
        ->OrderBy('stocks.created_at','desc')
        ->get();

        return view("stock.stockDetails")->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $data['items']      = item::OrderBy('created_at','asc')->get();

        $data['items'] = DB::table('items')
        ->select(
            'items.id as id',
            'items.item_name as item_name',
            'items.order_type_id as order_type_id',
            'item_order_types.order_type_name as order_type_name'
        )
        ->join('item_order_types','item_order_types.id','=','items.order_type_id')
        ->get();

        return view('stock.newStockDetails')->with('data',$data);
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
            'item_id'          => 'required',
            'stock_type'       => 'required',
            'order_type'       => 'required',
            'stock_qty'        => 'required',
            'item_price'       => 'required',
            'Manufacture_date' => 'required',
            'expiration_date'  => 'required',
        ]);

        $stock                   = new stock(); 
        $stock->item_id          = $request->item_id;
        $stock->stock_type       = $request->stock_type;
        $stock->order_type       = $request->order_type_id;
        $stock->stock_qty        = $request->stock_qty;
        $stock->item_price       = $request->item_price;
        $stock->Manufacture_date = $request->Manufacture_date;
        $stock->expiration_date  = $request->expiration_date;
        $stock->remarks          = $request->remarks;
        $stock->transaction_name = "Stock";
        $stock->updated_at       = null;
        $stock->save();

        return redirect('stock')->with('status', 'You have successfully Added Item Into Stock !');
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

        // $data['stock'] = stock::findOrFail($id);

        $data['stock'] = DB::table('stocks')
        ->select(
            'stocks.id as id',
            'stocks.item_id as item_id',
            'stocks.stock_type as stock_type',
            'stocks.order_type as order_type',
            'stocks.stock_qty as stock_qty',
            'stocks.item_price as item_price',
            'stocks.Manufacture_date as Manufacture_date',
            'stocks.expiration_date as expiration_date',
            'stocks.remarks as remarks',
            'items.id as item_id',
            'items.item_name as item_name',
            'items.order_type_id as order_type_id',
            'item_order_types.order_type_name as order_type_name'
        )
        ->join('items','items.id','=','stocks.item_id')
        ->leftjoin('item_order_types','item_order_types.id','=','items.order_type_id')
        ->where('stocks.id',$id)
        ->first();


        $data['items'] = item::OrderBy('created_at','asc')->get();

        return view('stock.newStockDetails')->with('data',$data);
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
            'item_id'          => 'required',
            'stock_type'       => 'required',
            'order_type'       => 'required',
            'stock_qty'        => 'required',
            'item_price'       => 'required',
            'Manufacture_date' => 'required',
            'expiration_date'  => 'required',
        ]);

        $stock                   = stock::find($id);; 
        $stock->item_id          = $request->item_id;
        $stock->stock_type       = $request->stock_type;
        $stock->order_type       = $request->order_type_id;
        $stock->stock_qty        = $request->stock_qty;
        $stock->item_price       = $request->item_price;
        $stock->Manufacture_date = $request->Manufacture_date;
        $stock->expiration_date  = $request->expiration_date;
        $stock->remarks          = $request->remarks;
        $stock->save();

        return redirect('stock')->with('status', 'You have successfully Updated the Item Stock!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
