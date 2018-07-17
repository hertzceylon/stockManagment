@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Purchase Return </b></h3></div>

      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-8">
              <small> Create  / Update  Purchase Return </small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/purchase_return" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          <div class="alert alert-success" style="display:none"></div>
          <form  id='purchase_return_form' method="post" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <div class="row">
              <br><div class="col-sm-2"></div>

              <div class="col-sm-8">

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Purchase Return ID</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['purchaseReturn']))
                      <input type="text" class="form-control" name="return_id" id="return_id" placeholder="Enter purchase Return ID" value="{{$data['purchaseReturn']->return_id}}" readonly>
                    @else
                      <input type="text" class="form-control" name="return_id" id="return_id" placeholder="Enter purchase Return ID" value ="{{$data['code']}}" readonly>
                    @endif
                  </div>
                </div>


                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Purchase Return Date</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['purchaseReturn']))
                      <input class="datepicker form-control" type="text" id="return_date"  name ="return_date" width="270" placeholder="Enter Purchase Return Date" value="{{$data['purchaseReturn']->return_date}}"></input>
                    @else
                      <input class="datepicker form-control" type="text" id="return_date"  name ="return_date" width="270" placeholder="Enter Purchase Return Date" value="<?php echo date('Y-m-d'); ?>"></input>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Supplier</label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="supplier_id" id="supplier_id" width="270">
                      <option value="">Select Supplier</option>
                      @if(isset($data['suppliers']))
                        @foreach ($data['suppliers'] as $supplier)
                          @if(isset($data['purchaseReturn']))
                            @if($supplier->id == $data['purchaseReturn']->supplier_id)
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
                    <label class="text-right">Return Amount</label>
                  </div>
                  <div class="col-sm-6">
                    @if(isset($data['purchaseReturn']))
                      <input type="text" class="form-control" name="return_amount" id="return_amount" placeholder="Return Amount" value="{{$data['purchaseReturn']->return_amount}}" readonly>
                    @else
                      <input type="text" class="form-control" name="return_amount" id="return_amount" placeholder="Return Amount" readonly>
                    @endif
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                     @if(isset($data['purchaseReturn']))
                      <textarea type="text" class="form-control" id ="remarks" name="remarks">{{$data['purchaseReturn']->remarks}}</textarea>
                    @else
                      <textarea type="text" class="form-control" id ="remarks" name="remarks"></textarea>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <br>
              <div class="col-sm-12">
                <p hidden id="error"><span id="table_error" class="text-danger"><b></b></span></p>
                <table id="return_item_table" class="table table-striped table-hover table-center" cellspacing="0">
                  <thead>
                    <tr>
                     <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Price</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Return Stock Type</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Return Type</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Return QTY</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Return Amount</th>
                     <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr id="return_item_table_opction_select">
                      <th class="text-center" style="border: 1px solid #dddddd;">
                        <select class="selectpicker" data-live-search="true" name="item_id" id="item_id" width="270">
                          <option value="">Select Item</option>
                          @if(isset($data['items']))
                            @foreach ($data['items'] as $item)
                              <option value="{{$item->id}}" data-type ="{{$item->item_price}}">{{$item->item_name}}</option>
                            @endforeach
                          @endif
                        </select>
                      </th>

                      <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="item_price" id="item_price" onkeypress="return numberOnly(event)" placeholder="Enter Item Price"></th>

                      <th class="text-center" style="border: 1px solid #dddddd;">
                        <select class="selectpicker" data-live-search="true" name="stock_type_id" id="stock_type_id" width="270">
                          <option value="">Select Return Stock Type</option>
                          <option value="G">Good</option>
                          <option value="B">Bad</option>
                        </select>
                      </th>

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

                      <th class="text-center" style="border: 1px solid #dddddd;"><button class="btn btn-info" type="button" onclick="addReturnItem();">Add</button></th>
                    </tr>
                    @if(isset($data['purchaseReturnEntries']))
                      @foreach($data['purchaseReturnEntries'] as $purchaseReturnEntry)
                        <tr id="{{$purchaseReturnEntry->item_id}}">
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->item_id}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->item_name}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->item_price}}</td>
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->stock_type_id}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->stock_type_name}}</td>
                          <td hidden class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->return_type_id}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->return_name}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->return_qty}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;">{{$purchaseReturnEntry->amount}}</td>
                          <td class="text-center" style="border: 1px solid #dddddd;"><a onclick="removeReturnItem('{{$purchaseReturnEntry->item_id}}')" class="btn btn-danger btn-xs" type="button">Remove</a></td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>

            <br><div class="row form-group" >
              <div class="col-sm-6"></div>
              <div class="col-sm-6" align="right">
                @if(isset($data['purchaseReturn']))
                  <button type="button" class="btn btn-info" onclick="savePurchaseReturn('{{$data['purchaseReturn']->id}}');">Update</button>
                @else
                  <button type="button" onclick="formValidate();" class="btn btn-info">Create</button>
                @endif

                <a href="/purchase_return/create" class="btn btn-default ">Refresh</a>
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

var return_item = {};
var update_id   = 0;

$(document).ready(function()
{
  $('#purchase_return_form').formValidation({
    framework: 'bootstrap',
        fields: {
            supplier_id: {
                validators: {
                    notEmpty: {
                        message: 'The Supplier Name is required'
                    }
                }
            },
            return_date: {
                validators: {
                  notEmpty: {
                        message: 'The Return Date is required'
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

$('#return_qty , #item_price').on('keyup',function ()
{
  var item_price = $('#item_price').val();
  var return_qty = $('#return_qty').val();

  $('#return_amount_td').val(item_price * return_qty);
});

function formValidate()
{
  $('#purchase_return_form').bootstrapValidator('validate');
  if ($('#purchase_return_form').bootstrapValidator('isValid')) 
  {
      savePurchaseReturn();
  }
}

function addReturnItem()
{
  var item_id          = $('#item_id option:selected').val();
  var item_name        = $('#item_id option:selected').text();
  var item_price       = $('#item_price').val();
  var stock_type_id    = $('#stock_type_id option:selected').val();
  var stock_type_name  = $('#stock_type_id option:selected').text();
  var return_type_id   = $('#return_type_id option:selected').val();
  var return_type_name = $('#return_type_id option:selected').text();
  var return_qty       = $('#return_qty').val();
  var return_amount    = $('#return_amount_td').val();
  var returnContent    = null;

  returnContent = '<tr id="'+item_id+'"><td hidden>'+item_id+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+item_name+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+item_price+'</td><td hidden class="text-center" style="border: 1px solid #dddddd;">'+stock_type_id+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+stock_type_name+'</td><td hidden>'+return_type_id+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+return_type_name+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+return_qty+'</td><td class="text-center" style="border: 1px solid #dddddd;">'+return_amount+'</td><td class="text-center" style="border: 1px solid #dddddd;"><a onclick="removeReturnItem('+item_id+')" class="btn btn-danger btn-xs" type="button">Remove</a></td></tr>';

  $("#return_item_table tbody").append(returnContent);
  reset();
  createTableObject();
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

function createTableObject()
{
  var total_invoice_amount = 0;
  return_item              = [];

  $('#return_item_table > tbody  > tr:not(#return_item_table_opction_select)').each(function(i, el)
  {
    var td = $(this).find('td');
    item   = {};

    item["item_id"]          = td.eq(0).text();
    item["item_name"]        = td.eq(1).text();
    item["item_price"]       = td.eq(2).text();
    item["stock_type_id"]    = td.eq(3).text();
    item["stock_type_name"]  = td.eq(4).text();
    item["return_type_id"]   = td.eq(5).text();
    item["return_type_name"] = td.eq(6).text();
    item["return_qty"]       = td.eq(7).text();
    item["return_amount"]    = td.eq(8).text();
  
    return_item.push(item);

    total_invoice_amount += parseFloat(td.eq(8).text());
 });

  $('#return_amount').val(total_invoice_amount);
}

function removeReturnItem(remove_item_id)
{
  $("#"+remove_item_id).remove();
  createTableObject();
}

function savePurchaseReturn(update_id)
{
  update_id = update_id;

  if(update_id != null)
  {
    createTableObject();
  }

  var data = {
    return_date  : $('#return_date').val(),
    return_id    : $('#return_id').val(),
    supplier_id  : $('#supplier_id option:selected').val(),
    return_amount: $('#return_amount').val(),
    remarks      : $('#remarks').val(),
    return_item  : return_item,
    update_id    : update_id,
    '_token'     : $('input[name=_token]').val(),
  };

  $.ajax({
    url: '/purchase_return',
    type: "post",
    data: data,
    dataType: 'JSON',
    success: function (data)
    {
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

  $('return_date').val('');
  $("#supplier_id").val('');
  $("#supplier_id").selectpicker("refresh");
  $('#return_id').val('');
  $('#return_amount').val('');
  $('#remarks').val('');
  return_item = [];
  $('#return_item_table > tbody  > tr:not(#return_item_table_opction_select)').empty();
}

</script>
@endsection
