@extends('layouts.admin')
@section('title')
    Treasuries Shifts
@endsection
@section('contentheader')
Treasuries Transactions
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.admin_shift.index')}}">
        Treasuries shifts
    </a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Treasuries Data Information for users
     </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      {{-- <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.uoms.ajax_search')}}"> --}}
{{-- for search/end --}}

@if(empty($checkExistsOpenShift))
<a href="{{route('admin.admin_shift.create')}}" class="btn btn-warning">Open New Shift</a>

@endif
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">


   

<div class="clearfix"></div>
<div class="col-md-12">

        <div id="ajax_response_searchdiv">

            @if (@isset($data) && !@empty($data) && count($data)>0)
        
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>ShiftCode</th>
                  <th>UserName</th>
                  <th>TreasuryName</th>
                  <th>OPenDate</th>
                  <th>IsFinished</th>
                  <th>ReviewedDate</th>
                  <th>Actions</th>
                  </thead> 
                  <tbody>
                  @foreach ($data as $info)
                  <tr>
                      <td>{{$info->id}}
                      @if($info->is_finished==0 and $info->admin_id==auth()->user()->id) 
                       <br>
                       <span class="text-warning">YourCurrentShift</span>
                      @endif
                      </td>
                      <td>{{$info->admin_name}}</td>
                      <td>{{$info->treasuries_name}}</td>
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
                        
                      </td>
                      
                      <td>@if($info->is_finished==1) it is finished @else it is still open  @endif</td>
                      <td>@if($info->is_delivered_and_reviewed==1)  review is finished @else @if($info->is_finished==1) wating for review @endif  @endif</td>


                      <td>
                          <a href="#" class="btn btn-sm btn-warning">Print financial statement</a>
                          <a href="#" class="btn btn-sm btn-secondary">Brief financial statement</a>
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
<script src="{{asset('assets/admin/js/inv_uoms.js')}}"></script>
    
@endsection