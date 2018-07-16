@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Item</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All items )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/item/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> New Item</a>
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

          <table id="item_table" class="table table-striped table-hover table-center" cellspacing="0">
            <thead>
              <tr>
                <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Bar Code</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Category Name</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Sub Category Name</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Availability</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($items))
                @foreach ($items as $item)
                <tr>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$item->item_name}}</td>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$item->bar_code}}</td>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$item->cate_name}}</td>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$item->sub_cate_name}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{$item->created_at}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{$item->updated_at}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{($item->status == 1 ? "Yes" : "No")}}</td>
                  
                  <form action="/item/{{$item->id}}" class="pull-right" method="POST">
                     {{ csrf_field() }}
                     <td class="text-center" style="border: 1px solid #dddddd;">
                      <a class="btn btn-warning" id='{{$item->id}}' href="item/{{$item->id}}">Edit</a>
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
var item_table = 0;

$(document).ready(function()
{
  item_table = $('#item_table').DataTable( {
      scrollY       :'400px',
      scrollX       : true,
      scrollCollapse: true,
      paging        : false,
      searching     : true,
      ordering      : false,
      info          : false,
      dom: 'Bfrtip',
      buttons: [{
        extend: 'pdfHtml5',
        pageSize: 'a4',
        title:'item'
      },
      {
        extend: 'excelHtml5',
        pageSize: 'a4',
        title:'item'
      },
      ]
    });
});
</script>
@endsection