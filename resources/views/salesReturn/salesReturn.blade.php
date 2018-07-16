@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Sales Returns</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All Sales Return )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/sales_return/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> New Sales Return</a>
            </div>           
         </div>
        </div>
        <div class="panel-body">
            <table id="sales_return_table" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Sales Return ID</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Sales Return Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Sales Return Amount</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                </tr>
              </thead>
              <tbody>
                @if($salesReturns)
                  @foreach($salesReturns as $salesReturn)
                    <tr>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturn->return_id}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturn->return_date}}</td>
                      <td class="text-right" style="border: 1px solid #dddddd;">{{number_format($salesReturn->return_amount,2)}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturn->created_at}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturn->updated_at}}</td>
                      <form action="/sales_return/{{$salesReturn->id}}" class="pull-right" method="POST">
                         {{ csrf_field() }}
                         <td class="text-center" style="border: 1px solid #dddddd;" width="100px">
                          <a class="btn btn-warning btn-xs" id='{{$salesReturn->id}}' href="sales_return/{{$salesReturn->id}}">Edit</a>
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
var sales_return_table = 0;

$(document).ready(function()
{
  sales_return_table = $('#sales_return_table').DataTable( {
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
        title:'Sales Returns'
      }]
    });
});
</script>
@endsection