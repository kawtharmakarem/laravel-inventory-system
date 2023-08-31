@extends('layouts.admin')
@section('title')
    Add New BillingCategory
@endsection
@section('contentheader')
    Billing Categories
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.sales_material_types.index')}}">Billing Categories</a>
@endsection
@section('contentheaderactive')
   ADD
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Add New BillingCategory</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       
        <form action="{{route('admin.sales_material_types.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            
        <div class="form-group">
            <label for="name">BillingCategory Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" placeholder="Enter Category name" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
             @error('name')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>



        <div class="form-group">
            <label for="active">Status</label>
            <select class="form-control" name="active" id="active">
                <option value="">Select..</option>
                <option  @if(old('active')==1) selected='selected' @endif value="1">active</option>
                <option @if(old('active')==0 and old('active')!='') selected='selected' @endif value="0">inactive</option>
            </select>
             @error('active')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

        

        <div class="form-group text-center">
         <button type="submit" class="btn btn-warning">Add</button>
         <a href="{{route('admin.sales_material_types.index')}}" class="btn btn-secondary">Cancel</a>

        </div>



        </form>

    </div>
</div>
    </div>
</div>

    
@endsection