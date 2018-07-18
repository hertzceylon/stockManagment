<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class salesReturnReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data      = array();
        $from_date = date('Y-m-01');
        $to_date   = date('Y-m-d');

        $sales_returns = DB::table('sales_returns')
        ->select('*')
        ->where(function($query) use ($from_date, $to_date) 
        {
            if($from_date != null)
                $query->where('sales_returns.return_date','>=',$from_date);

            if($to_date != null)
                $query->where('sales_returns.return_date','<=',$to_date);
        })
        ->get();

        foreach ($sales_returns as $key => $sales_return) 
        {
            $sales_return->id            = $sales_return->id;
            $sales_return->return_id     = $sales_return->sales_return_id;
            $sales_return->return_date   = $sales_return->return_date;
            $sales_return->return_amount = $sales_return->return_amount;
        }

        if(!$sales_returns->isEmpty())
        {
            $data['sales_returns'] = $sales_returns;
            return view("report.salesReturnReport")->with('data',$data);
        }
        else
        {
            return view("report.salesReturnReport");
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
        $data      =array();
        $from_date =$request->from_date;
        $to_date   =$request->to_date;

        $sales_returns = DB::table('sales_returns')
        ->select('*')
        ->where(function($query) use ($from_date, $to_date) 
        {
            if($from_date != null)
                $query->where('sales_returns.return_date','>=',$from_date);

            if($to_date != null)
                $query->where('sales_returns.return_date','<=',$to_date);
        })
        ->get();

        foreach ($sales_returns as $key => $sales_return) 
        {
            $sales_return->id            = $sales_return->id;
            $sales_return->return_id     = $sales_return->sales_return_id;
            $sales_return->return_date   = $sales_return->return_date;
            $sales_return->return_amount = $sales_return->return_amount;
        }

      if(!$sales_returns->isEmpty())
      {
        $data['sales_returns'] = $sales_returns;
        return view("report.salesReturnReport")->with('data',$data);
      }
      else
      {
        return view("report.salesReturnReport");
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
