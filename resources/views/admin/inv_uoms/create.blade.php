@extends('layouts.admin')
@section('title')
    Add New Unit
@endsection
@section('contentheader')
    Units
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.uoms.index')}}">
        Units
    </a>
@endsection
@section('contentheaderactive')
   ADD
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Add New Unit
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       
        <form action="{{route('admin.uoms.store')}}" method="post">
            @csrf
            
        <div class="form-group">
            <label for="name">
                UnitName
                {{-- Store Name --}}
            </label>
            <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" placeholder="Enter Unit Name" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
             @error('name')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

        

        <div class="form-group">
            <label for="active">UnitType</label>
            <select class="form-control" name="is_master" id="is_master">
                <option value="">Select..</option>
                <option  @if(old('is_master')==1) selected='selected' @endif value="1">basic_unit</option>
                <option @if(old('is_master')==0 and old('is_master')!='') selected='selected' @endif value="0">fragment_unit</option>
            </select>
             @error('is_master')
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
         <a href="{{route('admin.stores.index')}}" class="btn btn-secondary">Cancel</a>

        </div>



        </form>

    </div>
</div>
    </div>
</div>

    
@endsection