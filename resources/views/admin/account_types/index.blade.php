@extends('layouts.admin')
@section('title')
    Accounts
@endsection
@section('contentheader')
AccountTypes
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.accounttypes.index')}}">
        AccountTypes
    </a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        AccountTypes Information
    </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
{{-- for search/end --}}

    </div>

    <!-- /.card-header -->
    <div class="card-body">
      
       
        <div id="ajax_response_searchdiv">

            @if (@isset($data) && !@empty($data) && count($data)>0)
        
            @php
                $i=1;
            @endphp
  
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>#</th>
                  <th>
                    AccountName
                </th>
                  <th>Status</th>

                  <th>IsAddedFromInternalScreen</th>

          
                  </thead> 
          
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                      <td>{{$i}}</td>
                      <td>{{$info->name}}</td>
                      
                     <td>@if($info->active==1)
                        Active  
                      @else
                      InActive
                          
                      @endif
                    </td>

                    <td>@if($info->relatedinternalaccounts==1)
                       No and it's Added by accounts screen  
                      @else
                      yes and added by his screen
                          
                      @endif
                    </td>
                     
                  </tr>
                  @php
                      $i++;
                  @endphp
                  @endforeach
                  </tbody>  
                
              </table>

             

        @else
         <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
        @endif
            
            </div>
     

    </div>
</div>
    </div>
</div>

    
@endsection
