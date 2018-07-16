@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-11">
      <div style="text-align: center;"><h3><b>Sub Category</b></h3></div>
      <div class="panel panel-info">
        <div class="panel-heading">
         <div class="row">
            <div class="col-sm-8">
            <small>Lookup ( All Sub Categories )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/subCategory/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> new Sub Category</a>
            </div>           
         </div>
        </div>
        <div class="panel-body">
          @if(session('status'))
            <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
            </div>
          @elseif(session('status_delete'))
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status_delete') }}
            </div>
          @endif

          <table id="category_table" class="table table-striped table-hover table-center " cellspacing="0">
            <thead>
              <tr>
                <th class="text-center" style="border: 1px solid #dddddd;">Category Name</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Sub Category Name</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Status</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
              </tr>
            </thead>
            <tbody>
            @if(isset($subCategories))
              @foreach ($subCategories as $subCategory)
                <tr>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$subCategory->cate_name}}</td>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$subCategory->sub_cate_name}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{$subCategory->created_at}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{$subCategory->updated_at}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{($subCategory->status == 1 ? "Yes" : "No")}}</td>
                  <form action="/subCategory/{{$subCategory->id}}" class="pull-right" method="POST">
                     {{ csrf_field() }}
                     <td class="text-center" style="border: 1px solid #dddddd;">
                      <a class="btn btn-warning" id='{{$subCategory->id}}' href="subCategory/{{$subCategory->id}}">Edit</a>
                     </td>
                  </form>
                </tr>
              @endforeach
            @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@include('layouts.deleteModal')
@endsection