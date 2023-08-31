@extends('layouts.admin')
@section('title')
Purchase
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
Transactions
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.supplierswithorders.index')}}"> Purchase bills</a>
@endsection
@section('contentheaderactive')
   Edit
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Edit purchase bill form supplier</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        @if (@isset($data) && !@empty($data))
        @if($data['is_approved']==0)

       <form action="{{route('admin.supplierswithorders.update',$data['id'])}}" method="post">
        @csrf
         
         <div class="form-group">
                <label for="order_date">bill date</label>
                <input type="date" name="order_date" id="order_date"  value="{{old('order_date',$data['order_date'])}}" class="form-control" placeholder="bill date">
              @error('order_date')
              <span class="text-danger">{{$message}}</span>
                  @enderror
            </div>


            <div class="form-group">
                <label for="doc_num">The bill number registered in the purchase bill</label>
                <input type="text" name="doc_num" id="doc_num" value="{{old('doc_num',$data['doc_num'])}}" class="form-control" placeholder="bill number">
              @error('doc_num')
              <span class="text-danger">{{$message}}</span>
                   @enderror
            </div>


        <div class="form-group">
            <label for="supplier_code">Supplier name</label>
            <select name="supplier_code" id="supplier_code" class="form-control select2">
             <option value="">select supplier name</option>
             @if (@isset($suppliers) && !empty($suppliers) )
             @foreach ($suppliers as $info )
             <option {{old('supplier_code',$data['supplier_code'])==$info->supplier_code ? 'selected':''}} value="{{$info->supplier_code}}">{{$info->name}}</option>
            @endforeach
            @endif
            </select>
            @error('supplier_code')
               <span class="text-danger">{{$message}}</span> 
            @enderror
        </div>

        
        <div class="form-group">
            <label for="bill_type">bill type</label>
            <select class="form-control select2" name="bill_type" id="bill_type">
                <option {{old('bill_type',$data['bill_type'])==1 ? 'selected':''}} value="1">Cash bill</option>
                <option {{old('bill_type',$data['bill_type'])==2 ? 'selected':''}} value="2">Deferred bill</option>
            </select>
             @error('bill_type')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>

        <div class="form-group">
            <label for="store_id">Branch information</label>
            <select name="store_id" id="store_id" class="form-control select2">
             <option value="">select received branch </option>
             @if (@isset($stores) && !empty($stores))
             @foreach ($stores as $info )
             <option {{old('store_id',$data['store_id'])==$info->id ? 'selected' :''}} value="{{ $info->id }}"> {{ $info->name }} </option>
                  @endforeach
                 @endif
            </select>
            @error('store_id')
               <span class="text-danger">{{$message}}</span> 
            @enderror
        </div>


        <div class="form-group">
            <label for="notes">Notes</label>
            <input type="text" name="notes" id="notes" value="{{old('notes',$data['notes'])}}" class="form-control" placeholder="Enter your notes">
          @error('notes')
          <span class="text-danger">{{$message}}</span>
              
          @enderror
        </div>
           

    
    
    
      <div class="form-group text-center">
         <button type="submit" class="btn btn-warning">SaveUpdates</button>
         <a href="{{route('admin.supplierswithorders.index')}}" class="btn btn-secondary">Cancel</a>
        </div>
       </form>



        @else
         <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
        @endif

        @else
        <div class="alert alert-danger">Sorry ! you cann't update closed an archived bill !!!</div>
        @endif

    </div>
     </div>
    </div>
</div>

    
@endsection
@section('script')

<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/admin/js/supplier_with_orders.js')}}"></script>
<script>

 //Initialize Select2 Elements
 $('.select2').select2({
   theme: 'bootstrap4'
 });


</script>
@endsection
