<?php

namespace App\Http\Controllers;

use App\item;
use App\returnType;
use App\salesInvoice;
use App\salesReturn;
use App\salesReturnEntries;
use App\stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class salesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salesReturns = DB::table('sales_returns')
        ->select(
            'sales_returns.id as id',
            'sales_returns.return_date as return_date',
            'sales_returns.sales_return_id as return_id',
            'sales_returns.return_amount as return_amount',
            'sales_returns.created_at as created_at',
            'sales_returns.updated_at as updated_at'
        )
        ->OrderBy('sales_returns.created_at','desc')
        ->get();

        return view("salesReturn.salesReturn")->with('salesReturns',$salesReturns);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $data                  = array();
      $data['items']         = item::OrderBy('created_at','asc')->get();
      $data['returnTypes']   = returnType::OrderBy('created_at','asc')->get();
      $data['salesInvoices'] = salesInvoice::OrderBy('created_at','asc')->get();

      $lastSReturn = salesReturn::orderBy('created_at', 'desc')->first();

      if(! $lastSReturn)
      {
        $number = 0;
      }
      else
      {
        $number = substr($lastSReturn->sales_return_id, 3);
      }

      $data['code'] = 'SR' . sprintf('%06d', intval($number) + 1);

      return view('salesReturn.newSalesReturn')->with('data',$data);
    }

    public function fetch_sales_invoice_item(Request $request)
    {
      $sales_invoice_id = $request->input('sales_invoice_id');

      if($sales_invoice_id != null)
      {
        $sale_invoice_entries = DB::table('sale_invoice_entries')
        ->select(
            'sale_invoice_entries.item_id as item_id',
            'sale_invoice_entries.price as item_price',
            'items.item_name as item_name'
        )
        ->join('items','items.id','=','sale_invoice_entries.item_id')
        ->where('sale_invoice_entries.sales_invoice_id','=',$sales_invoice_id)
        ->get();
      }
      else
      {
        $sale_invoice_entries = item::OrderBy('created_at','asc')->get();
      }

        return $sale_invoice_entries;
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
            'sales_return_date'   => 'required',
            'sales_return_amount' => 'required',
        ]);

        $sales_return_item     = $request->sales_return_item;
        $update_id             = $request->update_id;
        $stock_sales_return_id = 0;

        if($validator->fails())
        {
          return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
          if($update_id == null)
          {
            $salesReturn                   = new salesReturn();
            $salesReturn->return_date      = $request->sales_return_date;
            $salesReturn->sales_invoice_id = $request->sales_invoice_id;
            $salesReturn->sales_return_id  = $request->sales_return_id;
            $salesReturn->return_amount    = $request->sales_return_amount;
            $salesReturn->remarks          = $request->remarks;
            $salesReturn->updated_at       = null;
            $salesReturn->save();
            $insertedId                    = $salesReturn->id;

            $stock_sales_return_id = $insertedId;

            if(isset($sales_return_item) && !empty($sales_return_item))
            {
              foreach ($sales_return_item as $value) 
              {
                $salesReturnEntries                  = new salesReturnEntries();
                $salesReturnEntries->sales_retrun_id = $insertedId;
                $salesReturnEntries->item_id         = $value['item_id'];
                $salesReturnEntries->return_type_id  = $value['return_type_id'];
                $salesReturnEntries->return_qty      = $value['return_qty'];
                $salesReturnEntries->item_price      = $value['item_price'];
                $salesReturnEntries->item_remark     = $value['item_remark'];
                $salesReturnEntries->updated_at      = null;
                $salesReturnEntries->save();
              }
            }
          }
          else
          {
            $stock_sales_return_id = $update_id;

            $salesReturn                   = salesReturn::find($update_id);
            $salesReturn->return_date      = $request->sales_return_date;
            $salesReturn->sales_invoice_id = $request->sales_invoice_id;
            $salesReturn->sales_return_id  = $request->sales_return_id;
            $salesReturn->return_amount    = $request->sales_return_amount;
            $salesReturn->remarks          = $request->remarks;
            $salesReturn->save();

            salesReturnEntries::where('sales_retrun_id',$update_id)->delete();

            if(isset($sales_return_item) && !empty($sales_return_item))
            {
              foreach ($sales_return_item as $value) 
              {
                $salesReturnEntries                  = new salesReturnEntries();
                $salesReturnEntries->sales_retrun_id = $update_id;
                $salesReturnEntries->item_id         = $value['item_id'];
                $salesReturnEntries->return_type_id  = $value['return_type_id'];
                $salesReturnEntries->return_qty      = $value['return_qty'];
                $salesReturnEntries->item_price      = $value['item_price'];
                $salesReturnEntries->item_remark     = $value['item_remark'];
                $salesReturnEntries->save();
              }
            }
          }

          $this->stock_control($sales_return_item,$stock_sales_return_id);

          $response = array('status' =>'Record is successfully added');
          return response()->json($response);
        }
    }

    function stock_control($sales_return_item,$stock_sales_return_id)
    {
      $stock = DB::table('stocks')
      ->select('*')
      ->where('stocks.id','=',$stock_sales_return_id)
      ->where('stocks.transaction_name','=',"Sales Return")
      ->get();

      if(isset($sales_return_item) && !empty($sales_return_item))
      {
        foreach ($sales_return_item as $value) 
        {
          $stock                   = new stock(); 
          $stock->item_id          = $value['item_id'];
          $stock->transaction_name = "Sales Return";
          $stock->transaction_ID   = $stock_sales_return_id;
          $stock->stock_type       = '';
          $stock->stock_qty        = $value['return_qty'];
          $stock->item_price       = $value['item_price'];
          $stock->updated_at       = null;
          $stock->save();
        }
      }
      else
      {
        stock::where('stocks.transaction_ID','=',$stock_sales_return_id)->where('stocks.transaction_name','=',"Sales Return")->delete();
        
        foreach ($sales_return_item as $value) 
        {
          $stock                   = new stock(); 
          $stock->item_id          = $value['item_id'];
          $stock->transaction_name = "Sales Return";
          $stock->transaction_ID   = $stock_sales_return_id;
          $stock->stock_type       = '';
          $stock->stock_qty        = $value['return_qty'];
          $stock->item_price       = $value['item_price'];
          $stock->save();
        }
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
        $data = array();

        $salesReturn = DB::table('sales_returns')
        ->select("*")
        ->where('sales_returns.id','=',$id)
        ->first();

        $salesReturnEntries = DB::table('sales_return_entries')
        ->select(
            'sales_return_entries.item_id as item_id',
            'sales_return_entries.return_type_id as return_type_id',
            'sales_return_entries.item_price as item_price',
            'sales_return_entries.return_qty as return_qty',
            'sales_return_entries.item_remark as item_remark',
            'items.item_name as item_name',
            'return_types.return_name as return_name'
        )
        ->join('items','items.id','=','sales_return_entries.item_id')
        ->join('return_types','return_types.id','=','sales_return_entries.return_type_id')
        ->where('sales_return_entries.sales_retrun_id','=',$id)
        ->get();

        foreach ($salesReturnEntries as $key => $salesReturnEntry)
        {
          $salesReturnEntry->amount = ($salesReturnEntry->return_qty * $salesReturnEntry->item_price);
        }

        $data['salesReturn']        = $salesReturn;
        $data['salesReturnEntries'] = $salesReturnEntries;
        $data['returnTypes']        = returnType::OrderBy('created_at','asc')->get();
        $data['salesInvoices']      = salesInvoice::OrderBy('created_at','asc')->get();

        if($salesReturn->sales_invoice_id != null || $salesReturn->sales_invoice_id!= '')
        {
          $data['items'] = DB::table('sale_invoice_entries')
          ->select(
              'sale_invoice_entries.item_id as id',
              'sale_invoice_entries.price as item_price',
              'items.item_name as item_name'
          )
          ->join('items','items.id','=','sale_invoice_entries.item_id')
          ->where('sale_invoice_entries.sales_invoice_id','=',$salesReturn->sales_invoice_id)
          ->get();
        }
        else
        {
          $data['items'] = item::OrderBy('created_at','asc')->get();
        }

        return view('salesReturn.newSalesReturn')->with('data',$data);
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
