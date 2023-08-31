@extends('layouts.admin')
@section('title')
    Update Treasury Settings
@endsection
@section('contentheader')
    Treasuries
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.treasuries.index')}}">Treasuries</a>
@endsection
@section('contentheaderactive')
   Update
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Update Treasury Settings</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
       
        <form action="{{route('admin.treasuries.update',$data['id'])}}" method="post" enctype="multipart/form-data">
            @csrf
         
            <div class="form-group">
                <label for="name">Treasury Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name',$data['name'])}}" placeholder="Enter Treasury name" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
                 @error('name')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
            <div class="form-group">
                <label for="master">Is Master</label>
                <select class="form-control" name="is_master" id="is_master">
                    <option  value="">Select..</option>
                    <option {{ old('is_master',$data['is_master'])==1 ? 'selected':''}} value="1">Yes</option>
                    
                    <option {{old('is_master',$data['is_master'])==0 ? 'selected':''}} value="0">No</option>
                </select>
                 @error('is_master')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
            <div class="form-group">
                <label for="last_isal_exchange">The last financial receipt number for exchange</label>
                <input type="text" id="last_isal_exchange" name="last_isal_exchange" class="form-control" value="{{old('last_isal_exchange',$data['last_isal_exchange'])}}" placeholder="Enter The last financial receipt number for exchange" oninvalid="setcustomValidity('please enter the last financial receipt number for exchange')" onchange="try{setCustomValidity('')}catch(e){}" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
                 @error('last_isal_exchange')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
            <div class="form-group">
                <label for="last_isal_collect">The last financial receipt number for collection</label>
                <input type="text" id="last_isal_collect" name="last_isal_collect" class="form-control" value="{{old('last_isal_collect',$data['last_isal_collect'])}}" placeholder="Enter The last financial receipt number for collection " oninvalid="setcustomValidity('please enter the last financial receipt number for collection')" onchange="try{setCustomValidity('')}catch(e){}" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
                 @error('last_isal_collect')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
            <div class="form-group">
                <label for="active">Status</label>
                <select class="form-control" name="active" id="active">
                    <option value="">Select..</option>
                    <option {{old('active',$data['active'])==1 ? 'selected':''}} value="1">active</option>
                    <option {{old('active',$data['active'])==0 ?'selected':''}} value="0">inactive</option>
                </select>
                 @error('active')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
    
      <div class="form-group text-center">
         <button type="submit" class="btn btn-warning">SaveUpdates</button>
         <a href="{{route('admin.treasuries.index')}}" class="btn btn-secondary">Cancel</a>
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