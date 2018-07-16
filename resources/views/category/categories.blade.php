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
            <small>Lookup ( All Categories )</small>
            </div>
            <div class="col-sm-4" align="right">
               <a href="/category/create" class="btn btn-info "><i class="fa fa-plus" aria-hidden="true"></i> new Category</a>
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
                <th class="text-center" style="border: 1px solid #dddddd;">Create Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Update Date</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Status</th>
                <th class="text-center" style="border: 1px solid #dddddd;">Action</th>
              </tr>
            </thead>
            <tbody>
            @if(isset($categories))
              @foreach ($categories as $category)
                <tr>
                  <td class="text-left" style="border: 1px solid #dddddd;">{{$category->cate_name}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{$category->created_at}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{$category->updated_at}}</td>
                  <td class="text-center" style="border: 1px solid #dddddd;">{{($category->status == 1 ? "Yes" : "No")}}</td>
                  <form action="/category/{{$category->id}}" class="pull-right" method="POST">
                     {{ csrf_field() }}
                     <td class="text-center" style="border: 1px solid #dddddd;">
                      <a class="btn btn-warning" id='{{$category->id}}' href="category/{{$category->id}}">Edit</a>
                      <input type="submit" name="delete" value="Remove" class="btn btn-danger">
                      <input type="hidden" name="_method" value="DELETE">
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
@endsection