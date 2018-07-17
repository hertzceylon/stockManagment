<?php

namespace App\Http\Controllers;

use App\purchaseOrder;
use App\prchaseOrderEntry;
use App\item;
use App\supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class purchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        ->OrderBy('purchase_orders.created_at','desc')
        ->get();

        return view("purchaseOrder.purchaseOrders")->with('purchaseOrders',$purchaseOrders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data              = array();
        $data['suppliers'] = supplier::OrderBy('created_at','asc')->get();

        $lastPOrder = purchaseOrder::orderBy('created_at', 'desc')->first();

        if(! $lastPOrder)
        {
            $number = 0;
        }
        else
        {
            $number = substr($lastPOrder->order_id, 3);
        }

        $data['code'] = 'PO' . sprintf('%06d', intval($number) + 1);

        $data['items']     = DB::table('items')
                            ->select(
                                'items.id as id',
                                'items.item_name as item_name',
                                'item_order_types.order_type_name as order_type_name',
                                'item_order_types.id as order_type_id'
                            )
                            ->join('item_order_types','item_order_types.id','=','items.order_type_id')
                            ->OrderBy('items.created_at','asc')
                            ->get();

        return view('purchaseOrder.purchaseOrderCreate')->with('data',$data);
    }

    public function fetch_supplier_items(Request $request)
    {
        $supplier_id = $request->input('supplier_id');

        $supplier_item = DB::table('supply_items')
        ->select(
            'items.id as id',
            'items.item_name as item_name',
            'item_order_types.order_type_name as order_type_name',
            'item_order_types.id as order_type_id'
        )
        ->join('items','items.id','=','supply_items.item_id')
        ->join('item_order_types','item_order_types.id','=','items.order_type_id')
        ->where('supply_items.sup_id','=',$supplier_id)
        ->get();

        return $supplier_item;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
            
        ]);

        $validator = \Validator::make($request->all(), [
            'order_date'  => 'required',
            'supplier_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
            if($request->update_id == null)
            {
                $purchaseOrder              = new purchaseOrder();
                $purchaseOrder->order_date  = $request->order_date;
                $purchaseOrder->supplier_id = $request->supplier_id;
                $purchaseOrder->order_id    = $request->order_id;
                $purchaseOrder->remarks     = $request->remarks;
                $purchaseOrder->updated_at  = null;
                $purchaseOrder->save();
                $insertedId                 = $purchaseOrder->id;

                $order_item = $request->order_item;

                if(isset($order_item) && !empty($order_item))
                {
                    foreach ($order_item as $value) 
                    {
                        $prchaseOrderEntry                    = new prchaseOrderEntry();
                        $prchaseOrderEntry->purchase_order_id = $insertedId;
                        $prchaseOrderEntry->item_id           = $value['item_id'];
                        $prchaseOrderEntry->order_type        = $value['order_type'];
                        $prchaseOrderEntry->order_qty         = $value['order_qty'];
                        $prchaseOrderEntry->updated_at        = null;
                        $prchaseOrderEntry->save();
                    }
                }
            }
            else
            {
                $update_id = $request->update_id;

                $purchaseOrder              = purchaseOrder::find($update_id);
                $purchaseOrder->order_date  = $request->order_date;
                $purchaseOrder->supplier_id = $request->supplier_id;
                $purchaseOrder->order_id    = $request->order_id;
                $purchaseOrder->remarks     = $request->remarks;
                $purchaseOrder->save();

                prchaseOrderEntry::where('purchase_order_id',$update_id)->delete();
                $order_item = $request->order_item;

                if(isset($order_item))
                {
                    foreach ($order_item as $value) 
                    {
                        $prchaseOrderEntry                    = new prchaseOrderEntry();
                        $prchaseOrderEntry->purchase_order_id = $update_id;
                        $prchaseOrderEntry->item_id           = $value['item_id'];
                        $prchaseOrderEntry->order_type        = $value['order_type'];
                        $prchaseOrderEntry->order_qty         = $value['order_qty'];
                        $prchaseOrderEntry->save();
                    }
                }
            }

            $response = array('status' =>'Record is successfully added');
            return response()->json($response);
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

        $data['purchaseOrder']     = purchaseOrder::findOrFail($id);

        $data['prchaseOrderEntry'] = DB::table('prchase_order_entries')
        ->select(
            'prchase_order_entries.order_type as order_type',
            'prchase_order_entries.order_qty as order_qty',
            'prchase_order_entries.item_id as item_id',
            'items.item_name as item_name',
            'item_order_types.order_type_name as order_type_name',
            'item_order_types.id as order_type_id'
        )
        ->join('items','items.id','=','prchase_order_entries.item_id')
        ->join('item_order_types','item_order_types.id','=','items.order_type_id')
        ->where('prchase_order_entries.purchase_order_id','=',$id)
        ->get();

        $data['suppliers']         = supplier::OrderBy('created_at','asc')->get();
        $data['items']     = DB::table('items')
                            ->select(
                                'items.id as id',
                                'items.item_name as item_name',
                                'item_order_types.order_type_name as order_type_name',
                                'item_order_types.id as order_type_id'
                            )
                            ->join('item_order_types','item_order_types.id','=','items.order_type_id')
                            ->OrderBy('items.created_at','asc')
                            ->get();

        return view('purchaseOrder.purchaseOrderCreate')->with('data',$data);

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
        purchaseOrder::where('id',$id)->delete();
        prchaseOrderEntry::where('purchase_order_id',$id)->delete();
        return redirect('/purchase_order')->with('status_delete','Purchase Order Deleted !');
    }

    public function print_purchase_order($print_id)
    {
        $data = array();

        // $data['purchaseOrder']     = purchaseOrder::findOrFail($print_id);
        $data['purchaseOrder']     = DB::table('purchase_orders')
        ->select(
            'purchase_orders.id as id',
            'purchase_orders.order_date as order_date',
            'purchase_orders.supplier_id as supplier_id',
            'purchase_orders.order_id as order_id',
            'purchase_orders.remarks as remarks',
            'suppliers.supplier_name as supplier_name'
        )
        ->join('suppliers','suppliers.id','=','purchase_orders.supplier_id')
        ->where('purchase_orders.id',$print_id)
        ->first();

        $data['prchase_order_entries'] = DB::table('prchase_order_entries')
        ->select(
            'prchase_order_entries.order_type as order_type',
            'prchase_order_entries.order_qty as order_qty',
            'prchase_order_entries.item_id as item_id',
            'items.item_name as item_name',
            'item_order_types.order_type_name as order_type_name',
            'item_order_types.id as order_type_id'
        )
        ->join('items','items.id','=','prchase_order_entries.item_id')
        ->join('item_order_types','item_order_types.id','=','items.order_type_id')
        ->where('prchase_order_entries.purchase_order_id','=',$print_id)
        ->get();

        return view('printouts.purchaseOrderPrint')->with('data',$data);
    }
}
