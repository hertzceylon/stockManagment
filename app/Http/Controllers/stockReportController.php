<?php

namespace App\Http\Controllers;
use App\item;
use App\stock;
use App\grn;
use App\grnEntry;
use App\purchaseReturn;
use App\purchaseReturnEntries;
use App\salesInvoice;
use App\saleInvoiceEntry;
use App\salesReturn;
use App\salesReturnEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class stockReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data      = array();
        $items     = item::OrderBy('created_at','asc')->get();
        $from_date = date('Y-m-01');
        $to_date   = date('Y-m-d');

        foreach ($items as $key => $item) 
        {
          $stocks = DB::table('stocks')
         ->select(
            'stocks.item_id as  item_id',
            DB::raw('SUM(stocks.stock_qty) as stock_qty'),
            'stocks.item_price as item_price',
            'stocks.Manufacture_date as Manufacture_date',
            'stocks.expiration_date  as expiration_date',
            'stocks.transaction_name  as transaction_name',
            'items.item_name as item_name',
            'items.status as status'
         )
         ->join('items','items.id','=','stocks.item_id')
         ->where('stocks.item_id', $item->id)
          ->where(function($query) use ($from_date, $to_date) 
          {
            if($from_date != null)
            {
              $query->where('stocks.expiration_date','>=',$from_date);
              $query->orwhereNull('stocks.expiration_date');
            }

            if($to_date != null)
            {
              $query->where('stocks.expiration_date','<=',$to_date);
              $query->orwhereNull('stocks.expiration_date');
            }
          })
          ->OrderBy('stocks.item_id')
          ->OrderBy('stocks.transaction_name','asc')
          ->OrderBy('stocks.expiration_date')
          ->groupBy('stocks.transaction_name')
          ->get();

          $total_qty = 0;

          foreach ($stocks as $key => $stock) 
          {
            if($stock->transaction_name == "GRN" || $stock->transaction_name == "Sales Return" || $stock->transaction_name == "Stock")
            {
              $total_qty                                 += $stock->stock_qty;

              if($total_qty > 0)
                $final_array[$stock->item_id]['stock_qty'] = $total_qty;
              else
                $final_array[$stock->item_id]['stock_qty'] = 0;
            }

            if($stock->transaction_name == "Purchase Return" || $stock->transaction_name == "Sales invoice")
            {
              $total_qty -= $stock->stock_qty;

              if($total_qty > 0)
                $final_array[$stock->item_id]['stock_qty'] = $total_qty;
              else
                $final_array[$stock->item_id]['stock_qty'] = 0;
            }

            $final_array[$stock->item_id]['item_name']        = $stock->item_name;

            if($total_qty > 0)
              $final_array[$stock->item_id]['status']           = 'Available';
            else
              $final_array[$stock->item_id]['status']           = 'Not Available';
          }
        }

        $data['items'] = $items;
        $data['stocks'] = $final_array;
        return view("report.stockReport")->with('data',$data);
    }

    public function store(Request $request)
    {
        $item_id   =$request->item_id;
        $from_date =$request->from_date;
        $to_date   =$request->to_date;
        $final_array = array();

        if($item_id != null)
        {
          $items = item::where('id',$item_id)->OrderBy('created_at','asc')->get();
        }
        else
        {
          $items = item::OrderBy('created_at','asc')->get();
        }

        $items_for_select = item::OrderBy('created_at','asc')->get();

        foreach ($items as $key => $item) 
        {
          $stocks = DB::table('stocks')
         ->select(
            'stocks.item_id as  item_id',
            DB::raw('SUM(stocks.stock_qty) as stock_qty'),
            'stocks.item_price as item_price',
            'stocks.Manufacture_date as Manufacture_date',
            'stocks.expiration_date  as expiration_date',
            'stocks.transaction_name  as transaction_name',
            'items.item_name as item_name',
            'items.status as status'
         )
         ->join('items','items.id','=','stocks.item_id')
         ->where('stocks.item_id', $item->id)
          ->where(function($query) use ($from_date, $to_date) 
          {
            if($from_date != null)
              $query->where('stocks.expiration_date','>=',$from_date);

            if($to_date != null)
              $query->where('stocks.expiration_date','<=',$to_date);
          })
          ->OrderBy('stocks.item_id')
          ->OrderBy('stocks.transaction_name','asc')
          ->OrderBy('stocks.expiration_date')
          ->groupBy('stocks.transaction_name')
          ->get();

          $total_qty = 0;

          foreach ($stocks as $key => $stock) 
          {
            if($stock->transaction_name == "GRN" || $stock->transaction_name == "Sales Return" || $stock->transaction_name == "Stock")
            {
              $total_qty                                 += $stock->stock_qty;

              if($total_qty > 0)
                $final_array[$stock->item_id]['stock_qty'] = $total_qty;
              else
                $final_array[$stock->item_id]['stock_qty'] = 0;
            }

            if($stock->transaction_name == "Purchase Return" || $stock->transaction_name == "Sales invoice")
            {
              $total_qty -= $stock->stock_qty;

              if($total_qty > 0)
                $final_array[$stock->item_id]['stock_qty'] = $total_qty;
              else
                $final_array[$stock->item_id]['stock_qty'] = 0;
            }

            $final_array[$stock->item_id]['item_name']        = $stock->item_name;
            // $final_array[$stock->item_id]['status']           = ($stock->status == 1 ? 'Available' : 'Not Available');

            if($total_qty > 0)
              $final_array[$stock->item_id]['status']           = 'Available';
            else
              $final_array[$stock->item_id]['status']           = 'Not Available';
          }
        }

        $data           = array();
        $data['items']  = $items_for_select;
        $data['stocks'] = $final_array;

        return view("report.stockReport")->with('data',$data);
  }
}