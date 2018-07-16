@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Purchase Order</b></h3></div>

      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-8">
              <small> Create  / Update / Delete Purchase Order</small>
            </div>
            <div class="col-sm-4" align="right">
              @if(isset($data['purchaseOrder']))
                <a href="/print_purchase_order/{{$data['purchaseOrder']->id}}" target="_blank" class="btn btn-default"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
              @endif
              <a href="/purchase_order" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          <div class="alert alert-danger" style="display:none"></div>
          <form  id='purchase_order_form' method="post" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <div class="row">
              <br><div class="col-sm-2"></div>
              <div class="col-sm-8">

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Purchase Order Code</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['purchaseOrder']))
                      <input type="text" class="form-control" name="order_id" id="order_id" placeholder="Enter Order ID" value="{{$data['purchaseOrder']->order_id}}" readonly>
                    @else
                      <input type="text" class="form-control" name="order_id" id="order_id" placeholder="Enter Order ID" value="{{$data['code']}}" readonly>
                    @endif
                  </div>  
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Order Date</label>
                  </div>
                  <div class="col-sm-6">

                    @if(isset($data['purchaseOrder']))
                      <input class="datepicker form-control" type="text" id="order_date"  name ="order_date" width="270" value="{{$data['purchaseOrder']->order_date}}" placeholder="Enter Order Date"></input>
                    @else
                      <input class="datepicker form-control" type="text" id="order_date"  name ="order_date" width="270" placeholder="Enter Order Date" value="<?php echo date('Y-m-d'); ?>"></input>
                    @endif
                    
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Supplier </label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="supplier_id" id="supplier_id" width="270">
                      <option value="">Select Supplier</option>
                      @if(isset($data['suppliers']))
                        @foreach ($data['suppliers'] as $supplier)
                          @if(isset($data['purchaseOrder']))
                            @if($data['purchaseOrder']->supplier_id == $supplier->id)
                              <option value="{{$supplier->id}}" selected>{{$supplier->supplier_name}}</option>
                            @else
                              <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                            @endif
                          @else
                            <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                          @endif
                        @endforeach
                      @endif
                    </select>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['purchaseOrder']))
                      <textarea type="text" class="form-control" id ="remarks" name="remarks">{{$data['purchaseOrder']->remarks}}</textarea>
                    @else
                      <textarea type="text" class="form-control" id ="remarks" name="remarks"></textarea>
                    @endif
                  </div>  
                </div>
              </div>
            </div>
            <div class="row">
              <br><div class="col-sm-1"></div>
              <div class="col-sm-10">

                <p hidden id="error"><span id="table_error" class="text-danger"><b></b></span></p>
                <table id="order_item_table" class="table table-striped table-hover table-center" cellspacing="0">
                  <thead>
                    <tr>
                     <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Order Type</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">QTY</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr id="order_item_table_opction_select">
                      <th class="text-center" style="border: 1px solid #dddddd;">
                        <select class="selectpicker" data-live-search="true" name="item_id" id="item_id" width="270">
                          <option value="">Select Item</option>
                          @if(isset($data['items']))
                            @foreach ($data['items'] as $item)
                              <option value="{{$item->id}}" data-type="{{$item->order_type_id}}" data-type_name="{{$item->order_type_name}}">{{$item->item_name}}</option>
                            @endforeach
                          @endif
                        </select>
                      </th>

                      <th hidden class="text-center" style="border: 1px solid #dddddd;">
                        <input type="text" class="form-control" name="order_type" id="order_type" placeholder="Enter Order Type" readonly>
                      </th>

                      <th class="text-center" style="border: 1px solid #dddddd;">
                        <input type="text" class="form-control" name="order_type_name" id="order_type_name" placeholder="Enter Order Type" readonly>
                      </th>

                      <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="order_qty" id="order_qty" onkeypress="return numberOnly(event)" placeholder="Enter Order QTY"></th>

                      <th class="text-center" style="border: 1px solid #dddddd;"><button class="btn btn-info" type="button" onclick="addOrderItem();">Add</button></th>
                    </tr>
                    @if(isset($data['prchaseOrderEntry']))
                      @foreach ($data['prchaseOrderEntry'] as $entry)
                        <tr id="{{$entry->item_id}}">
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$entry->item_id}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$entry->item_name}}</td>
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$entry->order_type_id}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$entry->order_type_name}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$entry->order_qty}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;"><a onclick="removeOrderItem('{{$entry->item_id}}')" class="btn btn-danger btn-xs" type="button">Remove</a></td>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>

            <br><div class="row form-group" >
              <div class="col-sm-5"></div>
              <div class="col-sm-6" align="right">
                @if(isset($data['purchaseOrder']))
                  <button type="button" class="btn btn-info" onclick="savePurchaseOrder('{{$data['purchaseOrder']->id}}');">Update</button>
                @else
                  <button type="button" onclick="formValidate();" class="btn btn-info">Create</button>
                @endif
                <a href="/purchase_order/create" class="btn btn-default ">Refresh</a>
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

var order_item       = {};
var order_item_count = 0;
var update_id        = null;

$(document).ready(function()
{
  $('#purchase_order_form').formValidation({
    framework: 'bootstrap',
        fields: {
            order_id: {
                validators: {
                    notEmpty: {
                        message: 'The Order Id is required'
                    }
                }
            },
            supplier_id: {
                validators: {
                    notEmpty: {
                        message: 'The Supplier Name is required'
                    }
                }
            },
            order_date: {
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

$('#item_id').on('change',function ()
{
  var type_id   = $('#item_id option:selected').attr('data-type');
  var type_name = $('#item_id option:selected').attr('data-type_name');

  $('#order_type').val();
  $('#order_type_name').val();
  $('#order_type').val(type_id);
  $('#order_type_name').val(type_name);
});

$('#supplier_id').on('change',function ()
{
  $('#item_id').empty();
  var supplier_id = $('#supplier_id').val();
  fetchSupplierItems(supplier_id);
});

function fetchSupplierItems(supplier_id)
{
  var data = {supplier_id:supplier_id};
   $.ajax({
   method: 'GET',
   url: '/fetch_supplier_items',
   data: data,
   success: function(supplier_item)
   { 
      if(supplier_item != null)
      {
        $('#item_id').append('<option value="">Select Item</option>');
        $.each(supplier_item, function (index, value) 
        {
          $('#item_id').append( '<option value="'+value['id']+'" data-type="'+value['order_type_id']+'" data-type_name="'+value['order_type_name']+'" >'+value['item_name']+'</option>' );
        });
        $('.selectpicker').selectpicker('refresh');
      }
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log('comes error');
    }
  });
}

function addOrderItem()
{
  $('table_error').val('');
  var item_id         = $('#item_id option:selected').val();
  var item_name       = $('#item_id option:selected').text();
  var order_type      = $('#order_type').val();
  var order_type_name = $('#order_type_name').val();
  var order_qty       = $('#order_qty').val();
  var orderContent    = null;
  var item_already    = null;

  $(order_item).each(function(i, el)
  {
    if(el.item_id == item_id)
    {
      item_already ="set"; 
    }
  });

  $('#error').hide();

  if(item_already == null)
  {
    orderContent = '<tr id="'+item_id+'" ><td hidden>'+item_id+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+item_name+'</td><td hidden>'+order_type+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+order_type_name+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+order_qty+'</td><td class="text-center" style="border: 1px solid #dddddd;"><a onclick="removeOrderItem('+item_id+')" class="btn btn-danger btn-xs" type="button">Remove</a></td></tr>';
    item_already = null;
    $("#order_item_table tbody").append(orderContent);
    reset();
    createTableObject();
  }
  else
  {
    $('#error').show();
    $("#table_error").text("this item already in the table");
  }
}

function reset()
{
  $("#item_id").val('');
  $("#item_id").selectpicker("refresh");

  $("#order_type").val('');
  $("#order_type_name").val('');
  $('#order_qty').val('');
}

function createTableObject()
{
  order_item = [];
  $('#order_item_table > tbody  > tr:not(#order_item_table_opction_select)').each(function(i, el)
  {
    var td           = $(this).find('td');
    item = {};
    item["item_id"]         = td.eq(0).text();
    item["item_name"]       = td.eq(1).text();
    item["order_type"]      = td.eq(2).text();
    item["order_type_name"] = td.eq(3).text();
    item["order_qty"]       = td.eq(4).text();

    order_item.push(item);
 });
}

function removeOrderItem(remove_item_id)
{
  $("#"+remove_item_id).remove();
  createTableObject();
}

function formValidate()
{
  $('#purchase_order_form').bootstrapValidator('validate');
  if ($('#purchase_order_form').bootstrapValidator('isValid')) 
  {
      savePurchaseOrder();
  }
}

function savePurchaseOrder(update_id)
{
  update_id = update_id;

  if(update_id != null)
  {
    createTableObject();
  }

  var data = {
    order_date :$('#order_date').val(),
    supplier_id:$('#supplier_id option:selected').val(),
    order_id   :$('#order_id').val(),
    remarks    :$('#remarks').val(),
    order_item :order_item,
    update_id  :update_id,
    '_token'   : $('input[name=_token]').val(),
  };

  $.ajax({
    url: '/purchase_order',
    type: "post",
    data: data,
    dataType: 'JSON',
    success: function (data) 
    {
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

  $('order_date').val('');
  $("#supplier_id").val('');
  $("#supplier_id").selectpicker("refresh");
  $('#order_id').val('');
  $('#remarks').val('');
  order_item = [];
  $('#order_item_table > tbody  > tr:not(#order_item_table_opction_select)').empty();

}

</script>
@endsection