@extends('layouts.admin')
@section('title')
    GeneralSettings
@endsection
@section('contentheader')
Billing Categories
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.sales_material_types.index')}}">Billing Categories</a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Billing Categories Informations</h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.treasuries.ajax_search')}}">
{{-- for search/end --}}

      <a href="{{route('admin.sales_material_types.create')}}" class="btn btn-warning">Add New</a>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      
       
        <div id="ajax_response_searchdiv">

            @if (@isset($data) && !@empty($data))
        
            @php
                $i=1;
            @endphp
  
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>#</th>
                  <th>Category_Name</th>
                  <th>Status</th>
                  <th>Created_at</th>
                  <th>Updated_at</th>
                  <th>Actions</th>

          
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
                      <td>
                        @php
                            $dt=new DateTime($info->created_at);
                            $date=$dt->format("Y-m-d");
                            $time=$dt->format("h:i a");
                            $newDateTime=date("A",strtotime($time));
                            // $newDateTimeType=(($newDateTime=='AM')? '.A.M':'.P.M');
                        @endphp
                        {{$date}}
                        {{$time}}
                        {{-- {{$newDateTimeType}} --}}
                        <br>
                        By
                        {{$info->added_by_admin}}

                      </td>

                      <td>
                        @if($info->updated_by>0 and $info->updated_by!=null)
                        @php
                            $dt=new DateTime($info->updated_at);
                            $date=$dt->format("Y-m-d");
                            $time=$dt->format("h:i a");
                            $newDateTime=date("A",strtotime($time));
                            // $newDateTimeType=(($newDateTime=="AM")? '.A.M':'.P.M');

                        @endphp
                        {{$date}}
                        {{$time}}
                        {{-- {{$newDateTimeType}} --}}
                        <br>
                        By
                        {{$info->updated_by_admin}}
                        @else
                        no updated to show
                         @endif
                      </td>


                      <td>
                          <a href="{{route('admin.sales_material_types.edit',$info->id)}}" class="btn btn-sm btn-warning">Edit</a>
                          {{-- <a href="{{route('admin.sales_material_types.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a> --}}
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