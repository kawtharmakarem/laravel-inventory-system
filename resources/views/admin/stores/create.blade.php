@extends('layouts.admin')
@section('title')
    Add New Branch
    {{-- Add New Store --}}
@endsection
@section('contentheader')
    Branchs
    {{-- Stores --}}
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.stores.index')}}">
        Branchs
        {{-- Stores --}}
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
        Add New Branch
        {{-- Add New Store --}}
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       
        <form action="{{route('admin.stores.store')}}" method="post">
            @csrf
            
        <div class="form-group">
            <label for="name">
                Branch_Name
                {{-- Store Name --}}
            </label>
            <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" placeholder="Enter Branch Name" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
             @error('name')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

        <div class="form-group">
            <label for="phones">
               Phone_Numbers 
            </label>
            <input type="text" id="phones" name="phones" class="form-control" value="{{old('phones')}}" placeholder="Enter Phone Number" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
             @error('phones')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

        <div class="form-group">
            <label for="phones">
               Branch_Address 
               {{-- Store_address --}}
            </label>
            <input type="text" id="address" name="address" class="form-control" value="{{old('address')}}" placeholder="Enter Address" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
             @error('address')
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