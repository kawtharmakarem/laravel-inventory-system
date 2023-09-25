@extends('layouts.admin')
@section('title')
Edit Account
@endsection
@section('contentheader')
FinancialAccounts
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.accounts.index')}}">
      FinancialAccounts
    </a>
@endsection
@section('contentheaderactive')
   Edit
@endsection
@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Edit financial accounts
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <form action="{{route('admin.accounts.update',$data['id'])}}" method="post">
    <div class="row">
    @csrf

        
        <div class="col-md-6">
         <div class="form-group">
          <label for="name">Financial account name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name',$data['name'])}}" placeholder="Enter account name"/>
                 @error('name')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>
       

        
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_type">Account type</label>
                <select name="account_type" id="account_type" class="form-control">
                <option value="">select....</option>
                @if (@isset($account_type) && !@empty($account_type))
                 @foreach ($account_type as $info )
                   <option {{old('account_type',$data['account_type'])==$info->id ? 'selected':''}} value="{{$info->id}}">{{$info->name}}</option>  
                 @endforeach   
                @endif
                </select>
                 @error('account_type')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
              <label for="is_parent">Is_parent_account</label>
                <select class="form-control" name="is_parent" id="is_parent">
                        <option value="">Select..</option>
                        <option  {{old('is_parent',$data['is_parent'])==1 ? 'selected':''}} value="1">yes</option>
                        <option  {{old('is_parent',$data['is_parent'])==0 ? 'selected':''}} value="0">no</option>
                    </select>
                     @error('is_parent')
                       <span class="text-danger">{{$message}}</span>  
                     @enderror
                </div>    
             </div> 

        
                    
<div class="col-md-6" id="parentDiv" @if(old('is_parent',$data['is_parent'])==1) style="display: none;" @endif>
  <div class="form-group">
      <label for="parent_account_number">Parent accounts</label>
        <select class="form-control" name="parent_account_number" id="parent_account_number">
                <option value="">Select parent account</option>
                @if (@isset($parent_account_type) && !@empty($parent_account_type))
                 @foreach ($parent_account_type as $info )
                   <option {{old('parent_account_number',$data['parent_account_number'])==$info->account_number ? 'selected':''}} value="{{$info->account_number}}">{{$info->name}}</option>  
                 @endforeach   
                @endif
            </select>
             @error('parent_account_number')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>    
     </div> 


     <div class="col-md-6">
      <div class="form-group">
       <label for="notes">Notes</label>
             <input type="text" id="notes" name="notes" class="form-control" value="{{old('notes',$data['notes'])}}" placeholder="Enter your notes"/>
              @error('notes')
                <span class="text-danger">{{$message}}</span>  
              @enderror
         </div>
     </div>


<div class="col-md-6">
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
     </div> 

    </div>

        
          <div class="form-group text-center">
         <button  id="do_add_item_cardd" type="submit" class="btn btn-warning">SaveUpdates</button>
         <a href="{{route('admin.accounts.index')}}" class="btn btn-secondary">Cancel</a>
        </div>
 
 
 
         </form>
    
 
     </div>
 </div>
    
    
@endsection

@section('script')
<script src="{{ asset('assets/admin/js/accounts.js') }}"></script>

@endsection
