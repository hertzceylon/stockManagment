@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>GRN(Good Recived Note)</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All GRN )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/grn/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> New GRN</a>
            </div>
         </div>
        </div>
        <div class="panel-body">
          <table id="grn_table" class="table table-striped table-hover table-center" cellspacing="0">
            <thead>
              <tr>
                <th class="text-center" style="border: 1px solid #dddddd;">GRN Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">GRN ID</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Purchase Order ID</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Purchase Order Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">GRN Amount</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($grns))
                @foreach($grns as $grn)
                  <tr>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->grn_date}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->grn_id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->order_id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->order_date}}</td>
                    <td class="text-right" style="border: 1px solid #dddddd;">{{number_format($grn->grn_amount,2)}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->created_at}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->updated_at}}</td>
                    <form action="/grn/{{$grn->id}}" class="pull-right" method="POST">
                       {{ csrf_field() }}
                       <td class="text-center" style="border: 1px solid #dddddd;" width="100px">
                        <a class="btn btn-warning btn-xs" id='{{$grn->id}}' href="grn/{{$grn->id}}">Edit</a>
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
  
var grn_table = 0;

$(document).ready(function()
{
  grn_table = $('#grn_table').DataTable({
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
      title:'GRN'
    }]
  });
});
</script>
@endsection