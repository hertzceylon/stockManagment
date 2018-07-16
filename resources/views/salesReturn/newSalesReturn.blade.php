@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Sales Return </b></h3></div>

      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-8">
              <small> Create  / Update  Sales Return </small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/sales_return" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          <div class="alert alert-danger" style="display:none"></div>
          <form  id='sales_return_form' method="post" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <div class="row">
              <br><div class="col-sm-2"></div>

              <div class="col-sm-8">

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Sales Return ID</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesReturn']))
                      <input type="text" class="form-control" name="sales_return_id" id="sales_return_id" placeholder="Enter Sales Return ID" value="{{$data['salesReturn']->return_id}}" readonly>
                    @else
                      <input type="text" class="form-control" name="sales_return_id" id="sales_return_id" placeholder="Enter Sales Return ID" value="{{$data['code']}}" readonly>
                    @endif
                  </div>
                </div>


                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Sales Return Date</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesReturn']))
                      <input class="datepicker form-control" type="text" id="sales_return_date"  name ="sales_return_date" width="270" placeholder="Enter sales Return Date" value="{{$data['salesReturn']->return_date}}"></input>
                    @else
                      <input class="datepicker form-control" type="text" id="sales_return_date"  name ="sales_return_date" width="270" placeholder="Enter sales Return Date" value="<?php echo date('Y-m-d'); ?>"></input>
                    @endif
                    
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Sales Invoice</label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="sales_invoice_id" id="sales_invoice_id" width="270">
                      <option value="">Select Sales Invoice</option>
                      @if(isset($data['salesReturn']))
                        @if(isset($data['salesInvoices']))
                            @foreach ($data['salesInvoices'] as $salesInvoice)
                              @if($salesInvoice->id== $data['salesReturn']->sales_invoice_id)
                                <option value="{{$salesInvoice->id}}" selected>{{$salesInvoice->invoice_id}} | {{$salesInvoice->invoice_date}}</option>
                              @else
                                <option value="{{$salesInvoice->id}}">{{$salesInvoice->invoice_id}} | {{$salesInvoice->invoice_date}}</option>
                              @endif
                            @endforeach
                          @endif
                      @else
                        @if(isset($data['salesInvoices']))
                          @foreach ($data['salesInvoices'] as $salesInvoice)
                            <option value="{{$salesInvoice->id}}">{{$salesInvoice->invoice_id}} | {{$salesInvoice->invoice_date}}</option>
                          @endforeach
                        @endif
                      @endif
                    </select>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Sales Return Amount</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesReturn']))
                      <input type="text" class="form-control" name="sales_return_amount" id="sales_return_amount" placeholder="Sales Return Amount" value="{{$data['salesReturn']->return_amount}}" readonly>
                    @else
                      <input type="text" class="form-control" name="sales_return_amount" id="sales_return_amount" placeholder="Sales Return Amount" readonly>
                    @endif
                    
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesReturn']))
                      <textarea type="text" class="form-control" id ="remarks" name="remarks">{{$data['salesReturn']->remarks}}</textarea>
                    @else
                      <textarea type="text" class="form-control" id ="remarks" name="remarks"></textarea>
                    @endif
                  </div>
                </div>
              </div>

              <div class="row">
                <br>
                <div class="col-sm-12">
                  <p hidden id="error"><span id="table_error" class="text-danger"><b></b></span></p>
                  <table id="sales_return_item_table" class="table table-striped table-hover table-center" cellspacing="0">
                    <thead>
                      <tr>
                       <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                       <th class="text-center" style="border: 1px solid #dddddd;">Price</th>
                       <th class="text-center" style="border: 1px solid #dddddd;">Return Type</th>
                       <th class="text-center" style="border: 1px solid #dddddd;">Return QTY</th>
                       <th class="text-center" style="border: 1px solid #dddddd;">Return Amount</th>
                       <th class="text-center" style="border: 1px solid #dddddd;">Item Remark</th>
                       <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr id="sales_return_item_table_opction_select">
                        <th class="text-center" style="border: 1px solid #dddddd;">
                          <select class="selectpicker" data-live-search="true" name="item_id" id="item_id" width="270">
                            <option value="">Select Item</option>
                            @if(isset($data['items']))
                              @foreach ($data['items'] as $item)
                                <option value="{{$item->id}}" data-type="{{$item->item_price}}">{{$item->item_name}}</option>
                              @endforeach
                            @endif
                          </select>
                        </th>

                        <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="item_price" id="item_price" onkeypress="return numberOnly(event)" placeholder="Enter Item Price"></th>

                        <th class="text-center" style="border: 1px solid #dddddd;">
                          <select class="selectpicker" data-live-search="true" name="return_type_id" id="return_type_id" width="270">
                            <option value="">Select Return Type</option>
                            @if(isset($data['returnTypes']))
                              @foreach ($data['returnTypes'] as $returnType)
                                <option value="{{$returnType->id}}">{{$returnType->return_name}}</option>
                              @endforeach
                            @endif
                          </select>
                        </th>

                        <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="return_qty" id="return_qty" onkeypress="return numberOnly(event)" placeholder="Enter Return QTY"></th>

                        <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="return_amount_td" id="return_amount_td" onkeypress="return numberOnly(event)" placeholder="Return Amount" readonly></th>

                        <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="item_remark" id="item_remark" placeholder="Item Remark"></th>

                        <th class="text-center" style="border: 1px solid #dddddd;"><button class="btn btn-info" type="button" onclick="addReturnItem();">Add</button></th>
                      </tr>

                      @if(isset($data['salesReturnEntries']))
                        @foreach($data['salesReturnEntries'] as $salesReturnEntry)
                          <tr id="{{$salesReturnEntry->item_id}}">
                            <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->item_id}}</td>
                            <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->item_name}}</td>
                            <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->item_price}}</td>
                            <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->return_type_id}}</td>
                            <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->return_name}}</td>
                            <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->return_qty}}</td>
                            <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->amount}}</td>
                            <td class="text-center" style="border: 1px solid #dddddd;">{{$salesReturnEntry->item_remark}}</td>
                            <td class="text-center" style="border: 1px solid #dddddd;"><a onclick="removeReturnItem('{{$salesReturnEntry->item_id}}')" class="btn btn-danger btn-xs" type="button">Remove</a></td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <br><div class="row form-group" >
              <div class="col-sm-6"></div>
              <div class="col-sm-6" align="right">
                @if(isset($data['salesReturnEntries']))
                  <button type="button" onclick="saveSalesReturn('{{$data['salesReturn']->id}}');" class="btn btn-info">Create</button>
                @else
                  <button type="button" onclick="formValidate();" class="btn btn-info">Create</button>
                @endif

                <a href="/sales_return/create" class="btn btn-default ">Refresh</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">

var sales_return_item = {};
var update_id         = 0;

$(document).ready(function()
{
  $('#sales_return_form').formValidation({
    framework: 'bootstrap',
        fields: {
            sales_invoice_id: {
                validators: {
                    notEmpty: {
                        message: 'The Sales Invoice is required'
                    }
                }
            },
            sales_return_date: {
                validators: {
                  notEmpty: {
                        message: 'The Order Date is required'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'The value is not a valid date'
                    }
                }
            },
         }
    });

});

$('#sales_invoice_id').on('change',function ()
{
  $('#item_id').empty();
  var sales_invoice_id = $('#sales_invoice_id').val();
  fetchSalesInvoiceItem(sales_invoice_id);
});

$('#item_id').on('change',function ()
{
  calculation();
});

$('#return_qty , #item_price').on('keyup',function ()
{
  calculation();
});

function calculation()
{
  var item_price    = $('#item_id option:selected').attr('data-type');
  $('#item_price').val(item_price);

  var item_price = $('#item_price').val();
  var return_qty = $('#return_qty').val();

  $('#return_amount_td').val(item_price * return_qty);
}

function fetchSalesInvoiceItem(sales_invoice_id)
{
  var data = {sales_invoice_id:sales_invoice_id};
   $.ajax({
   method: 'GET',
   url: '/fetch_sales_invoice_item',
   data: data,
   success: function(sale_invoice_entries)
   { 
      if(sale_invoice_entries != null)
      {
        $('#item_id').append('<option value="">Select Item</option>');
        $.each(sale_invoice_entries, function (index, value) 
        {
          $('#item_id').append( '<option value="'+value['item_id']+'" data-type="'+value['item_price']+'" >'+value['item_name']+'</option>' );
        });
        $('.selectpicker').selectpicker('refresh');
      }
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log('comes error');
    }
  });
}

function addReturnItem()
{
  $('table_error').val('');

  var item_id          = $('#item_id option:selected').val();
  var item_name        = $('#item_id option:selected').text();
  var item_price       = $('#item_price').val();
  var return_type_id   = $('#return_type_id option:selected').val();
  var return_type_name = $('#return_type_id option:selected').text();
  var return_qty       = $('#return_qty').val();
  var return_amount    = $('#return_amount_td').val();
  var item_remark      = $('#item_remark').val();
  var returnContent    = null;
  var item_already     = null;
  $('#error').hide();

  $(sales_return_item).each(function(i, el)
  {
    if(el.item_id == item_id)
    {
      item_already ="set"; 
    }
  });
  
  if(item_id !='' && return_type_id !='' && return_qty != '')
  {
    if(item_already == null)
    {
      returnContent = '<tr id="'+item_id+'"><td hidden>'+item_id+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+item_name+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+item_price+'</td><td hidden>'+return_type_id+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+return_type_name+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+return_qty+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+return_amount+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+item_remark+'</td><td class="text-center" style="border: 1px solid #dddddd;"><a onclick="removeReturnItem('+item_id+')" class="btn btn-danger btn-xs" type="button">Remove</a></td></tr>';

      $("#sales_return_item_table tbody").append(returnContent);
      reset();
      createTableObject();
    }
    else
    {
    console.log('4');

      $('#error').show();
      $("#table_error").text("this item already in the table");
    }
  }
  else
  {
    $('#error').show();
    $("#table_error").text("Table entries can not be empty");
  }
}

function reset()
{
  $("#item_id").val('');
  $("#item_id").selectpicker("refresh");
  $("#return_type_id").val('');
  $("#return_type_id").selectpicker("refresh");

  $('#item_price').val('');
  $('#return_qty').val('');
  $('#return_amount_td').val('');
}

function removeReturnItem(remove_item_id)
{
  $("#"+remove_item_id).remove();
  createTableObject();
}

function createTableObject()
{
  var total_invoice_amount = 0;
  sales_return_item        = [];

  $('#sales_return_item_table > tbody  > tr:not(#sales_return_item_table_opction_select)').each(function(i, el)
  {
    var td = $(this).find('td');
    item   = {};

    item["item_id"]          = td.eq(0).text();
    item["item_name"]        = td.eq(1).text();
    item["item_price"]       = td.eq(2).text();
    item["return_type_id"]   = td.eq(3).text();
    item["return_type_name"] = td.eq(4).text();
    item["return_qty"]       = td.eq(5).text();
    item["return_amount"]    = td.eq(6).text();
    item["item_remark"]      = td.eq(7).text();
  
    sales_return_item.push(item);
    total_invoice_amount += parseFloat(td.eq(6).text());
 });

  $('#sales_return_amount').val(total_invoice_amount);
}

function formValidate()
{
  $('#sales_return_form').bootstrapValidator('validate');
  if ($('#sales_return_form').bootstrapValidator('isValid')) 
  {
    saveSalesReturn();
  }
}

function saveSalesReturn(update_id)
{
  update_id = update_id;

  if(update_id != null)
  {
    createTableObject();
  }  

  var data = {
    sales_return_date  : $('#sales_return_date').val(),
    sales_invoice_id   : $('#sales_invoice_id option:selected').val(),
    sales_return_id    : $('#sales_return_id').val(),
    sales_return_amount: $('#sales_return_amount').val(),
    remarks            : $('#remarks').val(),
    sales_return_item  : sales_return_item,
    update_id          : update_id,
    '_token'           : $('input[name=_token]').val(),
  };

  $.ajax({
    url: '/sales_return',
    type: "post",
    data: data,
    dataType: 'JSON',
    success: function (data) {

      if(data.status != undefined)
      {
        $('.alert-danger').show();
        $('.alert-danger').append('<p>'+data.status+'</p>');
          resetNewPage();
      }
    }
  });
}

function resetNewPage()
{
  update_id = null;

  $('sales_return_date').val('');
  $("#sales_invoice_id").val('');
  $("#sales_invoice_id").selectpicker("refresh");
  $('#sales_return_id').val('');
  $('#sales_return_amount').val('');
  $('#remarks').val('');
  sales_return_item = [];
  $('#sales_return_item_table > tbody  > tr:not(#sales_return_item_table_opction_select)').empty();

}
</script>
@endsection