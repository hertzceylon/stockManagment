@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Category</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-8">
              <small> Create  / Update / Delete Category</small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/category" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>           
          </div>   
        </div>

        <div class="panel-body">
          @if(isset($category))
            <form  id='category_form' action="/category/{{$category->id}}" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="PUT">
              <br><div class="col-sm-2"></div>
              <div class="col-sm-8">
                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Category Name</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Enter Category Name" value="{{$category->cate_name}}">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Description</label>
                  </div>
                  <div class="col-sm-6">
                      <textarea type="text" class="form-control" id ="remarks" name="remarks">{{$category->remarks}}</textarea>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Status</label>
                  </div>
                  <div class="col-sm-6">
                    <div class="checkbox">
                      @if($category->status == 1)
                        <input checked data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Active" data-off="Deactive" type="checkbox" name="status" id="status" checked>
                      @else
                        <input data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Active" data-off="Deactive" type="checkbox" name="status" id="status">
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row form-group" >
                  <div class="col-sm-6"></div>
                  <div class="col-sm-6" align="right">
                      <button type="submit" class="btn btn-info">Update</button>
                      <a href="#Modal_delete" class="btn btn-danger" data-toggle="modal" >Remove</a>
                  </div>    
                </div>
              </div>
            </form>
          @else
            <form  id='category_form' action="/category" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="post">
              <br><div class="col-sm-2"></div>
              <div class="col-sm-8">
                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Category Name</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Enter Category Name">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Discription</label>
                  </div>
                  <div class="col-sm-6">
                      <textarea type="text" class="form-control" id ="remarks" name="remarks"></textarea>
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Status</label>
                  </div>
                  <div class="col-sm-6">
                    <div class="checkbox">
                      <input checked data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Active" data-off="Deactive" type="checkbox" name="status" id="status">
                    </div>
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
  $('#category_form').formValidation({
    framework: 'bootstrap',
        fields: {
            category_name: {
                validators: {
                    notEmpty: {
                        message: 'The Category Name is required'
                    }
                }
            },
         }
    });
});
</script>
@endsection
