@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Suppliers</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All Suppliers )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/supplier/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> New Supplier</a>
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

          <table id="supplier_table" class="table table-striped table-hover table-center" cellspacing="0">
            <thead>
              <tr>
                <th class="text-center" style="border: 1px solid #dddddd;">Supplier Name</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Address </th>
                <th class="text-center" style="border: 1px solid #dddddd;">Contact Number</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Contact Person</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Email</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Status</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Item Add</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($suppliers))
                @foreach ($suppliers as $supplier)
                  <tr>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$supplier->supplier_name}}</td>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$supplier->supplier_address}}</td>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$supplier->contact_number_1}}</td>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$supplier->contact_person}}</td>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$supplier->email}}</td>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$supplier->created_at}}</td>
                    <td class="text-left" style="border: 1px solid #dddddd;">{{$supplier->updated_at }}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{($supplier->status == 1 ? "Active" : "Deactive")}}</td>
                    <form action="/supplier/{{$supplier->id}}" class="pull-right" method="POST">
                       {{ csrf_field() }}
                       <td class="text-center" style="border: 1px solid #dddddd;">
                        <a class="btn btn-warning" id='{{$supplier->id}}' href="supplier/{{$supplier->id}}">Edit</a>
                       </td>
                    </form>

                    <form action="/supplier/add_supply_item/{{$supplier->id}}" class="pull-right" method="POST">
                       {{ csrf_field() }}
                       <td class="text-center" style="border: 1px solid #dddddd;">
                        <a class="btn btn-warning" id='{{$supplier->id}}' href="/supplier/add_supply_item/{{$supplier->id}}">Add Supply Item</a>
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
var supplier_table = 0;

$(document).ready(function()
{
  supplier_table = $('#supplier_table').DataTable( {
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
        title:'Suppliers'
      }]
    });
});
</script>
@endsection