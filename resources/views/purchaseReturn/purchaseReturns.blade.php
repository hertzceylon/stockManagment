@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Purchase Returns</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All Purchase Return )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/purchase_return/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> New Purchase Return</a>
            </div>           
         </div>
        </div>
        <div class="panel-body">
            <table id="purchase_return_table" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Return ID</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Return Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Suppier Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Return Amount</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(($purchaseReturns))
                  @foreach($purchaseReturns as $purchaseReturn)
                  <tr>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturn->return_id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturn->return_date}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturn->supplier_name}}</td>
                    <td class="text-right" style="border: 1px solid #dddddd;">{{number_format($purchaseReturn->return_amount,2)}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturn->created_at}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturn->updated_at}}</td>
                    <form action="/purchase_return/{{$purchaseReturn->id}}" class="pull-right" method="POST">
                       {{ csrf_field() }}
                       <td class="text-center" style="border: 1px solid #dddddd;" width="100px">
                        <a class="btn btn-warning btn-xs" id='{{$purchaseReturn->id}}' href="purchase_return/{{$purchaseReturn->id}}">Edit</a>
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
var purchase_return_table = 0;

$(document).ready(function()
{
  purchase_return_table = $('#purchase_return_table').DataTable( {
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
        title:'Purchase Returns'
      }]
    });
});
</script>
@endsection