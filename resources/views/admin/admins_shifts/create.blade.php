@extends('layouts.admin')
@section('title')
    Treasuries Shifts
@endsection
@section('contentheader')
Treasuries Transactions
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.admin_shift.index')}}">
        Treasuries shifts
    </a>
@endsection
@section('contentheaderactive')
   Receiving new shift
@endsection
@section('content')

<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Add new shift to the treasury
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       
        <form action="{{route('admin.admin_shift.store')}}" method="post">
            @csrf
            
        <div class="form-group">
            <label for="">Treasuries added to my permissions</label>
            <select name="treasuries_id" id="treasuries_id" class="form-control">
                <option selected value="">Please select treasury to start shift</option>
                 @if (@isset($admins_treasuries) && !@empty($admins_treasuries))
                 @foreach ($admins_treasuries as $info)
                 <option value="{{$info->treasuries_id}}" @if($info->avaliable==false) disabled @endif>{{$info->treasuries_name}} @if($info->avaliable==false) (The treasury is currently unavailable to be used by another user) @endif</option>
                     
                 @endforeach
                     
                 @endif
            </select>

             @error('treasuries_id')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

    
        <div class="form-group text-center">
         <button type="submit" class="btn btn-warning">Add</button>
         <a href="{{route('admin.admin_shift.index')}}" class="btn btn-secondary">Cancel</a>

        </div>



        </form>

    </div>
</div>
    </div>
</div>

    
@endsection