@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
              <div style="text-align: center;"><h3><b>Sales Return Report</b></h3></div>
         </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">

              <form  id='sales_return_form' action="/sales_return_report" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="post">
                <div class="col-sm-12">

                    <div class="form-group">
                      <div class="col-sm-3">
                        <input class="datepicker form-control" type="text" name="from_date" id="from_date" placeholder="From Date" value="<?php echo date('Y-m-d');?>">
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

          <table id="sales_return_report_table" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Sales Return ID </th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Sales Return Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Sales Return Amount </th>
                </tr>
              </thead>
              <tbody>
                @if(isset($data['sales_returns']))
                  @foreach($data['sales_returns'] as $sales_return)
                  <tr>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$sales_return->return_id}}</td>
                    <td class="text-center" style="border: 1px solid #dddddd;">{{$sales_return->return_date}}</td>
                    <td class="text-right" style="border: 1px solid #dddddd;">{{number_format($sales_return->return_amount,2)}}</td>
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
var sales_return_report_table = 0;
$(document).ready(function()
{
  $('.selectpicker').selectpicker();

  sales_return_report_table = $('#sales_return_report_table').DataTable({
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
      title:'Sales Return Report'
    }]
  });
});

</script>
@endsection