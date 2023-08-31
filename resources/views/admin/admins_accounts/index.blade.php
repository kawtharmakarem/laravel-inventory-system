@extends('layouts.admin')
@section('title')
Permissions
@endsection
@section('contentheader')
Users
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.admins_accounts.index')}}">Users</a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Users Informations</h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.admins_accounts.ajax_search')}}">
{{-- for search/end --}}

      <a href="{{route('admin.admins_accounts.create')}}" class="btn btn-warning">Add New</a>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
        <div class="col-md-4">
            <input type="text" id="search_by_text" placeholder="Search by name" class="form-control"><br>
        </div>
       
        <div id="ajax_response_searchdiv">

            @if (@isset($data) && !@empty($data))
        
            @php
                $i=1;
            @endphp
  
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>#</th>
                  <th>User_Name</th>
                  <th>ActivationCase</th>
                  <th>Actions</th>
          
                  </thead> 
          
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                      <td>{{$i}}</td>
                      <td>{{$info->name}}</td>
                      
                      <td>@if($info->active==1) Active  @else InActive @endif</td>
                      <td>
                          <a href="{{route('admin.admins_accounts.edit',$info->id)}}" class="btn btn-sm btn-warning">Edit</a>
                          <a href="{{route('admin.admins_accounts.details',$info->id)}}" class="btn btn-sm btn-secondary">Special Permissions</a>
                      </td>
          
                  </tr>
                  @php
                      $i++;
                  @endphp
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
<script src="{{asset('assets/admin/js/treasuries.js')}}"></script>
    
@endsection