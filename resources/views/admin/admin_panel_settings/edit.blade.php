@extends('layouts.admin')
@section('title')
    Update Settings
@endsection
@section('contentheader')
    Settings
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.adminpanelsetting.index')}}">Settings</a>
@endsection
@section('contentheaderactive')
   Update
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Update System Informations</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
       
        <form action="{{route('admin.adminpanelsetting.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            
        <div class="form-group">
            <label for="system_name">Company Name</label>
            <input type="text" id="system_name" name="system_name" class="form-control" value="{{$data['system_name']}}" placeholder="Enter company name" oninvalid="setcustomValidity('please enter company name')" onchange="try{setCustomValidity('')}catch(e){}"/>
             @error('system_name')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" class="form-control" value="{{$data['address']}}" placeholder="Enter Company Address" oninvalid="setcustomValidity('please enter company address')" onchange="try{setCustomValidity('')}catch(e){}"/>
            @error('address')
              <span class="text-danger">{{$message}}</span>  
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{$data['phone']}}" placeholder="Enter Company PhoneNumber" oninvalid="setcustomValidity('please enter company phone number')" onchange="try{setCustomValidity('')}catch(e){}"/>
            @error('phone')
              <span class="text-danger">{{$message}}</span>  
            @enderror
        </div>


                   
            <div class="form-group">
                <label for="customer_parent_account_number">Parent accounts</label>
                  <select class="form-control" name="customer_parent_account_number" id="customer_parent_account_number">
                          <option value="">Parent account for customers</option>
                          @if (@isset($parent_accounts) && !@empty($parent_accounts))
                           @foreach ($parent_accounts as $info )
                             <option @if(old('customer_parent_account_number',$data['customer_parent_account_number'])==$info->account_number) selected='selected' @endif value="{{$info->account_number}}">{{$info->name}}</option>  
                           @endforeach   
                          @endif
                      </select>
                       @error('customer_parent_account_number')
                         <span class="text-danger">{{$message}}</span>  
                       @enderror
                  </div>  
                  
                  <div class="form-group">
                    <label for="supplier_parent_account_number">Parent accounts</label>
                      <select class="form-control" name="supplier_parent_account_number" id="supplier_parent_account_number">
                              <option value="">Parent account for suppliers</option>
                              @if (@isset($parent_accounts) && !@empty($parent_accounts))
                               @foreach ($parent_accounts as $info )
                                 <option @if(old('supplier_parent_account_number',$data['supplier_parent_account_number'])==$info->account_number) selected='selected' @endif value="{{$info->account_number}}">{{$info->name}}</option>  
                               @endforeach   
                              @endif
                          </select>
                           @error('supplier_parent_account_number')
                             <span class="text-danger">{{$message}}</span>  
                           @enderror
                      </div>    


        <div class="form-group">
            <label for="general_alert">Alert Message</label>
            <input type="text" id="general_alert" name="general_alert" class="form-control" value="{{$data['general_alert']}}" placeholder="Enter Alert Messsage" oninvalid="setcustomValidity('please enter alert message')" onchange="try{setCustomValidity('')}catch(e){}"/>
        </div>

        <div class="form-group">
            <label for="logo">Company Logo</label>
            <div class="image">
                <img src="{{asset('assets/admin/uploads').'/'.$data['photo']}}" alt="Logo" class="custom_img">
                <button type="button" class="btn btn-sm btn-danger" id="update_image">ChangeImage</button>
                <button type="button" class="btn btn-sm btn-danger" style="display: none" id="cancel_update_image">Cancel</button>
            </div>
            <div id="oldimage"></div>

        </div>

        <div class="form-group text-center">
         <button type="submit" class="btn btn-warning">SaveUpdates</button>
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