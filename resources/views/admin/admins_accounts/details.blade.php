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
   View Special Permissions
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">

    <div class="card-header">
      <h3 class="card-title card_title_center">User Details</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
        
      <table id="example2" class="table table-bordered table-hover">
        
        <tr>
            <td class="width30">UserName</td>
            <td>{{$data['name']}}</td>
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
                <a href="{{route('admin.admins_accounts.edit',$data['id'])}}" class="btn btn-sm btn-warning">Edit</a>

            </td>
        </tr>
        
      </table>

    <!--treasuries_delivery-->
    <div class="card-header">
      <h3 class="card-title card_title_center">Treasuries added to the users's permissions 
        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target='#Add_treasuries_modal'>Add New</button>
      </h3>

    </div>
    
    <div id="ajax_response_searchdiv">

      @if (@isset($admins_treasuries) && !@empty($admins_treasuries))
  
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
            @foreach ($admins_treasuries as $info)
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
                <td><a href="#" class="btn btn-danger are_you_sure">Delete</a></td>
    
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
<!--modal-->

<div class="modal fade modal-x1" id="Add_treasuries_modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-warning">
      <div class="modal-header">
        <h4 class="modal-title text-center">Add treasuries to the user</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="Add_treasuries_modal_body" style="background-color: white !important;color: black">   


       <form action="{{route('admin.admins_accounts.Add_treasuries_To_Admin',$data['id'])}}" method="post">
      @csrf
      <div class="form-group">
        <label for="">Treasuries Information</label>
        <select name="treasuries_id" id="treasuries_id" class="form-control">
          <option value="">Select treasury</option>
          @if(@isset($treasuries) && !@empty($treasuries))
          @foreach ($treasuries as $info)
          <option value="{{$info->id}}">{{$info->name}}</option>
          @endforeach
          @endif
        </select>
       <div class="form-group text-center"><br>
        <button class="btn btn-warning btn-sm">Add treasury to user</button>
       </div>
      </div>


      </form> 
       
       

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--/modal-->


    
@endsection