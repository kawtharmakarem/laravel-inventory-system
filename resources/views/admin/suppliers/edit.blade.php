@extends('layouts.admin')
@section('title')
Edit suppliers accounts
@endsection
@section('contentheader')
Accounts
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.suppliers.index')}}">
      Suppliers accounts
    </a>
@endsection
@section('contentheaderactive')
   Edit
@endsection
@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Edit suppliers accounts
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <form action="{{route('admin.suppliers.update',$data['id'])}}" method="post">
    <div class="row">
    @csrf

        
        <div class="col-md-6">
         <div class="form-group">
          <label for="name">Supplier name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name',$data['name'])}}" placeholder="Enter supplier name"/>
                 @error('name')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="suppliers_categories_id">SupplierCategory</label>
            <select name="suppliers_categories_id" id="suppliers_categories_id" class="form-control">
              <option value="">select category..</option>
              @if (@isset($suppliers_categories) && !@empty($suppliers_categories))
               @foreach ($suppliers_categories as $info)
                <option {{old('suppliers_categories_id',$data['suppliers_categories_id'])==$info->id ? 'selected' : ''}} value="{{$info->id}}">{{$info->name}}</option> 
               @endforeach 
              @endif
            </select>
            @error('suppliers_categories_id')
              <span class="text-danger">{{$message}}</span>
            @enderror
          </div>
        </div>
       
        <div class="col-md-6">
          <div class="form-group">
           <label for="address">Address</label>
                 <input type="text" id="address" name="address" class="form-control" value="{{old('address',$data['address'])}}" placeholder="Enter customer address"/>
                  @error('address')
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
         <a href="{{route('admin.suppliers.index')}}" class="btn btn-secondary">Cancel</a>
        </div>
 
 
 
         </form>
    
 
     </div>
 </div>
    
    
@endsection

@section('script')
<script src="{{ asset('assets/admin/js/suppliers.js') }}"></script>

@endsection
