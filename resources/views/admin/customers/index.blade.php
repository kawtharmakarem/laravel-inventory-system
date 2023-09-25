@extends('layouts.admin')
@section('title')
    Customers
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
   view 
@endsection
@section('content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Customers Information
    </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.customers.ajax_search')}}">
{{-- for search/end --}}

      <a href="{{route('admin.customers.create')}}" class="btn btn-warning">Add New</a>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
       <div class="row">

     <div class="col-md-4">
      <input checked type="radio" name="searchbyradio" id="searchbyradio" value="customer_code"><label for="">ByCust_code</label>

      <input  type="radio" name="searchbyradio" id="searchbyradio" value="account_number"><label for="">ByAccount</label>
      <input  type="radio" name="searchbyradio" id="searchbyradio" value="name"><label for="">ByName</label>
        <input autofocus type="text"  id="search_by_text"  name="search_by_text" placeholder="account_number - customer_code -name" class="form-control"><br>
    </div>   
 
  
    <div class="clearfix"></div>
       
        <div id="ajax_response_searchdiv" class="col-md-12">
           @if (@isset($data) && !@empty($data) && count($data)>0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th> Name</th>
                  <th>Customer_code</th>

                  <th>Account_number</th>
                  <th>Balance</th>
                  <th>ActivationCase</th>
                  <th>Actions</th>
                </thead> 
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                  <td>{{$info->name}}</td>
                  <td>{{$info->customer_code}}</td>

                  <td>{{$info->account_number}}</td>
                  <td>
                    @if ($info->is_parent==0)
                    @if ($info->current_balance>0)
                    Debit ({{$info->current_balance*1}})S.p
                     @elseif ($info->current_balance<0)
                     Credit ({{$info->current_balance*(-1)}})S.P
                     @else
                     Balanced 
                    @endif
                    @else
                    من ميزان المراجعة
                      
                    @endif
                  </td>
                  <td>@if($info->active==1) active @else inactive @endif</td>
                  <td>
                          <a href="{{route('admin.customers.edit',$info->id)}}" class="btn btn-sm btn-warning" style="margin-bottom: 1px">Edit</a>
                          <a href="{{route('admin.customers.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
                          <a href="{{route('admin.customers.show',$info->id)}}" class="btn btn-sm btn-info">show</a>
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

    
@endsection
@section('script')
<script src="{{asset('assets/admin/js/customers.js')}}"></script>
    
@endsection