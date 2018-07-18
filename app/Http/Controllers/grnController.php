<?php

namespace App\Http\Controllers;
use App\purchaseOrder;
use App\prchaseOrderEntry;
use App\grn;
use App\grnEntry;
use App\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class grnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grns = DB::table('grns')
        ->select(
            'grns.id as id',
            'grns.purchase_order_id as purchase_order_id',
            'grns.grn_date as grn_date',
            'grns.grn_amount as grn_amount',
            'grns.grn_id as grn_id',
            'grns.created_at as created_at',
            'grns.updated_at as updated_at', 
            'purchase_orders.order_date as order_date',
            'purchase_orders.order_id as order_id',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('purchase_orders','purchase_orders.id','=','grns.purchase_order_id')
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->OrderBy('grns.created_at','desc')
        ->get();

        return view("grn.grns")->with('grns',$grns);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['purchaseOrders'] = DB::table('purchase_orders')
        ->select(
           'purchase_orders.id as id',
           'purchase_orders.order_date as order_date',
           'purchase_orders.order_id as order_id',
           'suppliers.supplier_name as supplier_name'
        )
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->get();

        $lastGRN = grn::orderBy('created_at', 'desc')->first();

        if(! $lastGRN)
        {
          $number = 0;
        }
        else
        {
          $number = substr($lastGRN->grn_id, 3);
        }

        $data['code'] = 'GRN' . sprintf('%06d', intval($number) + 1);

        return view('grn.newGrnCreate')->with('data',$data);
    }

    public function fetch_purchase_order_entries(Request $request)
    {
        $purchase_order_id = $request->input('purchase_order_id');

        $order_entries = DB::table('prchase_order_entries')
        ->select(
            'prchase_order_entries.order_type as order_type',
            'prchase_order_entries.order_qty as order_qty',
            'prchase_order_entries.item_id as item_id',
            'items.item_name as item_name'
        )
        ->join('items','items.id','=','prchase_order_entries.item_id')
        ->where('prchase_order_entries.purchase_order_id','=',$purchase_order_id)
        ->get();

      return $order_entries;
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
            'grn_date'            => 'required',
            'grn_amount'          => 'required',
        ]);

       $grn_item         = $request->grn_item;
       $update_id        = $request->update_id;
       $grn_id_for_stock = 0;

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
           if($update_id ==null)
           {
                $grn                    = new grn();
                $grn->purchase_order_id = $request->purchase_order_id;
                $grn->grn_date          = $request->grn_date;
                $grn->grn_amount        = $request->grn_amount;
                $grn->remarks           = $request->remarks;
                $grn->grn_id            = $request->grn_id;
                $grn->updated_at        = null;
                $grn->save();
                $insertedId             = $grn->id;
                $grn_id_for_stock       = $insertedId;

                if(isset($grn_item) && !empty($grn_item))
                {
                    foreach ($grn_item as $value) 
                    {
                        $grnEntry                 = new grnEntry();
                        $grnEntry->grn_id         = $insertedId;
                        $grnEntry->item_id        = $value['item_id'];
                        $grnEntry->order_type     = $value['order_type'];
                        $grnEntry->order_qty      = $value['order_qty'];
                        $grnEntry->rec_g_qty      = $value['rec_g_qty'];
                        $grnEntry->rec_b_qty      = $value['rec_b_qty'];
                        $grnEntry->grn_item_price = $value['grn_price'];
                        $grnEntry->discount       = $value['discount'];
                        $grnEntry->manif_date     = $value['manif_date'];
                        $grnEntry->ex_date        = $value['ex_date'];
                        $grnEntry->updated_at     = null;
                        $grnEntry->save();
                    }
                }
           }
           else
           {
                $grn_id_for_stock       = $update_id;

                $grn                    = grn::find($update_id);
                $grn->purchase_order_id = $request->purchase_order_id;
                $grn->grn_date          = $request->grn_date;
                $grn->grn_amount        = $request->grn_amount;
                $grn->remarks           = $request->remarks;
                $grn->grn_id            = $request->grn_id;
                $grn->save();

                grnEntry::where('grn_id',$update_id)->delete();

                if(isset($grn_item) && !empty($grn_item))
                {
                    foreach ($grn_item as $value) 
                    {
                        $grnEntry                 = new grnEntry();
                        $grnEntry->grn_id         = $update_id;
                        $grnEntry->item_id        = $value['item_id'];
                        $grnEntry->order_type     = $value['order_type'];
                        $grnEntry->order_qty      = $value['order_qty'];
                        $grnEntry->rec_g_qty      = $value['rec_g_qty'];
                        $grnEntry->rec_b_qty      = $value['rec_b_qty'];
                        $grnEntry->grn_item_price = $value['grn_price'];
                        $grnEntry->discount       = $value['discount'];
                        $grnEntry->manif_date     = $value['manif_date'];
                        $grnEntry->ex_date        = $value['ex_date'];
                        $grnEntry->save();
                    }
                }
            }

            $this->stock_control($grn_item,$grn_id_for_stock);

            $response = array('status' =>'Record is successfully added','id'=>$grn_id_for_stock);
            return response()->json($response);
        }
    }

    public function stock_control($grn_item,$grn_id_for_stock)
    {
        $stock = DB::table('stocks')
        ->select('*')
        ->where('stocks.id','=',$grn_id_for_stock)
        ->where('stocks.transaction_name','=',"GRN")
        ->get();

        if($stock->isEmpty())
        {
            if(isset($grn_item) && !empty($grn_item))
            {
                foreach ($grn_item as $value) 
                {
                    if($value['rec_g_qty'] > 0)
                    {
                        $stock                   = new stock(); 
                        $stock->item_id          = $value['item_id'];
                        $stock->transaction_name = "GRN";
                        $stock->transaction_ID   = $grn_id_for_stock;
                        $stock->stock_type       = "G";
                        $stock->order_type       = $value['order_type'];
                        $stock->stock_qty        = $value['rec_g_qty'];
                        $stock->item_price       = $value['grn_price'];
                        $stock->Manufacture_date = $value['manif_date'];
                        $stock->expiration_date  = $value['ex_date'];
                        $stock->updated_at       = null;
                        $stock->save();
                    }

                    if($value['rec_b_qty'] > 0)
                    {
                        $stock                   = new stock(); 
                        $stock->item_id          = $value['item_id'];
                        $stock->transaction_name = "GRN";
                        $stock->transaction_ID   = $grn_id_for_stock;
                        $stock->stock_type       = "B";
                        $stock->order_type       = $value['order_type'];
                        $stock->stock_qty        = $value['rec_b_qty'];
                        $stock->item_price       = $value['grn_price'];
                        $stock->Manufacture_date = $value['manif_date'];
                        $stock->expiration_date  = $value['ex_date'];
                        $stock->updated_at       = null;
                        $stock->save();
                    }
                }
            }
        }
        else
        {
            stock::where('stocks.transaction_ID','=',$grn_id_for_stock)->where('stocks.transaction_name','=',"GRN")->delete();

            if(isset($grn_item) && !empty($grn_item))
            {
                foreach ($grn_item as $value) 
                {
                    if($value['rec_g_qty'] > 0)
                    {
                        $stock                   = new stock(); 
                        $stock->item_id          = $value['item_id'];
                        $stock->transaction_name = "GRN";
                        $stock->transaction_ID   = $grn_id_for_stock;
                        $stock->stock_type       = "G";
                        $stock->order_type       = $value['order_type'];
                        $stock->stock_qty        = $value['rec_g_qty'];
                        $stock->item_price       = $value['grn_price'];
                        $stock->Manufacture_date = $value['manif_date'];
                        $stock->expiration_date  = $value['ex_date'];
                        $stock->updated_at       = null;
                        $stock->save();
                    }

                    if($value['rec_b_qty'] > 0)
                    {
                        $stock                   = new stock(); 
                        $stock->item_id          = $value['item_id'];
                        $stock->transaction_name = "GRN";
                        $stock->transaction_ID   = $grn_id_for_stock;
                        $stock->stock_type       = "B";
                        $stock->order_type       = $value['order_type'];
                        $stock->stock_qty        = $value['rec_b_qty'];
                        $stock->item_price       = $value['grn_price'];
                        $stock->Manufacture_date = $value['manif_date'];
                        $stock->expiration_date  = $value['ex_date'];
                        $stock->updated_at       = null;
                        $stock->save();
                    }
                }
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
        $data        = array();

        $data['grn'] = DB::table('grns')
        ->select(
            'grns.id as id',
            'grns.purchase_order_id as purchase_order_id',
            'grns.grn_date as grn_date',
            'grns.grn_amount as grn_amount',
            'grns.grn_id as grn_id',
            'grns.remarks as remarks',
            'purchase_orders.order_date as order_date',
            'purchase_orders.order_id as order_id',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('purchase_orders','purchase_orders.id','=','grns.purchase_order_id')
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->where('grns.id','=',$id)
        ->first();

        $grn_entries = DB::table('grn_entries')
        ->select(
            'grn_entries.rec_g_qty as rec_g_qty',
            'grn_entries.rec_b_qty as rec_b_qty',
            'grn_entries.grn_item_price as grn_item_price',
            'grn_entries.discount as discount', 
            'grn_entries.item_id as item_id', 
            'grn_entries.order_type as order_type', 
            'grn_entries.order_qty as order_qty', 
            'grn_entries.manif_date as manif_date', 
            'grn_entries.ex_date as ex_date', 
            'items.item_name as item_name'
        )
        ->join('items','items.id','=','grn_entries.item_id')
        ->where('grn_entries.grn_id','=',$id)
        ->get();

        foreach ($grn_entries as $key => $grn_entry)
        {
            $total_qty                  = $grn_entry->rec_g_qty+$grn_entry->rec_b_qty;
            $total_grn_amount           = $total_qty*$grn_entry->grn_item_price;
            $discount_amount            = ($total_grn_amount*$grn_entry->discount)/100;
            $grand_grn_amount           = $total_grn_amount - $discount_amount;
            $grn_entry->amount          = $grand_grn_amount;
            $grn_entry->order_type_name = ($grn_entry->order_type =="C" ? 'Case' : 'Unit');
        }

        $data['grn_entries'] = $grn_entries;

        return view('grn.newGrnCreate')->with('data',$data);
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
