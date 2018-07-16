<?php

namespace App\Http\Controllers;
use App\item;
use App\salesInvoice;
use App\saleInvoiceEntry;
use App\stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class saleInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data                  = array();
        $data['salesInvoices'] = salesInvoice::OrderBy('created_at','desc')->get();

        return view("salesInvoice.SalesInvoices")->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data          = array();

        $data['items']= DB::table('items')
        ->select(
            'items.id as id', 
            'items.item_name as item_name',
            'items.bar_code as bar_code',
            'items.order_type_id as order_type_id',
            'item_order_types.order_type_name as order_type_name',
            DB::raw('SUM(stocks.stock_qty) as stock_qty'),
            'stocks.item_price as item_price as item_price'
        )
        ->join('item_order_types','item_order_types.id','=','items.order_type_id')
        ->join('stocks','stocks.item_id','=','items.id')
        ->where('stocks.transaction_name','=','GRN')
        ->OrderBy('items.id')
        ->OrderBy('stocks.item_price')
        ->groupBy('items.id')
        ->groupBy('stocks.item_price')
        ->get();

        $lastSInvoice = salesInvoice::orderBy('created_at', 'desc')->first();

        if(! $lastSInvoice)
        {
          $number = 0;
        }
        else
        {
          $number = substr($lastSInvoice->invoice_id, 3);
        }

        $data['code'] = 'SI' . sprintf('%06d', intval($number) + 1);

        return view('salesInvoice.newSaleInvoice')->with('data',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'invoice_id'   => 'required',
            'invoice_date' => 'required',
            'invoice_item' => 'required',
        ]);

        $print_id = 0;

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
          if($request->update_id == null)
          {
              $salesInvoice                 = new salesInvoice();
              $salesInvoice->invoice_date   = $request->invoice_date;
              $salesInvoice->invoice_id     = $request->invoice_id;
              $salesInvoice->invoice_amount = $request->invoice_amount;
              $salesInvoice->discount       = $request->discount;
              $salesInvoice->payment        = $request->payment_amount;
              $salesInvoice->remarks        = $request->remarks;
              $salesInvoice->updated_at     = null;
              $salesInvoice->save();
              $insertedId                   = $salesInvoice->id;
              $print_id                     = $insertedId;

              $invoice_item = $request->invoice_item;

              if(isset($invoice_item) && !empty($invoice_item))
              {
                  foreach ($invoice_item as $value) 
                  {
                      $saleInvoiceEntry                    = new saleInvoiceEntry();
                      $saleInvoiceEntry->sales_invoice_id = $insertedId;
                      $saleInvoiceEntry->item_id          = $value['item_id'];
                      $saleInvoiceEntry->order_type       = $value['order_type'];
                      $saleInvoiceEntry->invoice_qty      = $value['invoice_qty'];
                      $saleInvoiceEntry->price            = $value['item_price'];
                      $saleInvoiceEntry->updated_at       = null;
                      $saleInvoiceEntry->save();
                  }
              }
          }
          else
          {
              $update_id    = $request->update_id;
              $invoice_item = $request->invoice_item;

              $salesInvoice                 = salesInvoice::find($update_id);
              $salesInvoice->invoice_date   = $request->invoice_date;
              $salesInvoice->invoice_id     = $request->invoice_id;
              $salesInvoice->invoice_amount = $request->invoice_amount;
              $salesInvoice->discount       = $request->discount;
              $salesInvoice->payment        = $request->payment_amount;
              $salesInvoice->remarks        = $request->remarks;
              $salesInvoice->save();

              saleInvoiceEntry::where('sales_invoice_id',$update_id)->delete();

              $print_id = $update_id;

              if(isset($invoice_item) && !empty($invoice_item))
              {
                  foreach ($invoice_item as $value) 
                  {
                      $saleInvoiceEntry                    = new saleInvoiceEntry();
                      $saleInvoiceEntry->sales_invoice_id = $update_id;
                      $saleInvoiceEntry->item_id          = $value['item_id'];
                      $saleInvoiceEntry->order_type       = $value['order_type'];
                      $saleInvoiceEntry->invoice_qty      = $value['invoice_qty'];
                      $saleInvoiceEntry->price            = $value['item_price'];
                      $saleInvoiceEntry->save();
                  }
              }
          }

          $this->stock_control($invoice_item,$print_id);

          $response = array('status' =>'Record is successfully added','id'=>$print_id);
          return response()->json($response);
        }
    }

    function stock_control($invoice_item,$sales_invoice_id)
    {
      $stock = DB::table('stocks')
        ->select('*')
        ->where('stocks.id','=',$sales_invoice_id)
        ->where('stocks.transaction_name','=',"Sales invoice")
        ->get();

      if(isset($invoice_item) && !empty($invoice_item))
      {
        foreach ($invoice_item as $value) 
        {
          $stock                   = new stock(); 
          $stock->item_id          = $value['item_id'];
          $stock->transaction_name = "Sales invoice";
          $stock->transaction_ID   = $sales_invoice_id;
          $stock->stock_type       = '';
          $stock->stock_qty        = $value['invoice_qty'];
          $stock->item_price       = $value['item_price'];
          $stock->updated_at       = null;
          $stock->save();
        }
      }
      else
      {
        stock::where('stocks.transaction_ID','=',$sales_invoice_id)->where('stocks.transaction_name','=',"Sales invoice")->delete();
        foreach ($invoice_item as $value) 
        {
          $stock                   = new stock(); 
          $stock->item_id          = $value['item_id'];
          $stock->transaction_name = "Sales invoice";
          $stock->transaction_ID   = $sales_invoice_id;
          $stock->stock_type       = '';
          $stock->stock_qty        = $value['invoice_qty'];
          $stock->item_price       = $value['item_price'];
          $stock->save();
        }
      }

      // $this->show($sales_invoice_id);

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

        $salesInvoice = DB::table('sales_invoices')
        ->select("*")
        ->where('sales_invoices.id','=',$id)
        ->first();

        $saleInvoiceEntries = DB::table('sale_invoice_entries')
        ->select(
            'sale_invoice_entries.invoice_qty as invoice_qty',
            'sale_invoice_entries.item_id as item_id',
            'sale_invoice_entries.price as price',
            'items.item_name as item_name',
            'sale_invoice_entries.order_type as order_type',
            'item_order_types.order_type_name as order_type_name'
        )
        ->join('items','items.id','=','sale_invoice_entries.item_id')
        ->join('item_order_types','item_order_types.id','=','sale_invoice_entries.order_type')
        ->where('sale_invoice_entries.sales_invoice_id','=',$id)
        ->get();

        $salesInvoice->discount_amount      = ($salesInvoice->invoice_amount * $salesInvoice->discount)/100;
        $salesInvoice->total_invoice_amount = $salesInvoice->invoice_amount + ($salesInvoice->invoice_amount * $salesInvoice->discount)/100;
        $salesInvoice->balance_payment      = $salesInvoice->payment-$salesInvoice->total_invoice_amount;

        foreach ($saleInvoiceEntries as $key => $saleInvoiceEntry)
        {

          $stock = DB::table('stocks')
          ->select(
            DB::raw('SUM(stocks.stock_qty) as stock_qty')
          )
          ->where('stocks.item_id','=',$saleInvoiceEntry->item_id)
          ->where('stocks.item_price','=',$saleInvoiceEntry->price)
          ->first();

            $saleInvoiceEntry->avb_qty         =$stock->stock_qty- $saleInvoiceEntry->invoice_qty;
        }

        $data['salesInvoice']       = $salesInvoice;
        $data['saleInvoiceEntries'] = $saleInvoiceEntries;

        $data['items']= DB::table('items')
        ->select(
            'items.id as id', 
            'items.item_name as item_name',
            'items.order_type_id as order_type_id',
            'item_order_types.order_type_name as order_type_name',
            DB::raw('SUM(stocks.stock_qty) as stock_qty'),
            'stocks.item_price as item_price as item_price'
        )
        ->join('item_order_types','item_order_types.id','=','items.order_type_id')
        ->join('stocks','stocks.item_id','=','items.id')
        ->where('stocks.transaction_name','=','GRN')
        ->OrderBy('items.id')
        ->OrderBy('stocks.item_price')
        ->groupBy('items.id')
        ->groupBy('stocks.item_price')
        ->get();

        return view('salesInvoice.newSaleInvoice')->with('data',$data);
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
    public function update(Request $request,$id)
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

  //   public function print_all_slips_actual($salary_id,$branch_id)
  // {
  //   $data = $this->index_with_slips($salary_id,$branch_id);
  //   return view('layouts.printouts.slip_bundle_actual',$data);
  // }

    public function print_sales_invoice($print_id)
    {
        $data = array();

        $salesInvoice = DB::table('sales_invoices')
        ->select("*")
        ->where('sales_invoices.id','=',$print_id)
        ->first();

          $saleInvoiceEntries = DB::table('sale_invoice_entries')
        ->select(
            'sale_invoice_entries.order_type as order_type',
            'sale_invoice_entries.invoice_qty as invoice_qty',
            'sale_invoice_entries.item_id as item_id',
            'sale_invoice_entries.price as price',
            'items.item_name as item_name'
        )
        ->join('items','items.id','=','sale_invoice_entries.item_id')
        ->where('sale_invoice_entries.sales_invoice_id','=',$print_id)
        ->get();

        $salesInvoice->discount_amount      = ($salesInvoice->invoice_amount * $salesInvoice->discount)/100;
        $salesInvoice->total_invoice_amount = $salesInvoice->invoice_amount + ($salesInvoice->invoice_amount * $salesInvoice->discount)/100;
        $salesInvoice->balance_payment      = $salesInvoice->payment-$salesInvoice->total_invoice_amount;

        foreach ($saleInvoiceEntries as $key => $saleInvoiceEntry)
        {

          $stock = DB::table('stocks')
          ->select(
            DB::raw('SUM(stocks.stock_qty) as stock_qty')
          )
          ->where('stocks.item_id','=',$saleInvoiceEntry->item_id)
          ->where('stocks.item_price','=',$saleInvoiceEntry->price)
          ->first();

            $saleInvoiceEntry->avb_qty         =$stock->stock_qty- $saleInvoiceEntry->invoice_qty;
            $saleInvoiceEntry->issue_type_name = ($saleInvoiceEntry->order_type =="C" ? 'Case' : 'Unit');
        }

        $data['salesInvoice']       = $salesInvoice;
        $data['saleInvoiceEntries'] = $saleInvoiceEntries;
        return view('printouts.salesInvoicePrint')->with('data',$data);

    }
}
