<?php

namespace App\Http\Controllers;

use App\salesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class salesInvoiceReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data      = array();

        $from_date =  date('Y-m-01');
        $to_date   =  date('Y-m-d');

        $sales_invoices = DB::table('sales_invoices')
        ->select('*')
        ->where(function($query) use ($from_date, $to_date) 
        {
            if($from_date != null)
                $query->where('sales_invoices.invoice_date','>=',$from_date);

            if($to_date != null)
                $query->where('sales_invoices.invoice_date','<=',$to_date);
        })
        ->get();

        foreach ($sales_invoices as $key => $sales_invoice) 
        {
            $sales_invoice->id                   = $sales_invoice->id;
            $sales_invoice->invoice_id           = $sales_invoice->invoice_id;
            $sales_invoice->invoice_date         = $sales_invoice->invoice_date;
            $sales_invoice->invoice_amount       = $sales_invoice->invoice_amount;
            $sales_invoice->discount             = $sales_invoice->discount;
            $sales_invoice->total_invoice_amount = $sales_invoice->invoice_amount - (($sales_invoice->invoice_amount * $sales_invoice->discount) / 100);
        }

        if(!$sales_invoices->isEmpty())
        {
            $data['sales_invoices'] = $sales_invoices;
            return view("report.salesInvoiceReport")->with('data',$data);
        }
        else
        {
            return view("report.salesInvoiceReport");
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
        $data      = array();
        $from_date = $request->from_date;
        $to_date   = $request->to_date;

        $sales_invoices = DB::table('sales_invoices')
        ->select('*')
        ->where(function($query) use ($from_date, $to_date) 
        {
            if($from_date != null)
                $query->where('sales_invoices.invoice_date','>=',$from_date);

            if($to_date != null)
                $query->where('sales_invoices.invoice_date','<=',$to_date);
        })
        ->get();

        foreach ($sales_invoices as $key => $sales_invoice) 
        {
            $sales_invoice->id                   = $sales_invoice->id;
            $sales_invoice->invoice_id           = $sales_invoice->invoice_id;
            $sales_invoice->invoice_date         = $sales_invoice->invoice_date;
            $sales_invoice->invoice_amount       = $sales_invoice->invoice_amount;
            $sales_invoice->discount             = $sales_invoice->discount;
            $sales_invoice->total_invoice_amount = $sales_invoice->invoice_amount - (($sales_invoice->invoice_amount * $sales_invoice->discount) / 100);
        }

        $data['sales_invoices'] = $sales_invoices;

        return view("report.salesInvoiceReport")->with('data',$data);
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
