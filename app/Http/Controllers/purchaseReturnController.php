<?php

namespace App\Http\Controllers;
use App\item;
use App\supplier;
use App\returnType;
use App\purchaseReturn;
use App\purchaseReturnEntries;
use App\stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class purchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        ->OrderBy('purchase_returns.created_at','desc')
        ->get();

        return view("purchaseReturn.purchaseReturns")->with('purchaseReturns',$purchaseReturns);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data                = array();
        $data['suppliers']   = supplier::OrderBy('created_at','asc')->get();
        $data['items']       = item::OrderBy('created_at','asc')->get();
        $data['returnTypes'] = returnType::OrderBy('created_at','asc')->get();

        $lastPReturn = purchaseReturn::orderBy('created_at', 'desc')->first();

        if(! $lastPReturn)
        {
          $number = 0;
        }
        else
        {
          $number = substr($lastPReturn->return_id, 3);
        }

        $data['code'] = 'PR' . sprintf('%06d', intval($number) + 1);

        return view('purchaseReturn.newPurchaseReturn')->with('data',$data);
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
            'return_date'   => 'required',
            'supplier_id'   => 'required',
            'return_amount' => 'required',
        ]);

        $return_item         = $request->return_item;
        $update_id           = $request->update_id;
        $return_id_for_stock = 0;

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
          if($update_id == null)
          {
              $purchaseReturn                = new purchaseReturn();
              $purchaseReturn->return_date   = $request->return_date;
              $purchaseReturn->return_id     = $request->return_id;
              $purchaseReturn->supplier_id   = $request->supplier_id;
              $purchaseReturn->return_amount = $request->return_amount;
              $purchaseReturn->remarks       = $request->remarks;
              $purchaseReturn->updated_at    = null;
              $purchaseReturn->save();
              $insertedId                    = $purchaseReturn->id;
              $return_id_for_stock = $insertedId;

              if(isset($return_item) && !empty($return_item))
              {
                  foreach ($return_item as $value) 
                  {
                      $purchaseReturnEntries                     = new purchaseReturnEntries();
                      $purchaseReturnEntries->purchase_retrun_id = $insertedId;
                      $purchaseReturnEntries->item_id            = $value['item_id'];
                      $purchaseReturnEntries->stock_type_id      = $value['stock_type_id'];
                      $purchaseReturnEntries->return_type_id     = $value['return_type_id'];
                      $purchaseReturnEntries->return_qty         = $value['return_qty'];
                      $purchaseReturnEntries->item_price         = $value['item_price'];
                      $purchaseReturnEntries->updated_at         = null;
                      $purchaseReturnEntries->save();
                  }
              }
          }
          else
          {
            $return_id_for_stock = $update_id;

            $purchaseReturn                 = purchaseReturn::find($update_id);
            $purchaseReturn->return_date   = $request->return_date;
            $purchaseReturn->return_id     = $request->return_id;
            $purchaseReturn->supplier_id   = $request->supplier_id;
            $purchaseReturn->return_amount = $request->return_amount;
            $purchaseReturn->remarks       = $request->remarks;
            $purchaseReturn->save();

            purchaseReturnEntries::where('purchase_retrun_id',$update_id)->delete();

            if(isset($return_item) && !empty($return_item))
            {
                foreach ($return_item as $value) 
                {
                    $purchaseReturnEntries                     = new purchaseReturnEntries();
                    $purchaseReturnEntries->purchase_retrun_id = $update_id;
                    $purchaseReturnEntries->item_id            = $value['item_id'];
                    $purchaseReturnEntries->stock_type_id      = $value['stock_type_id'];
                    $purchaseReturnEntries->return_type_id     = $value['return_type_id'];
                    $purchaseReturnEntries->return_qty         = $value['return_qty'];
                    $purchaseReturnEntries->item_price         = $value['item_price'];
                    $purchaseReturnEntries->save();
                }
            }
          }

          $this->stock_control($return_item,$return_id_for_stock);

          $response = array('status' =>'Record is successfully added');
          return response()->json($response);
        }
    }

    function stock_control($return_item,$return_id_for_stock)
    {
      $stock = DB::table('stocks')
        ->select('*')
        ->where('stocks.id','=',$return_id_for_stock)
        ->where('stocks.transaction_name','=',"Purchase Return")
        ->get();

      if(isset($return_item) && !empty($return_item))
      {
        foreach ($return_item as $value) 
        {
          $stock                   = new stock(); 
          $stock->item_id          = $value['item_id'];
          $stock->transaction_name = "Purchase Return";
          $stock->transaction_ID   = $return_id_for_stock;
          $stock->stock_type       = $value['stock_type_id'];
          $stock->stock_qty        = $value['return_qty'];
          $stock->item_price       = $value['item_price'];
          $stock->updated_at       = null;
          $stock->save();
        }
      }
      else
      {
        stock::where('stocks.transaction_ID','=',$return_id_for_stock)->where('stocks.transaction_name','=',"Purchase Return")->delete();
        foreach ($return_item as $value) 
        {
          $stock                   = new stock(); 
          $stock->item_id          = $value['item_id'];
          $stock->transaction_name = "Purchase Return";
          $stock->transaction_ID   = $return_id_for_stock;
          $stock->stock_type       = $value['stock_type_id'];
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

        $purchaseReturn = DB::table('purchase_returns')
        ->select("*")
        ->where('purchase_returns.id','=',$id)
        ->first();

        $purchaseReturnEntries = DB::table('purchase_return_entries')
        ->select(
            'purchase_return_entries.item_id as item_id',
            'purchase_return_entries.stock_type_id as stock_type_id',
            'purchase_return_entries.return_type_id as return_type_id',
            'purchase_return_entries.item_price as item_price',
            'purchase_return_entries.return_qty as return_qty',
            'items.item_name as item_name',
            'return_types.return_name as return_name'
        )
        ->join('items','items.id','=','purchase_return_entries.item_id')
        ->join('return_types','return_types.id','=','purchase_return_entries.return_type_id')
        ->where('purchase_return_entries.purchase_retrun_id','=',$id)
        ->get();

        foreach ($purchaseReturnEntries as $key => $purchaseReturnEntry)
        {
          $purchaseReturnEntry->amount          = ($purchaseReturnEntry->return_qty * $purchaseReturnEntry->item_price);
          $purchaseReturnEntry->stock_type_name = ($purchaseReturnEntry->stock_type_id == "G" ? "Good" : "Bad");
        }

        $data['purchaseReturn']        = $purchaseReturn;
        $data['purchaseReturnEntries'] = $purchaseReturnEntries;
        $data['suppliers']             = supplier::OrderBy('created_at','asc')->get();
        $data['items']                 = item::OrderBy('created_at','asc')->get();
        $data['returnTypes']           = returnType::OrderBy('created_at','asc')->get();

        return view('purchaseReturn.newPurchaseReturn')->with('data',$data);
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
