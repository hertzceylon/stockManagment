@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Sales Invoices</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All Sales Invoice )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/sales_invoice/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> New Sale Invoice</a>
            </div>
         </div>
        </div>
        <div class="panel-body">
          <table id="sales_invoice_table" class="table table-striped table-hover table-center" cellspacing="0">
            <thead>
              <tr>
                <th class="text-center" style="border: 1px solid #dddddd;">Invoice ID</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Invoice Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Discount</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Invoice Amount</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($data['salesInvoices']))
                @foreach($data['salesInvoices'] as $salesInvoice)
                  <tr>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$salesInvoice->invoice_id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$salesInvoice->invoice_date}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$salesInvoice->discount}}</td>
                    <td class="text-right" style="border: 1px solid #dddddd;">{{number_format($salesInvoice->invoice_amount,2)}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$salesInvoice->created_at}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$salesInvoice->updated_at}}</td>
                    <form action="/sales_invoice/{{$salesInvoice->id}}" class="pull-right" method="POST">
                       {{ csrf_field() }}
                       <td class="text-center" style="border: 1px solid #dddddd;" width="100px">
                        <a class="btn btn-info btn-xs" id='{{$salesInvoice->id}}' href="sales_invoice/{{$salesInvoice->id}}">View</a>
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
var sales_invoice_table = 0;

$(document).ready(function()
{
  sales_invoice_table = $('#sales_invoice_table').DataTable({
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