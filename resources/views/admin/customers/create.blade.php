
@extends('layouts.admin')
@section('title')
Add new customer account
@endsection
@section('contentheader')
Accounts
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.customers.index')}}">
      Customers
    </a>
@endsection
@section('contentheaderactive')
   ADD
@endsection
@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Add new customer account
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <form action="{{route('admin.customers.store')}}" method="post">
    <div class="row">
    @csrf

        
    <div class="col-md-6">
      <div class="form-group">
       <label for="name">Customer_Name</label>
             <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" placeholder="Enter customer name"/>
              @error('name')
                <span class="text-danger">{{$message}}</span>  
              @enderror
         </div>
     </div>
    
       

        
     <div class="col-md-6">
      <div class="form-group">
       <label for="start_balance_status">Start balance status</label>
       <select class="form-control" name="start_balance_status" id="start_balance_status">
        <option value="">Select..</option>
        <option  @if(old('start_balance_status')==1) selected='selected' @endif value="1">credit</option>
        <option  @if(old('start_balance_status')==2) selected='selected' @endif value="2">debit</option>
        <option  @if(old('start_balance_status')==3) selected='selected' @endif value="3">balanced</option>
       </select>
     @error('start_balance_status')
        <span class="text-danger">{{$message}}</span>  
    @enderror
         </div>
     </div>   


     <div class="col-md-6">
      <div class="form-group">
       <label for="start_balance">start balance</label>
             <input type="text" id="start_balance" name="start_balance" class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'')" value="{{old('start_balance')}}" placeholder="Enter start balance"/>
              @error('start_balance')
                <span class="text-danger">{{$message}}</span>  
              @enderror
         </div>
     </div>

        
                    
     <div class="col-md-6">
      <div class="form-group">
       <label for="address">Address</label>
             <input type="text" id="address" name="address" class="form-control" value="{{old('address')}}" placeholder="Enter customer address"/>
              @error('address')
                <span class="text-danger">{{$message}}</span>  
              @enderror
         </div>
     </div>

      {{-- oninput="this.value=this.value.replace(/[^0-9.]/g,'')"  --}}

       
      <div class="col-md-6">
        <div class="form-group">
         <label for="notes">Notes</label>
               <input type="text" id="notes" name="notes" class="form-control" value="{{old('notes')}}" placeholder="Enter your notes"/>
                @error('notes')
                  <span class="text-danger">{{$message}}</span>  
                @enderror
           </div>
       </div>

       <div class="col-md-6">
        <div class="form-group">
         <label for="phones">Phones</label>
               <input type="text" id="phones" name="phones" class="form-control" value="{{old('phones')}}" placeholder="Enter your phones"/>
                @error('phones')
                  <span class="text-danger">{{$message}}</span>  
                @enderror
           </div>
       </div>

       <div class="col-md-6">
        <div class="form-group">
            <label for="active">ActivationCase</label>
              <select class="form-control" name="active" id="active">
                      <option value="">Select..</option>
                      <option  @if(old('active')==1 || old('active')=='') selected='selected' @endif value="1">Yes</option>
      
                      <option @if(old('active')==0 and old('active')!='') selected='selected' @endif  value="0">No</option>
      
                  </select>
                   @error('active')
                     <span class="text-danger">{{$message}}</span>  
                   @enderror
              </div>    
           </div> 

     
    </div>
        <div class="form-group text-center">
         <button  id="do_add_item_cardd" type="submit" class="btn btn-warning">Add</button>
         <a href="{{route('admin.customers.index')}}" class="btn btn-secondary">Cancel</a>

        </div>
     </div>



        </form>
   

    </div>
</div>
    
    
@endsection
<div class="clearfix"></div>


@section('script')
<script src="{{ asset('assets/admin/js/customers.js') }}"></script>

@endsection

