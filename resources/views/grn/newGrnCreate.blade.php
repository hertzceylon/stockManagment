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
              <small> Create  / Update / Delete GRN</small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/grn" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          <div class="alert alert-success" style="display:none"></div>

          <form  id='grn_form' method="post" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <div class="row">
              <br><div class="col-sm-2"></div>
              <div class="col-sm-8">
                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">GRN ID</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['grn']))
                      <input type="text" class="form-control" name="grn_id" id="grn_id" placeholder="Enter GRN ID" value="{{$data['grn']->grn_id}}" readonly>
                    @else
                      <input type="text" class="form-control" name="grn_id" id="grn_id" placeholder="Enter GRN ID" value="{{$data['code']}}" readonly>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="text-right">GRN Date</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['grn']))
                      <input class="datepicker form-control" type="text" id="grn_date"  name ="grn_date" width="270" placeholder="Enter GRN Date" value="{{$data['grn']->grn_date}}"></input>
                    @else
                      <input class="datepicker form-control" type="text" id="grn_date"  name ="grn_date" width="270" placeholder="Enter GRN Date" value="<?php echo date('Y-m-d'); ?>"></input>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Purchase Order </label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['grn']))
                      <input class="form-control" type="text" id="purchase_order_name"  name ="purchase_order_name" width="270" placeholder="Enter GRN Date" value="{{$data['grn']->order_id}}" readonly></input>

                      <input  class="form-control" type="hidden" id="purchase_order_id"  name ="purchase_order_id" width="270" placeholder="Enter GRN Date" value="{{$data['grn']->purchase_order_id}}" readonly></input>
                    @else
                      <select class="selectpicker" data-live-search="true" name="purchase_order_id" id="purchase_order_id" width="270">
                        <option value="">Select Purchase Order</option>
                        @if(isset($data['purchaseOrders']))
                          @foreach ($data['purchaseOrders'] as $purchaseOrder)
                              <option value="{{$purchaseOrder->id}}" data-type="{{$purchaseOrder->order_date}}" data-supplier="{{$purchaseOrder->supplier_name}}">{{$purchaseOrder->order_id}} | {{$purchaseOrder->order_date}}</option>
                          @endforeach
                        @endif
                      </select>
                    @endif
                  </div>  
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Supplier</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['grn']))
                      <input class="form-control" type="text" id="supplier_name"  name ="supplier_name" width="270" placeholder="Supplier" value="{{$data['grn']->supplier_name}}" readonly></input>
                    @else
                      <input class="form-control" type="text" id="supplier_name"  name ="supplier_name" width="270" placeholder="Supplier" readonly></input>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Purchase Order Date</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['grn']))
                      <input class="form-control" type="text" id="purchase_order_date"  name ="purchase_order_date" width="270" placeholder="Purchase Order Date" value="{{$data['grn']->order_date}}" readonly></input>
                    @else
                      <input class="form-control" type="text" id="purchase_order_date"  name ="purchase_order_date" width="270" placeholder="Purchase Order Date" readonly></input>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['grn']))
                      <textarea type="text" class="form-control" id ="remarks" name="remarks" placeholder="Remark">{{$data['grn']->remarks}}</textarea>
                    @else
                      <textarea type="text" class="form-control" id ="remarks" name="remarks" placeholder="Remark"></textarea>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">GRN Amount</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['grn']))
                      <input type="text" class="form-control" name="grn_amount" id="grn_amount" placeholder="Enter GRN Amount" value="{{$data['grn']->grn_amount}}" readonly>
                    @else
                      <input type="text" class="form-control" name="grn_amount" id="grn_amount" placeholder="Enter GRN Amount" readonly>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <br><div class="col-sm-1"></div>
              <div class="col-sm-10">
                <table id="grn_entry_table" class="table table-striped table-hover table-center" cellspacing="0">
                  <thead>
                    <tr>
                     <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Order Type</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Order QTY</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Received Stock QTY (Good )</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Received Stock QTY ( Bad )</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Price</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Manufacture Date</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Expiration Date</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Discount % </th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Amount</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                    </tr>
                  </thead>
                  <tbody id="grn_entry_table_body">
                    @if(isset($data['grn_entries']))
                      @foreach($data['grn_entries'] as $grn_entry)
                        <tr id='{{$grn_entry->item_id}}'>
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$grn_entry->item_id}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$grn_entry->item_name}}</td>
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$grn_entry->order_type}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$grn_entry->order_type_name}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$grn_entry->order_qty}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control received_stock_qty_good_class" onkeypress="return numberOnly(event)" value="{{$grn_entry->rec_g_qty}}"></td>
                          <td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control received_stock_qty_bad" onkeypress="return numberOnly(event)" value="{{$grn_entry->rec_b_qty}}"></td>

                          <td class="text-center" style="border: 1px solid #dddddd;">
                            <input type="text" class="form-control item_price_class" onkeypress="return numberOnly(event)" value="{{$grn_entry->grn_item_price}}"></td>

                          <td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="datepicker form-control manif_date" value="{{$grn_entry->manif_date}}"></td>

                          <td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="datepicker form-control ex_date" value="{{$grn_entry->ex_date}}"></td>

                          <td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control discount_class" onkeypress="return numberOnly(event)" value="{{$grn_entry->discount}}"></td>

                          <td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control total_amount" onkeypress="return numberOnly(event)" readonly value="{{$grn_entry->amount}}"></td>

                          <td class="text-center remove_btn_class" style="border: 1px solid #dddddd;"><a onclick="removeGrnItem('{{$grn_entry->item_id}}')" class="btn btn-danger btn-xs" type="button">Remove</a></td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>

            <br><div class="row form-group" >
              <div class="col-sm-5"></div>
              <div class="col-sm-6" align="right">
                @if(isset($data['grn']))
                  <button type="button" class="btn btn-info" onclick="saveGrn('{{$data['grn']->id}}');">Update</button>
                @else
                  <button type="button" onclick="formValidate();" class="btn btn-info">Create</button>
                @endif

                <a href="/grn/create" class="btn btn-default ">Refresh</a>

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

var grn_item  = [];
var update_id = 0;

$(document).ready(function()
{
  
  $('#grn_form').formValidation({
    framework: 'bootstrap',
        fields: {
            grn_amount: {
                validators: {
                    notEmpty: {
                        message: 'The GRN Amount is required'
                    }
                }
            },
            purchase_order_id: {
                validators: {
                    notEmpty: {
                        message: 'The Purchase Order is required'
                    }
                }
            },
         }
    });
});

$('#purchase_order_id').on('change',function()
{
  $("#grn_entry_table_body").empty();
  
  var purchase_order_id   = $('#purchase_order_id option:selected').val();
  var purchase_order_date = $('#purchase_order_id option:selected').attr('data-type');
  var supplier_name       = $('#purchase_order_id option:selected').attr('data-supplier');

  $('#purchase_order_date').val(purchase_order_date);
  $('#supplier_name').val(supplier_name);

  fetchPurchaseOrderEntries(purchase_order_id);
});

$('#grn_entry_table_body').on('keyup', '.received_stock_qty_good_class', function()
{
  calculation();
  createGrnObject();
});

$('#grn_entry_table_body').on('keyup', '.received_stock_qty_bad', function()
{
  calculation();
  createGrnObject();
});

$('#grn_entry_table_body').on('keyup', '.item_price_class', function()
{
  calculation();
  createGrnObject();
});

$('#grn_entry_table_body').on('keyup', '.discount_class', function()
{
  calculation();
  createGrnObject();
});

function fetchPurchaseOrderEntries(purchase_order_id)
{
  var data = {purchase_order_id:purchase_order_id};
   $.ajax({
   method: 'GET',
   url: '/fetch_purchase_order_entries',
   data: data,
   success: function(order_entries)
   { 
      var grnContent     = '';
      $.each(order_entries, function (index, value) 
      {
        grnContent += '<tr id='+value.item_id+'><td hidden class="text-center item_id_class" style="border: 1px solid #dddddd;">'+value.item_id+'</td><td class="text-center item_name_class" style="border: 1px solid #dddddd;">'+value.item_name+'</td><td hidden class="text-center order_type_id_class" style="border: 1px solid #dddddd;">'+value.order_type+'</td><td class="text-center order_type_class" style="border: 1px solid #dddddd;">'+(value.order_type =="C" ? 'Case' : "Unit")+'</td><td class="text-center order_qty_class" style="border: 1px solid #dddddd;">'+value.order_qty+'</td><td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control received_stock_qty_good_class" onkeypress="return numberOnly(event)"></td><td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control received_stock_qty_bad" onkeypress="return numberOnly(event)"></td><td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control item_price_class" onkeypress="return numberOnly(event)"></td><td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="datepicker form-control manif_date"></td><td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="datepicker form-control ex_date"></td><td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control discount_class" onkeypress="return numberOnly(event)"></td><td class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control total_amount" onkeypress="return numberOnly(event)" readonly></td><td class="text-center remove_btn_class" style="border: 1px solid #dddddd;"><a onclick="removeGrnItem('+value.item_id+')" class="btn btn-danger btn-xs" type="button">Remove</a></td></tr>'
      });

      $("#grn_entry_table tbody").append(grnContent);
      $('.selectpicker').selectpicker();
      $('.selectpicker').selectpicker('refresh');

      $('.datepicker').datepicker({
        format:'yyyy-mm-dd',
        todatbtn:'linked',
        keyboardNaigation:false,
        forceParse:false,
        calendarWeeks:true,
        autoclose:false
      });


      createGrnObject();

    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log('comes error');
    }
  }); 
}

function createGrnObject()
{
  grn_item            = [];

  $('#grn_entry_table > tbody  > tr').each(function(i, el)
  {
    var td = $(this).find('td');
    item   = {};

    item["item_id"]         = td.eq(0).text();
    item["item_name"]       = td.eq(1).text();
    item["order_type"]      = td.eq(2).text();
    item["order_type_name"] = td.eq(3).text();
    item["order_qty"]       = td.eq(4).text();
    item["rec_g_qty"]       = $(this).find('.received_stock_qty_good_class').val();
    item["rec_b_qty"]       = $(this).find('.received_stock_qty_bad').val();
    item["grn_price"]       = $(this).find('.item_price_class').val();
    item["discount"]        = $(this).find('.discount_class').val();
    item["manif_date"]      = $(this).find('.manif_date').val();
    item["ex_date"]         = $(this).find('.ex_date').val();

    grn_item.push(item);
 });
}

function removeGrnItem(remove_item_id)
{
  $("#"+remove_item_id).remove();
  calculation();
  createGrnObject();
}

function calculation()
{
  var total_grn_value = 0;
  var discount_amount = 0;
  var grand_total     = 0;
  var good_stock      = 0;
  var bad_stock       = 0;
  var item_price      = 0;
  var discount        = 0;
  var grn_grand_total = 0;

  $('#grn_entry_table > tbody  > tr').each(function(i, el)
  {
    var td          = $(this).find('td');
    
    good_stock      = parseFloat(($(this).find('.received_stock_qty_good_class').val() =="" ? 0 : $(this).find('.received_stock_qty_good_class').val()));
    
    bad_stock       = parseFloat(($(this).find('.received_stock_qty_bad').val() == "" ? 0 : $(this).find('.received_stock_qty_bad').val()));
    
    item_price      = parseFloat(($(this).find('.item_price_class').val() == "" ? 0 : $(this).find('.item_price_class').val()));
    
    discount        = parseFloat(($(this).find('.discount_class').val() == "" ? 0 : $(this).find('.discount_class').val()));
    
    total_grn_value = (good_stock+bad_stock)*item_price;
    discount_amount = (total_grn_value*discount)/100;
    grn_grand_total = total_grn_value-discount_amount;
    grand_total     += grn_grand_total;

    $(this).find('.total_amount').val(grn_grand_total);

 });

  $('#grn_amount').val(grand_total);

  $('#grn_form').bootstrapValidator('revalidateField', 'grn_amount');
  
}

function formValidate()
{
  $('#grn_form').bootstrapValidator('validate');
  if ($('#grn_form').bootstrapValidator('isValid')) 
  {
    saveGrn();
  }
}

function saveGrn(update_id)
{
  update_id             = update_id;
  var purchase_order_id = 0;

  if(update_id != null)
  {
    createGrnObject();

    purchase_order_id = $('#purchase_order_id').val();
  }
  else
  {
    purchase_order_id = $('#purchase_order_id option:selected').val();
  }

  var data = {
    grn_date         :$('#grn_date').val(),
    purchase_order_id:purchase_order_id,
    grn_id           :$('#grn_id').val(),
    remarks          :$('#remarks').val(),
    grn_amount       :$('#grn_amount').val(),
    grn_item         :grn_item,
    update_id        :update_id,
    '_token'         :$('input[name=_token]').val(),
  };

  $.ajax({
    url: '/grn',
    type: "post",
    data: data,
    dataType: 'JSON',
    success: function (data) {

      if(data.status != undefined)
      {
          $('.alert-success').show();
          $('.alert-success').append('<p>'+data.status+'</p>');
          resetNewPage();
      }
    }
  });
}

function resetNewPage()
{
  update_id = null;
  $('grn_date').val('');
  $("#purchase_order_id").val('');
  $("#purchase_order_id").selectpicker("refresh");
  $('#grn_id').val('');
  $('#remarks').val('');
  $('#grn_amount').val('');
  grn_item = [];
  $('#grn_entry_table > tbody').empty();

}

</script>
@endsection