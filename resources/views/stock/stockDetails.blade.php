@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Stock</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All Item Stock )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/stock/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> Add New Stock</a>
            </div>           
         </div>
        </div>
        <div class="panel-body">
          @if(session('status'))
            <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
            </div>
          @elseif(session('status_delete'))
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status_delete') }}
            </div>
          @endif

            <table id="stock_details" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Stock Type</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Order Type</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Available QTY </th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Item Price </th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Manufacture Date </th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Expiration Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Transaction Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($data))
                  @foreach($data['stocks'] as $stock)
                  <tr>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->item_name}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{($stock->stock_type == "G" ? "Good" : "Bad")}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{($stock->order_type == "C" ? "Case" : "Unit")}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->stock_qty}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->item_price}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->Manufacture_date}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->expiration_date}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->transaction_name}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->created_at}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$stock->updated_at}}</td>
                    <form action="/stock/{{$stock->id}}" class="pull-right" method="POST">
                       {{ csrf_field() }}
                       <td class="text-center" style="border: 1px solid #dddddd;" width="100px">
                        <a class="btn btn-warning btn-xs" id='{{$stock->id}}' href="stock/{{$stock->id}}">Edit</a>
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
var stock_details = 0;

$(document).ready(function()
{
  stock_details = $('#stock_details').DataTable( {
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
        title:'Stock'
      }]
    });
});
</script>
@endsection