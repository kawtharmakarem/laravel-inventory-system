@extends('layouts.admin')
@section('title')
    GeneralSettings
@endsection
@section('contentheader')
    Treasuries
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.treasuries.index')}}">Treasuries</a>
@endsection
@section('contentheaderactive')
   ViewDetails
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">

    <div class="card-header">
      <h3 class="card-title card_title_center">Treasury Details</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
        
      <table id="example2" class="table table-bordered table-hover">
        
        <tr>
            <td class="width30">TreasuryName</td>
            <td>{{$data['name']}}</td>
        </tr>

        <tr>
          <td class="width30">Last_exchange_cheque</td>
          <td>{{$data['last_isal_exchange']}}</td>
      </tr>

      <tr>
        <td class="width30">Last_collect_cheque</td>
        <td>{{$data['last_isal_collect']}}</td>
    </tr>

        <tr>
            <td class="width30">Is_Master</td>
            <td>@if ($data['is_master']==1)
              Yes  
            @else
              No  
            @endif</td>
        </tr>
        
        <tr>
            <td class="width30">TreasuryStatus</td>
            <td>@if ($data['active']==1)
              Active  
            @else
              InActive  
            @endif</td>
        </tr>

        <tr>
          <td class="width30">Added Date</td>
          <td>
               @php
                  $d=new DateTime($data['created_at']);
                  $date=$d->format("Y-m-d");
                  $time=$d->format("h:i");
                  $newDateTime=date("A",strtotime($time));
                  $newDateTimeType=$newDateTime=="AM"?'A.M.':'P.M.';
              @endphp
              {{$date}}
              {{$time}}
              {{$newDateTimeType}}
              By
          {{$data['added_by_admin']}}

          </td>
      </tr>
      
     
        <tr>
            <td class="width30">Date of last Update</td>
            <td>
                
                @if ($data['updated_by']>0 and $data['updated_by']!=null)
                   
                @php
                    $d=new DateTime($data['updated_at']);
                    $date=$d->format("Y-m-d");
                    $time=$d->format("h:i");
                    $newDateTime=date("A",strtotime($time));
                    $newDateTimeType=$newDateTime=="AM"?'A.M.':'P.M.';
                @endphp
                {{$date}}
                {{$time}}
                {{$newDateTimeType}}
                By
            {{$data['updated_by_admin']}}


                @else
                 There is no update   
                @endif
                <a href="{{route('admin.treasuries.edit',$data['id'])}}" class="btn btn-sm btn-warning">Edit</a>
                <a href="{{route('admin.treasuries.index')}}" class="btn btn-sm btn-secondary">back</a>


            </td>
        </tr>
        
      </table>

    <!--treasuries_delivery-->
    <div class="card-header">
      <h3 class="card-title card_title_center">Sub-Treasuries that will be delivered to the ({{$data['name']}})  <a href="{{route('admin.treasuries.add_treasuries_delivery',$data['id'])}}" class="btn btn-sm btn-warning">Add New</a>
      </h3>
    </div>
    
    <div id="ajax_response_searchdiv">

      @if (@isset($treasuries_delivery) && !@empty($treasuries_delivery) && count($treasuries_delivery))
  
      @php
          $i=1;
      @endphp

      <table id="example2" class="table table-bordered table-hover">
          <thead class="custom_thead">
            <th>#</th>
            <th>Treasury_Name</th>
            <th>AddedDate</th>
            <th>Actions</th>
            </thead> 
    
          <tbody>
            @foreach ($treasuries_delivery as $info)
            <tr>
                <td>{{$i}}</td>
                <td>{{$info->name}}</td>
                <td>
                  @php
                    $dt=new DateTime($info->created_at);
                    $date=$dt->format("Y-m-d");
                    $time=$dt->format("h:i");
                    $newDateTime=date("A",strtotime($time));
                    $newDateTimeType=(($newDateTime=='AM')? '.A.M':'.P.M');
                  @endphp
                  {{$date}}
                  {{$time}}
                  {{$newDateTimeType}}<br>
                  By
                  {{$info->added_by_admin}}

                </td>
                <td><a href="{{route('admin.treasuries.delete_treasuries_delivery',$info->id)}}" class="btn btn-danger are_you_sure">Delete</a></td>
    
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

    <!--end of treasuries_delivery-->

        @else
         <div class="alert alert-danget">Sorry ! There are no data to display .</div>   
        @endif

    </div>
</div>
    </div>
</div>

    
@endsection