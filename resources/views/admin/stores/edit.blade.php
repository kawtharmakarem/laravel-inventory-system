@extends('layouts.admin')
@section('title')
    Update Branch Settings
@endsection
@section('contentheader')
    Branchs
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.stores.index')}}">Branchs</a>
@endsection
@section('contentheaderactive')
   Update
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Update Branch Settings</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
       
        <form action="{{route('admin.stores.update',$data['id'])}}" method="post">
            @csrf
         
            <div class="form-group">
                <label for="name">Branch Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name',$data['name'])}}" placeholder="Enter Branch name" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
                 @error('name')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
            <div class="form-group">
                <label for="phones">Phone_Numbers</label>
                <input type="text" id="phones" name="phones" class="form-control" value="{{old('phones',$data['phones'])}}" placeholder="Enter Phone number" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
                 @error('phones')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>

            <div class="form-group">
                <label for="phones">Address</label>
                <input type="text" id="Address" name="address" class="form-control" value="{{old('address',$data['address'])}}" placeholder="Address" oninvalid="setcustomValidity('please enter Treasury name')" onchange="try{setCustomValidity('')}catch(e){}"/>
                 @error('address')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
    
    
    
            <div class="form-group">
                <label for="active">Status</label>
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
         <a href="{{route('admin.stores.index')}}" class="btn btn-secondary">Cancel</a>
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