@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Purchase Orders</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All Purchase Order )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/purchase_order/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> New Purchase Order</a>
            </div>           
         </div>
        </div>
        <div class="panel-body">
            <table id="purchase_order_table" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Order ID</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Order Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Suppier Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($purchaseOrders))
                  @foreach ($purchaseOrders as $purchaseOrder)
                  <tr>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->order_id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->order_date}}</td>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$purchaseOrder->supplier_name}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->created_at}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->updated_at}}</td>
                    <form action="/purchase_order/{{$purchaseOrder->id}}" class="pull-right" method="POST">
                       {{ csrf_field() }}
                       <td class="text-center" style="border: 1px solid #dddddd;" width="100px">
                        <a class="btn btn-warning btn-xs" id='{{$purchaseOrder->id}}' href="purchase_order/{{$purchaseOrder->id}}">Edit</a>
                        <input type="submit" name="delete" value="Remove" class="btn btn-danger btn-xs">
                        <input type="hidden" name="_method" value="DELETE">
                       </td>
                    </form>
                  </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
var purchase_order_table = 0;

$(document).ready(function()
{
  purchase_order_table = $('#purchase_order_table').DataTable( {
      scrollY       :'400px',
      scrollX       : true,
      scrollCollapse: true,
      paging        : false,
      searching     : true,
      ordering      : false,
      info          : false,
      dom: 'Bfrtip',
      buttons: [{
        extend: 'excel',
        pageSize: 'a4',
        title:'Purchase Orders'
      }]
    });
});
</script>
@endsection