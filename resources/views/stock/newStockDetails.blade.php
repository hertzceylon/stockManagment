@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Stock</b></h3></div>

      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-8">
              <small> Create  / Update Stock</small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/stock" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>

        <div class="panel-body">
          @if(isset($data['stock']))

          <form id='new_stock_form' action="/stock/{{$data['stock']->id}}" method="post" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <br><div class="col-sm-2"></div>

            <div class="col-sm-8">

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Item </label>
                </div>
                <div class="col-sm-6">
                  <select class="selectpicker" data-live-search="true" name="item_id" id="item_id" width="270">
                    <option value="">Select Item</option>
                    @if(isset($data['items']))
                      @foreach ($data['items'] as $item)
                        @if($item->id == $data['stock']->item_id)
                          <option value="{{$item->id}}"  data-type="{{$item->order_type_name}}"  data-type_id="{{$item->order_type_id}}" selected>{{$item->item_name}}</option>
                        @else
                          <option value="{{$item->id}}" data-type="{{$item->order_type_name}}" data-type_id="{{$item->order_type_id}}">{{$item->item_name}}</option>
                        @endif
                      @endforeach
                    @endif
                  </select>
                </div>  
              </div>

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Order Type</label>
                </div>
                <div class="col-sm-6">
                  <input class="form-control" type="hidden" id="order_type_id"  name ="order_type_id" width="270" placeholder="Enter Order Type" value="{{$data['stock']->order_type_id}}" readonly></input>
                  <input class="form-control" type="text" id="order_type"  name ="order_type" width="270" placeholder="Enter Order Type" value="{{$data['stock']->order_type_name}}" readonly></input>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Stock Type </label>
                </div>
                <div class="col-sm-6">
                  <select class="selectpicker" data-live-search="true" name="stock_type" id="stock_type" width="270">
                    <option value="">Select Stock Type</option>
                    @if($data['stock']->stock_type == "G")
                      <option value="G" selected>Good</option>
                      <option value="B">Bad</option>
                    @else
                      <option value="G">Good</option>
                      <option value="B" selected>Bad</option>
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label class="text-right">Item Price</label>
                </div>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="item_price" id="item_price" onkeypress="return numberOnly(event)" placeholder="Enter item price" value="{{$data['stock']->item_price}}">
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label class="text-right">Manufacture Date</label>
                </div>
                <div class="col-sm-6">
                  <input class="datepicker form-control" type="text" id="Manufacture_date"  name ="Manufacture_date" width="270" placeholder="Enter Manufacture Date" value="{{$data['stock']->Manufacture_date}}"></input>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label class="text-right">Expiration Date</label>
                </div>
                <div class="col-sm-6">
                  <input class="datepicker form-control" type="text" id="expiration_date"  name ="expiration_date" width="270" placeholder="Enter Expiration Date" value="{{$data['stock']->expiration_date}}"></input>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label class="text-right">Stock QTY</label>
                </div>
                <div class="col-sm-6">
                  <input class="form-control" type="text" id="stock_qty"  name ="stock_qty" width="270" value="{{$data['stock']->stock_qty}}" onkeypress="return numberOnly(event)"></input>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Remarks</label>
                </div>
                <div class="col-sm-6">
                  <textarea type="text" class="form-control" id ="remarks" name="remarks">{{$data['stock']->remarks}}</textarea>
                </div>  
              </div>

              <br><div class="row form-group" >
                <div class="col-sm-5"></div>
                <div class="col-sm-6" align="right">
                    <button type="submit" class="btn btn-info">Update</button>
                </div>
              </div>

            </div>
          </form>

          @else
            <form id='new_stock_form' action="/stock" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="post">
              <div class="row">
                <br><div class="col-sm-2"></div>
                <div class="col-sm-8">

                  <div class="row form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Item </label>
                    </div>
                    <div class="col-sm-6">
                      <select class="selectpicker" data-live-search="true" name="item_id" id="item_id" width="270">
                        <option value="">Select Item</option>
                        @if(isset($data['items']))
                          @foreach ($data['items'] as $item)
                              <option value="{{$item->id}}" data-type="{{$item->order_type_name}}" data-type_id="{{$item->order_type_id}}">{{$item->item_name}}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>  
                  </div>

                  <div class="row form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Order Type</label>
                    </div>
                    <div class="col-sm-6">
                      <input  class="form-control" type="hidden" id="order_type_id"  name ="order_type_id" width="270" placeholder="Enter Order Type" readonly></input>
                      <input class="form-control" type="text" id="order_type"  name ="order_type" width="270" placeholder="Enter Order Type" readonly></input>
                    </div>
                  </div>

                  <div class="row form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Stock Type </label>
                    </div>
                    <div class="col-sm-6">
                      <select class="selectpicker" data-live-search="true" name="stock_type" id="stock_type" width="270">
                        <option value="">Select Stock Type</option>
                        <option value="G">Good</option>
                        <option value="B">Bad</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Item Price</label>
                    </div>
                    <div class="col-sm-6">
                      <th class="text-center" style="border: 1px solid #dddddd;"><input type="text" class="form-control" name="item_price" id="item_price" onkeypress="return numberOnly(event)" placeholder="Enter item price"></th>
                    </div>
                  </div>


                  <div class="form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Manufacture Date</label>
                    </div>
                    <div class="col-sm-6">
                      <input class="datepicker form-control" type="text" id="Manufacture_date"  name ="Manufacture_date" width="270" placeholder="Enter Manufacture Date"></input>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Expiration Date</label>
                    </div>
                    <div class="col-sm-6">
                      <input class="datepicker form-control" type="text" id="expiration_date"  name ="expiration_date" width="270" placeholder="Enter Expiration Date"></input>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Stock QTY</label>
                    </div>
                    <div class="col-sm-6">
                      <input class="form-control" type="text" id="stock_qty"  name ="stock_qty" width="270" onkeypress="return numberOnly(event)"></input>
                    </div>
                  </div>

                  <div class="row form-group">
                    <div class="col-sm-6">
                      <label class="text-right">Remarks</label>
                    </div>
                    <div class="col-sm-6">
                      <textarea type="text" class="form-control" id ="remarks" name="remarks"></textarea>
                    </div>  
                  </div>

                </div>
              </div>

              <br><div class="row form-group" >
                <div class="col-sm-5"></div>
                <div class="col-sm-6" align="right">
                    <button type="submit" class="btn btn-info">Create</button>
                </div>
              </div>
            </form>
          @endif
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">

$(document).ready(function()
{
  $('.selectpicker').selectpicker();

  $('#new_stock_form').formValidation({
    framework: 'bootstrap',
        fields: {
            item_id: {
                validators: {
                    notEmpty: {
                        message: 'The Item Name is required'
                    }
                }
            },
            stock_type: {
                validators: {
                    notEmpty: {
                        message: 'The Stock type is required'
                    }
                }
            },
            order_type: {
                validators: {
                    notEmpty: {
                        message: 'The stock type is required'
                    }
                }
            },
            item_price: {
                validators: {
                    notEmpty: {
                        message: 'The Item price is required'
                    }
                }
            },
            Manufacture_date: {
                validators: {
                    notEmpty: {
                        message: 'The Manufacture Date is required'
                    }
                }
            },
            expiration_date: {
                validators: {
                    notEmpty: {
                        message: 'The Expiration Date is required'
                    }
                }
            },
            stock_qty: {
                validators: {
                    notEmpty: {
                        message: 'The Stock QTY is required'
                    }
                }
            },
         }
    });
});


$('#item_id').on('change',function ()
{
  var order_type    = $('#item_id option:selected').attr('data-type');
  var order_type_id = $('#item_id option:selected').attr('data-type_id');

  $('#order_type_id').val(order_type_id);
  $('#order_type').val(order_type);
});

</script>
@endsection