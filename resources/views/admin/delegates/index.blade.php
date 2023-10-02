@extends('layouts.admin')
@section('title')
Delegates
@endsection
@section('contentheader')
Accounts
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.delegates.index')}}">
      Delegates        
    </a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Delegates Information
    </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.delegates.ajax_search')}}">
{{-- for search/end --}}

      <a href="{{route('admin.delegates.create')}}" class="btn btn-warning">Add New</a>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
       <div class="row">

     <div class="col-md-4">
      <input checked type="radio" name="searchbyradio" id="searchbyradio" value="delegate_code"><label for="">ByDelg_code</label>

      <input  type="radio" name="searchbyradio" id="searchbyradio" value="account_number"><label for="">ByAccount</label>
      <input  type="radio" name="searchbyradio" id="searchbyradio" value="name"><label for="">ByName</label>
        <input autofocus type="text"  id="search_by_text"  name="search_by_text" placeholder="account_number - delegate_code -name" class="form-control"><br>
    </div>   
 
  
    <div class="clearfix"></div>
       
        <div id="ajax_response_searchdiv" class="col-md-12">
           @if (@isset($data) && !@empty($data) && count($data)>0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th> Name</th>
                  <th>Delegate_code</th>
                  <th>Account_number</th>
                  <th>Balance</th>
                  <th>Address</th>

                  <th>Phones</th>
                  <th>notes</th>
                  <th>ActivationCase</th>
                  <th>Actions</th>
                </thead> 
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                  <td>{{$info->name}}</td>
                  <td>{{$info->delegate_code}}</td>
                  <td>{{$info->account_number}}</td>
                  <td>
                    @if ($info->current_balance>0)
                    Debit ({{$info->current_balance*1}})S.p
                     @elseif ($info->current_balance<0)
                     Credit ({{$info->current_balance*(-1)}})S.P
                     @else
                     Balanced 
                    @endif 
                  </td>
                   
                  
                  <td>{{$info->address}}</td> 
                  <td>{{$info->phones}}</td> 
                  <td>{{$info->notes}}</td>               
                  <td @if($info->active==1) class="bg-secondary" @else class="bg-danger" @endif>@if($info->active==1) active @else inactive @endif</td>
                  <td>
                          <a href="{{route('admin.delegates.edit',$info->id)}}" class="btn btn-sm btn-warning" style="margin-bottom: 1px">Edit</a>
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
<script src="{{asset('assets/admin/js/suppliers.js')}}"></script>
    
@endsection