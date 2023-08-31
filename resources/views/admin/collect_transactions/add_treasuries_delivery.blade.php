@extends('layouts.admin')
@section('title')
    Settings
@endsection
@section('contentheader')
    Treasuries
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.treasuries.index')}}">Sub-Treasuries that can be delivered</a>
@endsection
@section('contentheaderactive')
   ADD
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Add Sub_Treasuries to </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       
        <form action="{{route('admin.treasuries.store_treasuries_delivery',$data['id'])}}" method="post" enctype="multipart/form-data">
            @csrf
            
        <div class="form-group">
            <label for="name">select sub_treasury</label>
            <select name="treasuries_can_delivery_id" id="treasuries_can_delivery_id" class="form-control">
            <option value="">select treasury</option>
            @if (@isset($treasuries) && !@empty($treasuries))
             @foreach ($treasuries as $info )
               <option @if(old('treasuries_can_delivery_id')==$info->id) selected='selected' @endif value="{{$info->id}}">{{$info->name}}</option>  
             @endforeach   
            @endif
            </select>
             @error('treasuries_can_delivery_id')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

        
        <div class="form-group text-center"><br>
         <button type="submit" class="btn btn-warning">Add</button>
         <a href="{{route('admin.treasuries.index')}}" class="btn btn-secondary">Cancel</a>

        </div>



        </form>

    </div>
</div>
    </div>
</div>

    
@endsection