@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
              <div style="text-align: center;"><h3><b>Stock Report</b></h3></div>
         </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">

              <form  id='stock_datils_form' action="/stock_report" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="post">
                <div class="col-sm-12">

                    <div class="form-group">

                      <div class="col-sm-3">
                        <select class="selectpicker" data-live-search="true" name="item_id" id="item_id">
                          <option value="">All Item </option>
                          @if(isset($data['items']))
                            @foreach ($data['items'] as $item)
                              <option value="{{$item->id}}">{{$item->item_name}}</option>
                            @endforeach
                          @endif
                        </select>
                      </div>

                      <div class="col-sm-3">
                        <input class="datepicker form-control" type="text" name="from_date" id="from_date" placeholder="From Date (Expired date range )" value="<?php echo date('Y-m-01');?>">
                      </div>

                      <div class="col-sm-3">
                        <input class="datepicker form-control" type="text" name="to_date" id="to_date" placeholder="To Date (Expired date range )" value="<?php echo date('Y-m-d');?>">
                      </div>

                      <div class="col-sm-3">
                        <button type="submit" class="btn btn-info">Filter</button>
                      </div>

                    </div>
                </div>
              </form>

            </div>
          </div>

          <table id="stock_report_table" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Item Status</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Available QTY</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($data['stocks']))
                  @foreach($data['stocks'] as $stock)
                   <tr>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$stock['item_name']}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$stock['status']}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$stock['stock_qty']}}</td>
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
var stock_report_table = 0;

$(document).ready(function()
{
  $('.selectpicker').selectpicker();

  stock_report_table = $('#stock_report_table').DataTable({
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
      title:'Stock Report'
    }]
  });
});

</script>
@endsection