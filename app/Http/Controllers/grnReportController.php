<?php

namespace App\Http\Controllers;
use App\supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class grnReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data              = array();
        $data['suppliers'] = supplier::OrderBy('created_at','asc')->get();
        
        $from_date         =  date('Y-m-01');
        $to_date           =  date('Y-m-d');

        $grns =DB::table('grns')
        ->select(
            'grns.grn_date as id',
            'grns.grn_date as grn_date',
            'grns.grn_amount as grn_amount',
            'grns.grn_id as grn_id',
            'purchase_orders.order_date as order_date',
            'purchase_orders.order_id as order_id',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('purchase_orders','purchase_orders.id','=','grns.purchase_order_id')
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->where(function($query) use ($from_date, $to_date) 
        {
            if($from_date != null)
                $query->where('grns.grn_date','>=',$from_date);

            if($to_date != null)
                $query->where('grns.grn_date','<=',$to_date);
        })
        ->get();

        foreach ($grns as $key => $grn) 
        {
           $grn->id            = $grn->id;
           $grn->grn_date      = $grn->grn_date;
           $grn->grn_amount    = $grn->grn_amount;
           $grn->grn_code      = $grn->grn_id;
           $grn->order_date    = $grn->order_date;
           $grn->order_id      = $grn->order_id;
           $grn->supplier_name = $grn->supplier_name;
        }

        if(!$grns->isEmpty())
        {
            $data['grns']      = $grns;
            return view("report.grnReport")->with('data',$data);
        }
        else
        {
            return view("report.grnReport");
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

        $grns =DB::table('grns')
        ->select(
            'grns.grn_date as id',
            'grns.grn_date as grn_date',
            'grns.grn_amount as grn_amount',
            'grns.grn_id as grn_id',
            'purchase_orders.order_date as order_date',
            'purchase_orders.order_id as order_id',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('purchase_orders','purchase_orders.id','=','grns.purchase_order_id')
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->where(function($query) use ($supplier_id, $from_date, $to_date) 
        {
            if($supplier_id != null)
                $query->where('purchase_orders.supplier_id', $supplier_id);

            if($from_date != null)
                $query->where('grns.grn_date','>=',$from_date);

            if($to_date != null)
                $query->where('grns.grn_date','<=',$to_date);
        })
        ->get();

        foreach ($grns as $key => $grn) 
        {
           $grn->id            = $grn->id;
           $grn->grn_date      = $grn->grn_date;
           $grn->grn_amount    = $grn->grn_amount;
           $grn->grn_code      = $grn->grn_id;
           $grn->order_date    = $grn->order_date;
           $grn->order_id      = $grn->order_id;
           $grn->supplier_name = $grn->supplier_name;
        }
        
        $data              = array();
        $data['grns']      = $grns;
        $data['suppliers'] = supplier::OrderBy('created_at','asc')->get();

        return view("report.grnReport")->with('data',$data);

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
