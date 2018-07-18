@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
              <div style="text-align: center;"><h3><b> GRN (Good Recived Note) Report</b></h3></div>
         </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">

              <form  id='grn_report_form' action="/grn_report" method="post" class="form-horizontal">
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

          <table id="grn_report_table" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Purchase Order Code</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Purchase Order Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Supplier </th>
                  <th class="text-center" style="border: 1px solid #dddddd;">GRN Date</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">GRN Code </th>
                  <th class="text-center" style="border: 1px solid #dddddd;">GRN Amount</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($data['grns']))
                  @foreach($data['grns'] as $grn)
                    <tr>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->order_id}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->order_date}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->supplier_name}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->grn_date}}</td>
                      <td class="text-center" style="border: 1px solid #dddddd;">{{$grn->grn_code}}</td>
                      <td class="text-right" style="border: 1px solid #dddddd;">{{number_format($grn->grn_amount,2)}}</td>
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
var grn_report_table = 0;

$(document).ready(function()
{
  $('.selectpicker').selectpicker();

  grn_report_table = $('#grn_report_table').DataTable({
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
      title:'GRN (Good Recived Note) Report'
    }]
  });
});

</script>
@endsection