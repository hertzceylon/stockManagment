@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
              <div style="text-align: center;"><h3><b>Purchase Order Report</b></h3></div>
         </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">

              <form  id='purchase_order_form' action="/purchase_order_report" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="post">
                <div class="col-sm-12">

                    <div class="form-group">

                      <div class="col-sm-3">
                        <select class="selectpicker" data-live-search="true" name="supplier_id" id="supplier_id">
                          <option value="">All Suppliers </option>
                          @if(isset($data['suppliers']))
                            @foreach ($data['suppliers'] as $supplier)
                              <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                            @endforeach
                          @endif
                        </select>
                      </div>

                      <div class="col-sm-3">
                        <input class="datepicker form-control" type="text" name="from_date" id="from_date" placeholder="From Date" value="<?php echo date('Y-m-01');?>">
                      </div>

                      <div class="col-sm-3">
                        <input class="datepicker form-control" type="text" name="to_date" id="to_date" placeholder="To Date" value="<?php echo date('Y-m-d');?>">
                      </div>

                      <div class="col-sm-3">
                        <button type="submit" class="btn btn-info">Filter</button>
                      </div>

                    </div>
                </div>
              </form>

            </div>
          </div>

          <table id="purchase_order_report_table" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Order ID </th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Order Code</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Order Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Supplier </th>
                </tr>
              </thead>
              <tbody>
               @if(isset($data['purchaseOrders']))
                  @foreach($data['purchaseOrders'] as $purchaseOrder)
                  <tr>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->order_id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->order_date}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseOrder->supplier_name}}</td>
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
var purchase_order_report_table = 0;

$(document).ready(function()
{
  $('.selectpicker').selectpicker();

  purchase_order_report_table = $('#purchase_order_report_table').DataTable({
    scrollY       :'400px',
    scrollX       : true,
    scrollCollapse: true,
    paging        : false,
    searching     : true,
    ordering      : true,
    info          : false,
    dom: 'Bfrtip',
    buttons: [{
      extend: 'excel',
      pageSize: 'a4',
      title:'Purchase Order Report'
    }]
  });
});

</script>
@endsection