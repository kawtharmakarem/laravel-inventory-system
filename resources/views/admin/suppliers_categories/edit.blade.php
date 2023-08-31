@extends('layouts.admin')
@section('title')
    Update suppliers category
@endsection
@section('contentheader')
    Accounts
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.suppliers_categories.index')}}">SuppliersCategories</a>
@endsection
@section('contentheaderactive')
   Update
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Update Suppliers</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
       
        <form action="{{route('admin.suppliers_categories.update',$data['id'])}}" method="post">
            @csrf
         
            <div class="form-group">
                <label for="name">Supplier category name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name',$data['name'])}}" placeholder="Enter suppliers category name" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
                 @error('name')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
    
    
            <div class="form-group">
                <label for="active">ActivationCase</label>
                <select class="form-control" name="active" id="active">
                    <option value="">Select..</option>
                    <option {{old('active',$data['active'])==1 ? 'selected':''}} value="1">active</option>
                    <option {{old('active',$data['active'])==0 ? 'selected':''}} value="0">inactive</option>
                </select>
                 @error('active')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
    
      <div class="form-group text-center">
         <button type="submit" class="btn btn-warning">SaveUpdates</button>
         <a href="{{route('admin.suppliers_categories.index')}}" class="btn btn-secondary">Cancel</a>
        </div>



        </form>

        @else
         <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
        @endif

    </div>
</div>
    </div>
</div>

    
@endsection