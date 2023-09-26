@extends('layouts.admin')
@section('title')
    Accounts
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
   view 
@endsection
@section('content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        FinancialAccounts Information
    </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.accounts.ajax_search')}}">
{{-- for search/end --}}

      <a href="{{route('admin.accounts.create')}}" class="btn btn-warning">Add New</a>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
       <div class="row">

     <div class="col-md-3">
      
      <input checked type="radio" name="searchbyradio" id="searchbyradio" value="account_number"><label for="">ByAccountNumber</label>
      <input  type="radio" name="searchbyradio" id="searchbyradio" value="name"><label for="">ByName</label>
      <input type="text"  id="search_by_text"  name="search_by_text" placeholder="account number - name" class="form-control"><br>
    </div>   
  
<div class="col-md-3">
  <div class="form-group">
    <label for="account_type_search">ByAccountType</label>
    <select name="account_type_search" id="account_type_search" class="form-control">
      <option value="all">all</option>
      @if (@isset($account_type) and !empty($account_type))
       @foreach ($account_type as $info )
       <option  value="{{$info->id}}">{{$info->name}}</option>
         
       @endforeach 
      @endif
    </select>
  </div>
</div>
  
<div class="col-md-3">
  <div class="form-group">
    <label for="is_parent_search">IsParent</label>
    <select class="form-control" name="is_parent_search" id="is_parent_search">
      <option value="all">all</option>
      <option value="1">Yes</option>
      <option value="0">No</option>
    </select>

  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <label for="active_search">IsActive</label>
    <select class="form-control" name="active_search" id="active_search">
      <option value="all">all</option>
      <option value="1">active</option>
      <option value="0">inactive</option>
    </select>

  </div>
</div>

<div class="clearfix"></div>
       
        <div id="ajax_response_searchdiv" class="col-md-12">
           @if (@isset($data) && !@empty($data) && count($data)>0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th> Name</th>
                  <th>Account_number</th>
                  <th>Account_Type</th>
                  <th>Is_Parent</th>
                  <th>Parent_Account</th>
                  <th>Balance</th>
                  <th>ActivationCase</th>
                  <th>Actions</th>
                </thead> 
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                  <td>{{$info->name}}</td>
                  <td>{{$info->account_number}}</td>
                  <td>{{$info->account_types_name}}</td>
                  <td>@if($info->is_parent==1) Yes  @else No @endif</td>
                  <td>{{$info->parent_account_name}}</td>
                  <td>
                    @if($info->is_parent==0)
                    @if ($info->current_balance>0)
                    Debit ({{$info->current_balance*1}}) S.p
                    @elseif ($info->current_balance<0)
                    Credit ({{$info->current_balance*(-1)}}) S.p
                    @else
                    Balanced (0) S.P
                    @endif
                    @else
                    من ميزان المراجعة
                    @endif
                  </td>
                  <td>@if($info->active==1) active @else inactive @endif</td>
                  <td>
                    @if( $info->relatedinternalaccounts==0)
                    <a href="{{route('admin.accounts.edit',$info->id)}}" class="btn btn-sm btn-warning" style="margin-bottom: 1px">Edit</a>
                  @else
                   update from it's own screen
                  @endif
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
<script src="{{asset('assets/admin/js/accounts.js')}}"></script>
    
@endsection