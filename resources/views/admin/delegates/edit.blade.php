@extends('layouts.admin')
@section('title')
Edit delegates accounts
@endsection
@section('contentheader')
Accounts
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.delegates.index')}}">
      Delegates accounts
    </a>
@endsection
@section('contentheaderactive')
   Edit
@endsection
@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Edit delegates accounts
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <form action="{{route('admin.delegates.update',$data['id'])}}" method="post">
    <div class="row">
    @csrf

        
        <div class="col-md-6">
         <div class="form-group">
          <label for="name">Delegate name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name',$data['name'])}}" placeholder="Enter delegate name"/>
                 @error('name')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>

       
       
        <div class="col-md-6">
          <div class="form-group">
           <label for="address">Address</label>
                 <input type="text" id="address" name="address" class="form-control" value="{{old('address',$data['address'])}}" placeholder="Enter delegate address"/>
                  @error('address')
                    <span class="text-danger">{{$message}}</span>  
                  @enderror
             </div>
         </div>
        
         <div class="col-md-6">
          <div class="form-group">
           <label for="phones">Phones</label>
                 <input type="text" id="phones" name="phones" class="form-control" value="{{old('phones',$data['phones'])}}" placeholder="Enter phones"/>
                  @error('phones')
                    <span class="text-danger">{{$message}}</span>  
                  @enderror
             </div>
         </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="">Delegate percent type</label>
            <select name="percent_type" id="percent_type" class="form-control">
              <option value="">Select type</option>
              <option  {{old('percent_type',$data['percent_type'])==1 ? 'selected' : ''}} value="1">Fixed wage</option>
               <option {{old('percent_type',$data['percent_type'])==2 ? 'selected':''}} value="2">percent wage</option>
            </select>
            @error('percent_type')
            <span class="text-danger">{{$message}}</span>
              @enderror
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="percent_sales_commission_kataei">Delegate commission in sales/Popular price</label>
            <input name="percent_sales_commission_kataei" id="percent_sales_commission_kataei" value="{{old('percent_sales_commission_kataei',$data['percent_sales_commission_kataei'])*1}}"
            class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
           @error('percent_sales_commission_kataei')
            <span class="text-danger">{{$message}}</span> 
           @enderror
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="percent_sales_commission_nosjomla">Delegate commission in sales/Half wholesale price</label>
            <input name="percent_sales_commission_nosjomla" id="percent_sales_commission_nosjomla" value="{{old('percent_sales_commission_nosjomla',$data['percent_sales_commission_nosjomla'])*1}}"
            class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
           @error('percent_sales_commission_nosjomla')
            <span class="text-danger">{{$message}}</span> 
           @enderror
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="percent_sales_commission_jomla">Delegate commission in sales/Wholesale price</label>
            <input name="percent_sales_commission_jomla" id="percent_sales_commission_jomla" value="{{old('percent_sales_commission_jomla',$data['percent_sales_commission_jomla'])*1}}"
            class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
           @error('percent_sales_commission_jomla')
            <span class="text-danger">{{$message}}</span> 
           @enderror
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="percent_collect_commission">Percent collect commission</label>
            <input name="percent_collect_commission" id="percent_collect_commission" value="{{old('percent_collect_commission',$data['percent_collect_commission'])*1}}"
            class="form-control" oninput="this.value=this.value.replace(/[^0-9.]/g,'');">
           @error('percent_collect_commission')
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
                <option {{old('active',$data['active'])==1 ? 'selected':''}} value="1">Yes</option>

                <option {{old('active',$data['active'])==0 ? 'selected':''}} value="0">No</option>
            </select>
             @error('active')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>    
     </div> 

    </div>

        
          <div class="form-group text-center">
         <button  id="do_add_item_cardd" type="submit" class="btn btn-warning">SaveUpdates</button>
         <a href="{{route('admin.delegates.index')}}" class="btn btn-secondary">Cancel</a>
        </div>
 
 
 
         </form>
    
 
     </div>
 </div>
    
    
@endsection

@section('script')
<script src="{{ asset('assets/admin/js/suppliers.js') }}"></script>

@endsection
