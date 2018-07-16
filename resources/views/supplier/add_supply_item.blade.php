@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Add Supply Item</b></h3></div>
      <!-- supplier details -->
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-8">
              <small> Add  / Update / Supply Item</small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/supplier" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>
        <div class="panel-body">

          @if(session('status'))
            <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
            </div>
          @endif

          @if(isset($data))
            <form  id='supply_item_form' action="/supplier/add_supply_items" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="post">
              <br><div class="col-sm-2"></div>
              <div class="col-sm-8">

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Supplier </label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="supplier_id" id="supplier_id">
                      <option value="">Select Supplier</option>
                      @if(isset($data['suppliers']))
                        @foreach ($data['suppliers'] as $supplier)
                          @if($supplier->id == $data['supplier_id'])
                            <option value="{{$supplier->id}}" selected>{{$supplier->supplier_name}}</option>
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
                    <label class="text-right"> Item </label>
                  </div>
                  <div class="col-sm-6">
                    <select class="selectpicker" data-live-search="true" name="item_id[]" id="item_id[]" multiple>
                      <option value="">Select Item</option>
                      @if(isset($data['items']))
                        @foreach ($data['items'] as $item)
                          <option value="{{$item->id}}">{{$item->item_name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>  
                </div>

                <div class="row form-group" >
                  <div class="col-sm-6"></div>
                  <div class="col-sm-6" align="right">
                    <button type="submit" class="btn btn-info">Add</button>   
                    <button type="reset" class="btn btn-defualt" id="refresh_btn">Refresh</button>   
                  </div>
                </div>

              </div>
            </form>
          @endif
        </div>
      </div>

      @if(isset($data['supplyitems']))
        <div class ="panel panel-info">
          <div class="panel-heading">
            <div class="row">
              <div class="col-sm-8">
                <small>Supply Item</small>
              </div>
            </div>
          </div>
          <div class="panel-body">
            @if(session('status_delete'))
              <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status_delete') }}
              </div>
            @endif
            <table id="supply_items" class="table table-striped table-hover table-center" cellspacing="0">
              <thead>
                <tr>
                  <th class="text-center" style="border: 1px solid #dddddd;">Item Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Category Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Sub Category Name</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Availability</th>
                  <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['supplyitems'] as $supplyitem)
                <tr>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$supplyitem->item_name}}</td>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$supplyitem->cate_name}}</td>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$supplyitem->sub_cate_name}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{($supplyitem->status == 1 ? "Yes" : "No")}}</td>


                  <form action="/supplier/add_supply_items/{{$supplyitem->id}}/{{$supplyitem->sup_id}}" class="pull-right" method="POST">
                     {{ csrf_field() }}
                     <td class="text-center" style="border: 1px solid #dddddd;">
                      <a class="btn btn-warning" id='{{$supplyitem->id}}' href="/supplier/add_supply_items/{{$supplyitem->id}}/{{$supplyitem->sup_id}}">Edit</a>
                     </td>
                  </form>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
    @endif

  </div>
</div>
@endsection

@section('script')
<script>
var supply_items = 0;

$(document).ready(function()
{
  supply_items = $('#supply_items').DataTable( {
      scrollY       :'400px',
      scrollX       : true,
      scrollCollapse: true,
      paging        : false,
      searching     : true,
      ordering      : true,
      info          : false,
      dom: 'Bfrtip',
      buttons: [{
        pageSize: 'a4',
        title:'supply items'
      }]
    });
});
</script>
@endsection