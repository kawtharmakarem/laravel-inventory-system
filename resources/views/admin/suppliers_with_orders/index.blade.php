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
    <a href="{{route('admin.supplierswithorders.index')}}">
      Purchase invoices
    </a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Purchase invoices
      </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.supplierswithorders.ajax_search')}}">
{{-- for search/end --}}

      <a href="{{route('admin.supplierswithorders.create')}}" class="btn btn-warning">Add New</a>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">

        <div class="col-md-4">
          <input checked type="radio" name="searchbyradio" id="searchbyradio" value="auto_serial"><label for="">BySystemCode</label>
          <input checked type="radio" name="searchbyradio" id="searchbyradio" value="doc_num"><label for="">By purchase bill code</label>
        
          <input  type="text" name="search_by_text" id="search_by_text" class="form-control"><br>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="">SearchBySuppliers</label>
              <select name="supplier_code_search" id="supplier_code_search" class="form-control ">
                <option value="all">Search by all suppliers</option>
                @if (@isset($suppliers) && !@empty($suppliers))
                  @foreach ($suppliers as $info)
                    <option value="{{$info->supplier_code}}">{{$info->name}}</option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="">Branchs information</label>
              <select name="store_id_search" id="store_id_search" class="form-control ">
                <option value="all">Search in all braches</option>
                @foreach ($stores as $info)
                <option value="{{$info->id}}">{{$info->name}}</option>  
                @endforeach
              </select>
              </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="">Search from date :</label>
              <input type="date" name="order_date_from" id="order_date_from" class="form-control" value="">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="">Search to date :</label>
              <input type="date" name="order_date_to" id="order_date_to" class="form-control" value="">
            </div>
          </div>

<div class="clearfix"></div>
<div class="col-md-12">

        <div id="ajax_response_searchdiv">

            @if (@isset($data) && !@empty($data) && count($data)>0)
        
  
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>Code</th>
                  <th>Supplier</th>
                  <th>BillDate</th>

                  <th>BillType</th>
                  <th>BillStatus</th>
                  <th>Branch to receive bill</th>
                   <th>Bill_total</th>
                  <th>Actions</th>

          
                  </thead> 
          
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                      <td>{{$info->auto_serial}}</td>
                      <td>{{$info->supplier_name}}</td>
                      <td>{{$info->order_date}}</td>

                      <td>@if ($info->bill_type==1) Cash_Bill @elseif($info->bill_type==2) Deferred_Bill @else Undefined @endif</td>
                      <td>@if ($info->is_approved==1) approved invoice @else open bill @endif</td>

                      <td>{{$info->store_name}}</td>
                    <td>{{$info->total_cost*(1)}}</td>

                      <td>
                        @if($info->is_approved==0)
                          <a href="{{route('admin.supplierswithorders.edit',$info->id)}}" class="btn btn-sm btn-warning">Edit</a>
                          <a href="{{route('admin.supplierswithorders.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
                          @endif
                          <a href="{{route('admin.supplierswithorders.show',$info->id)}}" class="btn btn-sm btn-secondary ">Details</a>

                        </td>
          
                  </tr>
                  
                  @endforeach
                  </tbody>  
                
              </table>

             <br>
             {{ $data->links() }}<br>

        @else
         <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
        @endif
        </div>
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