@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Sales Invoice</b></h3></div>

      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-5">
              <small> Create  / Update / Delete Sales Invoice</small>
            </div>
            <div class="col-sm-7" align="right">
              @if(isset($data['salesInvoice']))
                <a href="/print_sales_invoice/{{$data['salesInvoice']->id}}" target="_blank" class="btn btn-default"><i class="fa fa-print" aria-hidden="true" onload="window.print();"></i> Print</a>
              @endif
                <a class="btn btn-default" style="display: none;" id="print_btn"><i class="fa fa-print" aria-hidden="true"></i> Print </a>
                <a href="/sales_invoice/create" class="btn btn-info" target="_blank"><i class="fa fa-plus" aria-hidden="true"></i> New Sale Invoice</a>
                <a href="/sales_invoice" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="alert alert-success" role="alert" style="display:none"></div>
          <form  id='sales_invoice_form' method="post" class="form-horizontal" onsubmit="return false;">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <div class="row">
              <br><div class="col-sm-2"></div>
              <div class="col-sm-8">

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Invoice ID</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <input type="text" class="form-control" name="invoice_id" id="invoice_id" placeholder="Enter Invoice ID" value="{{$data['salesInvoice']->invoice_id}}" readonly>
                    @else
                     <input type="text" class="form-control" name="invoice_id" id="invoice_id" placeholder="Enter Invoice ID" value="{{$data['code']}}" readonly>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Invoice Date</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <input class="datepicker form-control" type="text" id="invoice_date"  name ="invoice_date" width="270" placeholder="Enter Invoice Date" value="{{$data['salesInvoice']->invoice_date}}" readonly></input>
                    @else
                      <input class="datepicker form-control" type="text" id="invoice_date"  name ="invoice_date" width="270" placeholder="Enter Invoice Date" value="<?php echo date('Y-m-d'); ?>"></input>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <br>
              <div class="col-sm-12">
                <p hidden id="error"><span id="table_error" class="text-danger"><b></b></span></p>
                <table id="invoice_item_table" class="table table-striped table-hover table-center" cellspacing="0">
                  <thead>
                    <tr>
                     <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Price</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Available QTY</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">issue Type</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Invoice QTY</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Amount</th>
                      @if(!isset($data['saleInvoiceEntries']))
                        <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody id="invoice_item_table_body">
                    @if(!isset($data['saleInvoiceEntries']))
                    <tr id="invoice_item_table_opction_select">
                      <th class="text-center" style="border: 1px solid #dddddd;">
                        <select class="js-example-basic-single" name="item_id" id="item_id" style="width:100%">
                          <option value="">Select Item</option>
                            @foreach($data['items'] as $item)
                            <option value="{{$item->id}}" data-type ="{{$item->item_price}}" data-item_name ="{{$item->item_name}}" data-type_avb_qty="{{$item->stock_qty}}" data-order_type_name="{{$item->order_type_name}}" data-order_type_id="{{$item->order_type_id}}">{{$item->item_name}} | {{$item->bar_code}} | Rs : {{$item->item_price}} | {{$item->stock_qty}}</option>
                            @endforeach
                        </select>
                      </th>

                      <th class="text-center" style="border: 1px solid #dddddd;" id="item_price"></th>

                      <th class="text-center" style="border: 1px solid #dddddd;" name="avb_qty" id="avb_qty"></th>

                      <th hidden class="text-center" style="border: 1px solid #dddddd;" name="issue_type_id" id="issue_type_id"></th>

                      <th  class="text-center" style="border: 1px solid #dddddd;" name="issue_type_name" id="issue_type_name"></th>

                      <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="invoice_qty" id="invoice_qty" onkeypress="return numberOnly(event)" placeholder="Enter Invoice QTY"></th>

                      <th class="text-right" style="border: 1px solid #dddddd;" name="total_amount" id="total_amount"></th>

                      <th class="text-center" style="border: 1px solid #dddddd;"><button class="btn btn-info" type="button" onclick="addInvoiceItem();">Add</button></th>
                    </tr>
                    @endif

                    @if(isset($data['saleInvoiceEntries']))
                      @foreach($data['saleInvoiceEntries'] as $saleInvoiceEntry)
                        <tr id="{{$saleInvoiceEntry->item_id}}">
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->item_id}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->item_name}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->price}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->avb_qty}}</td>
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->order_type}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->order_type_name}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$saleInvoiceEntry->invoice_qty}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{($saleInvoiceEntry->invoice_qty) * $saleInvoiceEntry->price}}</td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="text-right" style="border: 1px solid #dddddd;" colspan="5">Total</th>
                      <th class="text-right" style="border: 1px solid #dddddd;" id="total_amount_footer"></th>
                      @if(!isset($data['saleInvoiceEntries']))
                        <th class="text-center" style="border: 1px solid #dddddd;"></th>
                      @endif
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="row">
              <br>
              <div class="col-sm-2"></div>
              <div class="col-sm-8">
                 <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Invoice Amount</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <input type="text" class="form-control text-right" name="invoice_amount" id="invoice_amount" placeholder="Invoice Amount" value="{{$data['salesInvoice']->invoice_amount}}" readonly>
                    @else
                     <input type="text" class="form-control text-right" name="invoice_amount" id="invoice_amount" placeholder="Invoice Amount" readonly>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Discount % </label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <input type="text" class="form-control" name="discount" id="discount" placeholder="Enter Discount %" onkeypress="return numberOnly(event)" value="{{$data['salesInvoice']->discount}}" readonly>
                    @else
                     <input type="text" class="form-control" name="discount" id="discount" placeholder="Enter Discount %" onkeypress="return numberOnly(event)" value="0">
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Discount Amount </label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <input type="text" class="form-control text-right" name="discount_amount" id="discount_amount" placeholder=" Discount Amount " onkeypress="return numberOnly(event)" value="{{$data['salesInvoice']->discount_amount}}" readonly>
                    @else
                     <input type="text" class="form-control text-right" name="discount_amount" id="discount_amount" placeholder=" Discount Amount " onkeypress="return numberOnly(event)" readonly>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Total Invoice Amount</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <input type="text" class="form-control text-right" name="total_invoice_amount" id="total_invoice_amount" placeholder="Invoice Amount" value="{{$data['salesInvoice']->total_invoice_amount}}" readonly>
                    @else
                     <input type="text" class="form-control text-right" name="total_invoice_amount" id="total_invoice_amount" placeholder="Invoice Amount" readonly>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Amount </label>
                  </div>
                  <div class="col-sm-6">
                     @if(isset($data['salesInvoice']))
                     <input type="text" class="form-control text-right" name="payment_amount" id="payment_amount" placeholder="Enter Amount" readonly value="{{$data['salesInvoice']->payment}}">
                      @else
                      <input type="text" class="form-control text-right" name="payment_amount" id="payment_amount" placeholder="Enter Amount">
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Balance Amount </label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <input type="text" class="form-control text-right" name="balance_amount" id="balance_amount" placeholder="Balance Amount" readonly value="{{$data['salesInvoice']->balance_payment}}">
                    @else
                      <input type="text" class="form-control text-right" name="balance_amount" id="balance_amount" placeholder="Balance Amount" readonly>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['salesInvoice']))
                      <textarea type="text" class="form-control" id ="remarks" name="remarks" placeholder="Remark" readonly>{{$data['salesInvoice']->remarks}}</textarea>
                    @else
                     <textarea type="text" class="form-control" id ="remarks" name="remarks" placeholder="Remark"></textarea>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <br><div class="row form-group" >
              <div class="col-sm-6"></div>
              <div class="col-sm-6" align="right">
                @if(isset($data['salesInvoice']))
                @else
                  <button type="button" onclick="formValidate();" class="btn btn-info">Create</button>
                @endif
                <a href="/sales_invoice/create" class="btn btn-default ">Refresh</a>
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

var invoice_item = {};
var update_id    = 0;
var print_id     = 0;

$(document).ready(function()
{
  $('#sales_invoice_form').formValidation({
    framework: 'bootstrap',
        fields: {
            invoice_id: {
                validators: {
                    notEmpty: {
                        message: 'The Invoice Id is required'
                    }
                }
            },
            invoice_date: {
                validators: {
                  notEmpty: {
                        message: 'The Invoice Date is required'
                    },
                    date: {
                        format: 'YYYY-MM-DD',
                        message: 'The value is not a valid date'
                    }
                }
            },
            payment_amount: {
                validators: {
                    notEmpty: {
                        message: 'The Payment Amount is required'
                    }
                }
            },
         }
    });
});

$(function(){
    $('#item_id').select2({
      placeholder: "Select Item"
    });

    $('#item_id').on('change', function()
    {
      var item_price    = $('#item_id option:selected').attr('data-type');
      var avb_qty       = $('#item_id option:selected').attr('data-type_avb_qty');
      var order_type_id = $('#item_id option:selected').attr('data-order_type_id');
      var order_type    = $('#item_id option:selected').attr('data-order_type_name');

      $('#item_price').text(item_price);
      $('#avb_qty').text(avb_qty);
      $('#issue_type_id').text(order_type_id);
      $('#issue_type_name').text(order_type);
    })
  });

$('#invoice_qty').on('keyup',function ()
{
  calculation();
});

$('#discount').on('keyup',function ()
{
  calculation();
});

$('#payment_amount').on('keyup',function ()
{
  calculation();
});

$('#invoice_item_table_body').on('keyup', '.invoice_qty', function()
{
  var invoice_qty   = parseFloat($(this).val());
  var tr_id         = $(this).closest("tr").attr("id");
  var avb_qty_class = $('#'+tr_id).find('.avb_qty_class').html();

  $('#table_error').val('');
  $('#error').hide();
  if(invoice_qty > avb_qty_class)
  {
    $('#error').show();
    $("#table_error").text("Invoice QTY can not be grater than the Available QTY.");
  }

  calculationTable();
  createTableObject();
});

function calculationTable()
{
  var invoice_qty        = 0;
  var item_price         = 0;
  var total_amount_class = 0;

  $('#invoice_item_table_body  > tr:not(#invoice_item_table_opction_select)').each(function(i, el)
  {
    var td          = $(this).find('td');

    invoice_qty     = parseFloat(($(this).find('.invoice_qty').val() =="" ? 0 : $(this).find('.invoice_qty').val()));
    item_price      = parseFloat(($(this).find('.item_price_class').text() == "" ? 0 : $(this).find('.item_price_class').text()));
    $(this).find('.total_amount_class').text(invoice_qty*item_price);
 });
}

function calculation()
{
  var invoice_qty          = $('#invoice_qty').val();
  var item_price           = $('#item_price').text();
  var invoice_amount       = $('#invoice_amount').val();
  var discount             = $('#discount').val();
  var payment_amount       = $('#payment_amount').val();

  $('#total_amount').text(invoice_qty * item_price);
  $('#discount_amount').val((invoice_amount * discount) /100);
  $('#total_invoice_amount').val((invoice_amount -(invoice_amount * discount) /100).toFixed(2));

  var total_invoice_amount = $('#total_invoice_amount').val();
  var balance_amount       = payment_amount - parseFloat(total_invoice_amount);

  $('#balance_amount').val(balance_amount.toFixed(2));
}

function addInvoiceItem()
{
  var item_id         = $('#item_id option:selected').val();
  var item_name       = $('#item_id option:selected').attr('data-item_name');
  var issue_type      = $('#issue_type_id').text();
  var issue_type_name = $('#issue_type_name').text();
  var invoice_qty     = $('#invoice_qty').val();
  var avb_qty         = parseFloat($('#avb_qty').text());
  var item_price      = $('#item_price').text();
  var total_amount    = $('#total_amount').text();
  var invoiceContent  = null;
  var g_total_amount  = 0;
  var item_already    = null;

  $('#table_error').val('');
  $('#error').hide();

  $(invoice_item).each(function(i, el)
  {
    if(el.item_id == item_id)
    {
      item_already ="set"; 
    }
  });

  if(item_already == null)
  {
    if(item_id!='' && invoice_qty!='')
    {
      if(invoice_qty < avb_qty || invoice_qty == avb_qty)
      {
        invoiceContent = '<tr id="'+item_id+'" ><td hidden>'+item_id+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+item_name+'</td><td class="text-center item_price_class" id="item_price" style="border: 1px solid #dddddd;">'+item_price+'</td><td class="text-center avb_qty_class" id="avb_qty" style="border: 1px solid #dddddd;">'+avb_qty+'</td><td hidden>'+issue_type+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+issue_type_name+'</td><td class="text-center invoice_qty_class" id="invoice_qty" style="border: 1px solid #dddddd;"><input type="text" class="form-control invoice_qty" onkeypress="return numberOnly(event)" value="'+invoice_qty+'"></td><td class="text-right total_amount_class" id="total_amount" style="border: 1px solid #dddddd;">'+Number(total_amount,2)+'</td><td class="text-center" style="border: 1px solid #dddddd;"><a onclick="removeInvoiceItem('+item_id+')" class="btn btn-danger btn-xs" type="button">Remove</a></td></tr>';

        $("#invoice_item_table tbody").append(invoiceContent);
        reset();
        createTableObject();
      }
      else
      {
        $('#error').show();
        $("#table_error").text("Invoice QTY can not be grater than the Available QTY.");
      }
    }
    else
    {
      $('#error').show();
      $("#table_error").text("Table Entries can not be empty");
    }
  }
  else
  {
    $('#error').show();
    $("#table_error").text("this item already in the table");
  }
}

function reset()
{
  $("#item_id").val(null).trigger("change");
  $('#item_id').select2('focus');
  $('#avb_qty').text('');
  $('#invoice_qty').val('');
  $('#item_price').text('');
  $('#issue_type_id').text('');
  $('#issue_type_name').text('');
  $('#total_amount').text('');
}

function createTableObject()
{
  var total_invoice_amount = 0;
  var discount             = $('#discount').val();
  invoice_item             = [];

  $('#invoice_item_table > tbody  > tr:not(#invoice_item_table_opction_select)').each(function(i, el)
  {
    var td = $(this).find('td');
    item   = {};

    item["item_id"]     = td.eq(0).text();
    item["item_name"]   = td.eq(1).text();
    item["item_price"]  = td.eq(2).text();
    item["order_type"]  = td.eq(4).text();
    item["invoice_qty"] = $(this).find('.invoice_qty').val();
  
    invoice_item.push(item);
    total_invoice_amount += parseFloat(td.eq(7).text());
 });

  $("#total_amount_footer").text(total_invoice_amount);

  $('#invoice_amount').val(total_invoice_amount);
  $('#total_invoice_amount').val(total_invoice_amount);

  $('#discount_amount').val((total_invoice_amount * discount) /100);
  $('#total_invoice_amount').val(total_invoice_amount -(total_invoice_amount * discount) /100);
}

function removeInvoiceItem(remove_item_id)
{
  $("#"+remove_item_id).remove();
  createTableObject();
}

function formValidate()
{
  $('#sales_invoice_form').bootstrapValidator('validate');
  if ($('#sales_invoice_form').bootstrapValidator('isValid')) 
  {
    saveSalesInvoice();
  }
}

function saveSalesInvoice(update_id)
{
  update_id = update_id;

  if(update_id != null)
  {
    createTableObject();
  }

  var data = {
    invoice_date  : $('#invoice_date').val(),
    invoice_id    : $('#invoice_id').val(),
    invoice_amount: $('#invoice_amount').val(),
    discount      : $('#discount').val(),
    remarks       : $('#remarks').val(),
    payment_amount: $('#payment_amount').val(),
    invoice_item  : invoice_item,
    update_id     : update_id,
    '_token'      : $('input[name=_token]').val(),
  };

  $.ajax({
    url: '/sales_invoice',
    type: "post",
    data: data,
    dataType: 'JSON',
    success: function (data) 
    {
      if(data.status != undefined)
      {
        $('.alert-success').show();
        $('.alert-success').append('<p>'+data.status+'</p>');

        update_id = data.id;
        print_id  = data.id;
        // resetNewPage();
        $('#print_btn').show();
      }
    }
  });
}

function resetNewPage()
{
  $('#invoice_date').val('');

  $("#invoice_id").val('');
  $('#invoice_amount').val('');
  $('#discount').val('');
  $('#payment_amount').val('');
  $('#remarks').val('');
  invoice_item = [];
  $('#invoice_item_table > tbody  > tr:not(#invoice_item_table)').empty();
  invoiceCode();
}

function invoiceCode()
{
  $.ajax({
    url: '/invoice_code',
    type: "GET",
    dataType: 'JSON',
    success: function(invoice_code) 
    {
      $("#invoice_id").val(invoice_code);
    }
  });
}

$('#print_btn').on('click',function ()
{
  print(print_id);
});

function print(print_id)
{
  window.open("/print_sales_invoice/"+print_id).print();
}

</script>
@endsection