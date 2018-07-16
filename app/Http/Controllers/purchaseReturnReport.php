<?php

namespace App\Http\Controllers;
use App\supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class purchaseReturnReport extends Controller
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

        return view("report.purchaseReturnReport")->with('data',$data);
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

         $purchaseReturns = DB::table('purchase_returns')
        ->select(
            'purchase_returns.id as id',
            'purchase_returns.return_date as return_date',
            'purchase_returns.return_id as return_id',
            'purchase_returns.return_amount as return_amount',
            'purchase_returns.created_at as created_at',
            'purchase_returns.updated_at as updated_at',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('suppliers','suppliers.id','=','purchase_returns.supplier_id')
         ->where(function($query) use ($supplier_id, $from_date, $to_date) 
            {
                if($supplier_id != null)
                    $query->where('purchase_returns.supplier_id', $supplier_id);

                if($from_date != null)
                    $query->where('purchase_returns.return_date','>=',$from_date);

                if($to_date != null)
                    $query->where('purchase_returns.return_date','<=',$to_date);
            })
        ->get();

        $data                   = array();
        $data['purchaseReturns'] = $purchaseReturns;
        $data['suppliers']      = supplier::OrderBy('created_at','asc')->get();

        return view("report.purchaseReturnReport")->with('data',$data);


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
