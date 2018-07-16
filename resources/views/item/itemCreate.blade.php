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
              <small> Create  / Update / Delete Item</small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/item" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>
        <div class="panel-body">

          @if(isset($data['item']))
            <form  id='item_form' action="/item/{{$data['item']->id}}" method="post" class="form-horizontal" onsubmit="return false;">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="PUT">
              <br><div class="col-sm-2"></div>

              <div class="col-sm-8">
                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Item Name</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Enter Item Name" value="{{$data['item']->item_name}}">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Bar Code</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="bar_code" id="bar_code" placeholder="Enter Bar Code" value="{{$data['item']->bar_code}}">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Category</label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="category_id" id="category_id">
                      <option value="">Select Category</option>
                      @foreach ($data['categories'] as $category)
                        @if($category->id == $data['item']->cate_id)
                            <option value="{{$category->id}}" selected>{{$category->cate_name}}</option>
                        @else
                            <option value="{{$category->id}}">{{$category->cate_name}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Sub Category</label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="sub_category_id" id="sub_category_id">
                      <option value="">Select Sub Category</option>
                      @foreach ($data['sub_category'] as $sub_category)
                        @if($sub_category->id == $data['item']->sub_cate_id)
                            <option value="{{$sub_category->id}}" selected>{{$sub_category->sub_cate_name}}</option>
                        @else
                            <option value="{{$sub_category->id}}">{{$sub_category->sub_cate_name}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right"> Item Order Type </label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="order_type_id" id="order_type_id">
                      <option value="">Select Item Order Type</option>
                      @foreach ($data['itemOrderTypes'] as $itemOrderType)
                        @if($itemOrderType->id == $data['item']->order_type_id)
                            <option value="{{$itemOrderType->id}}" selected>{{$itemOrderType->order_type_name}}</option>
                        @else
                            <option value="{{$itemOrderType->id}}">{{$itemOrderType->order_type_name}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Availability</label>
                  </div>
                  <div class="col-sm-6">
                    <div class="checkbox">
                      @if($data['item']->status == 1)
                        <input data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Yes" data-off="No" type="checkbox" name="status" id="status" checked>
                      @else
                        <input data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Yes" data-off="No" type="checkbox" name="status" id="status">
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                      <textarea type="text" class="form-control" id ="remarks" name="remarks">{{$data['item']->remarks}}</textarea>
                  </div>  
                </div>

                <div class="row form-group" >
                  <div class="col-sm-6"></div>
                  <div class="col-sm-6" align="right">
                      <button type="submit" class="btn btn-info">Update</button>
                  </div>
                </div>
              </div>
            </form>
          @else
            <form  id='item_form' action="/item" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="post">
              <br><div class="col-sm-2"></div>
              <div class="col-sm-8">

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Item Name</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Enter Item Name">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Bar Code</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="bar_code" id="bar_code" placeholder="Enter Bar Code">
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Category</label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="category_id" id="category_id">
                      <option value="">Select Category</option>
                      @foreach ($data['categories'] as $category)
                          <option value="{{$category->id}}">{{$category->cate_name}}</option>
                      @endforeach
                    </select>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Sub Category</label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="sub_category_id" id="sub_category_id">
                      <option value="">Select Sub Category</option>
                    </select>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right"> Item Order Type </label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="order_type_id" id="order_type_id">
                      <option value="">Select Item Order Type</option>
                      @foreach ($data['itemOrderTypes'] as $itemOrderType)
                            <option value="{{$itemOrderType->id}}">{{$itemOrderType->order_type_name}}</option>
                      @endforeach
                    </select>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Availability</label>
                  </div>
                  <div class="col-sm-6">
                    <div class="checkbox">
                      <input checked data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Yes" data-off="No" type="checkbox" name="status" id="status">
                    </div>
                  </div>
                </div>
                <br>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                      <textarea type="text" class="form-control" id ="remarks" name="remarks"></textarea>
                  </div>  
                </div>

                <div class="row form-group" >
                  <div class="col-sm-6"></div>
                  <div class="col-sm-6" align="right">
                    <button type="submit" class="btn btn-info">Create</button>   
                    <button type="reset" class="btn btn-defualt" id="refresh_btn">Refresh</button>   
                  </div>
                </div>

              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@include('layouts.deleteModal')
@endsection

@section('script')
<script type="text/javascript">

$(document).ready(function()
{
  $('.selectpicker').selectpicker();

  $('#item_form').formValidation({
    framework: 'bootstrap',
        fields: {
            item_name: {
                validators: {
                    notEmpty: {
                        message: 'The Item Name is required'
                    }
                }
            },
            category_id: {
                validators: {
                    notEmpty: {
                        message: 'The Category Name is required'
                    }
                }
            },
            bar_code: {
                validators: {
                    notEmpty: {
                        message: 'The Bar Code is required'
                    }
                }
            },
         }
    });
});

$('#category_id').on('change',function ()
{
  $('#sub_category_id').empty();
  var category_id = $('#category_id').val();
  fetchSubCategory(category_id);
});

function fetchSubCategory(category_id)
{
   var data = {category_id:category_id};
   $.ajax({
   method: 'GET',
   url: '/fetch_sub_category',
   data: data,
   success: function(subCategories)
   { 
      if(subCategories != null)
      {
        $('#sub_category_id').append('<option value="">Select Sub Category</option>');
        $.each(subCategories, function (index, value) 
        {
           $('#sub_category_id').append( '<option value="'+value['id']+'">'+value['sub_cate_name']+'</option>' );
        });

        $('.selectpicker').selectpicker('refresh');
      }
      else
      {
        $('#sub_category_id').append('<option value="">Select Sub Category</option>');
        $('#sub_category_id').selectpicker('refresh');
      }
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log('comes error');
    }
  }); 
}
</script>
@endsection


