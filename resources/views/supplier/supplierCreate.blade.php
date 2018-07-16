@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Supplier</b></h3></div>
      <!-- supplier details -->
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-8">
              <small> Create  / Update / Delete Supplier</small>
            </div>
            <div class="col-sm-4" align="right">
              <a href="/supplier" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Lookup</a>
            </div>
          </div>
        </div>
        <div class="panel-body">

          @if(isset($supplier))
            <form  id='supplier_form' action="/supplier/{{$supplier->id}}" method="post" class="form-horizontal">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="PUT">
              <br><div class="col-sm-2"></div>

              <div class="col-sm-8">
                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Supplier Name</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="supplier_name" id="supplier_name" placeholder="Enter Supplier Name" value="{{$supplier->supplier_name}}">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Address</label>
                  </div>
                  <div class="col-sm-6">
                      <textarea type="text" class="form-control" id ="address" name="address">{{$supplier->supplier_address}}</textarea>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Contact Number 1 </label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" minlength="10" maxlength="10" data-fv-stringlength-message="The Phone Number must be more than 1 and less than 10 characters" class="form-control" name="contact_number_1" id="contact_number_1" placeholder="Enter Conatct Number" onkeypress="return numberOnly(event)" value="{{$supplier->contact_number_1}}">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Contact Number 2 </label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" minlength="10" maxlength="10" data-fv-stringlength-message="The Phone Number must be more than 1 and less than 10 characters" class="form-control" name="contact_number_2" id="contact_number_2" placeholder="Enter Conatct Number" onkeypress="return numberOnly(event)" value="{{$supplier->contact_number_2}}">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Contact Person</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Enter Contact Person Name"  value="{{$supplier->contact_person}}">
                  </div>  
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Email Address</label>
                  </div>
                  <div class="col-sm-6">
                    <input type="email" class="form-control" name="email_address" id="email_address" placeholder="Enter Email Address" value="{{$supplier->email}}">
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Status</label>
                  </div>
                  <div class="col-sm-6">
                    <div class="checkbox">
                      @if($supplier->status == 1)
                        <input checked data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Active" data-off="Deactive" type="checkbox" name="status" id="status" checked>
                      @else
                        <input data-toggle="toggle" data-onstyle="info" data-offstyle="danger" data-on="Active" data-off="Deactive" type="checkbox" name="status" id="status">
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-sm-6">
                    <label class="text-right">Remarks</label>
                  </div>
                  <div class="col-sm-6">
                      <textarea type="text" class="form-control" id ="remarks" name="remarks">{{$supplier->remarks}}</textarea>
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
          <form  id='supplier_form' action="/supplier" method="post" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post">
            <br><div class="col-sm-2"></div>
            <div class="col-sm-8">

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Supplier Name</label>
                </div>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="supplier_name" id="supplier_name" placeholder="Enter Supplier Name">
                </div>  
              </div>

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Address</label>
                </div>
                <div class="col-sm-6">
                    <textarea type="text" class="form-control" id ="address" name="address"></textarea>
                </div>  
              </div>

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Contact Number 1 </label>
                </div>
                <div class="col-sm-6">
                  <input type="text" minlength="10" maxlength="10" data-fv-stringlength-message="The Phone Number must be more than 1 and less than 10 characters" class="form-control" name="contact_number_1" id="contact_number_1" placeholder="Enter Conatct Number" onkeypress="return numberOnly(event)">
                </div>  
              </div>

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Contact Number 2 </label>
                </div>
                <div class="col-sm-6">
                  <input type="text" minlength="10" maxlength="10" data-fv-stringlength-message="The Phone Number must be more than 1 and less than 10 characters" class="form-control" name="contact_number_2" id="contact_number_2" placeholder="Enter Conatct Number" onkeypress="return numberOnly(event)">
                </div>  
              </div>


              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Contact Person</label>
                </div>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Enter Contact Person Name">
                </div>  
              </div>

              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="text-right">Email Address</label>
                </div>
                <div class="col-sm-6">
                  <input type="email" class="form-control" name="email_address" id="email_address" placeholder="Enter Email Address">
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
@endsection

@section('script')
<script type="text/javascript">

$(document).ready(function()
{
  $('#supplier_form').formValidation({
    framework: 'bootstrap',
        fields: {
            supplier_name: {
                validators: {
                    notEmpty: {
                        message: 'The Supplier Name is required'
                    }
                }
            },
            address: {
                validators: {
                    notEmpty: {
                        message: 'The Address is required'
                    }
                }
            },
            contact_number_1: {
                validators: {
                    notEmpty: {
                        message: 'The Contact Number 1  is required'
                    }
                }
            },
            contact_person: {
                validators: {
                    notEmpty: {
                        message: 'The Contact person is required'
                    }
                }
            },
         }
    });
});
</script>
@endsection
