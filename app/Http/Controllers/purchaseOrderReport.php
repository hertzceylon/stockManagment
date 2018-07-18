<?php

namespace App\Http\Controllers;
use App\supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class purchaseOrderReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['suppliers'] = supplier::OrderBy('created_at','asc')->get();

        $from_date         =  date('Y-m-01');
        $to_date           =  date('Y-m-d');

        $purchaseOrders = DB::table('purchase_orders')
        ->select(
            'purchase_orders.id as id',
            'purchase_orders.order_date as order_date',
            'purchase_orders.order_id as order_id',
            'purchase_orders.created_at as created_at',
            'purchase_orders.updated_at as updated_at',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->where(function($query) use ($from_date, $to_date) 
        {
            if($from_date != null)
                $query->where('purchase_orders.order_date','>=',$from_date);

            if($to_date != null)
                $query->where('purchase_orders.order_date','<=',$to_date);
        })
        ->get();

        foreach ($purchaseOrders as $key => $purchaseOrder) 
        {
           $purchaseOrder->id            = $purchaseOrder->id;
           $purchaseOrder->order_id      = $purchaseOrder->order_id;
           $purchaseOrder->order_date    = $purchaseOrder->order_date;
           $purchaseOrder->supplier_name = $purchaseOrder->supplier_name;
           $purchaseOrder->grn_code      = 0;
           $purchaseOrder->grn_date      = 0;
           $purchaseOrder->grn_amount    = 0;
        }


        if(!$purchaseOrders->isEmpty())
        {
            $data['purchaseOrders'] = $purchaseOrders;
            return view("report.purchaseOrderReport")->with('data',$data);
        }
        else
        {
            return view("report.purchaseOrderReport");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplier_id =$request->supplier_id;
        $from_date   =$request->from_date;
        $to_date     =$request->to_date;

        $purchaseOrders = DB::table('purchase_orders')
        ->select(
            'purchase_orders.id as id',
            'purchase_orders.order_date as order_date',
            'purchase_orders.order_id as order_id',
            'purchase_orders.created_at as created_at',
            'purchase_orders.updated_at as updated_at',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->where(function($query) use ($supplier_id, $from_date, $to_date) 
        {
            if($supplier_id != null)
                $query->where('purchase_orders.supplier_id', $supplier_id);

            if($from_date != null)
                $query->where('purchase_orders.order_date','>=',$from_date);

            if($to_date != null)
                $query->where('purchase_orders.order_date','<=',$to_date);
        })
        ->get();

        foreach ($purchaseOrders as $key => $purchaseOrder) 
        {
           $purchaseOrder->id            = $purchaseOrder->id;
           $purchaseOrder->order_id      = $purchaseOrder->order_id;
           $purchaseOrder->order_date    = $purchaseOrder->order_date;
           $purchaseOrder->supplier_name = $purchaseOrder->supplier_name;
           $purchaseOrder->grn_code      = 0;
           $purchaseOrder->grn_date      = 0;
           $purchaseOrder->grn_amount    = 0;
        }
        
        $data              = array();
        $data['suppliers'] = supplier::OrderBy('created_at','asc')->get();

        if(!$purchaseOrders->isEmpty())
        {
            $data['purchaseOrders'] = $purchaseOrders;
            return view("report.purchaseOrderReport")->with('data',$data);
        }
        else
        {
            return view("report.purchaseOrderReport");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
