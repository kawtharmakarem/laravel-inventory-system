@extends('layouts.admin')
@section('title')
    GeneralSettings
@endsection
@section('contentheader')
    Settings
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.adminpanelsetting.index')}}">Settings</a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">System Informations</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
        
      <table id="example2" class="table table-bordered table-hover">
        
        <tr>
            <td class="width30">CompanyName</td>
            <td>{{$data['system_name']}}</td>
        </tr>
        <tr>
            <td class="width30">CompanyCode</td>
            <td>{{$data['com_code']}}</td>
        </tr>
        <tr>
            <td class="width30">SystemStatus</td>
            <td>@if ($data['active']==1)
              Active  
            @else
              InActive  
            @endif</td>
        </tr>


        <tr>
            <td class="width30">CompanyAddress</td>
            <td>{{$data['address']}}</td>
        </tr>

        <tr>
            <td class="width30">Parent account name</td>
            <td>{{$data['customer_parent_account_name']}}/ AccountNumber : ({{$data['customer_parent_account_number']}})</td>
        </tr>

        <tr>
            <td class="width30">Parent account name</td>
            <td>{{$data['supplier_parent_account_name']}}/ AccountNumber : ({{$data['supplier_parent_account_number']}})</td>
        </tr>


        <tr>
            <td class="width30">Phone</td>
            <td>{{$data['phone']}}</td>
        </tr>

        <tr>
            <td class="width30">AlarmMessage</td>
            <td>{{$data['general_alert']}}</td>
        </tr>
        <tr>
            <td class="width30">Logo</td>
            <td>
                <div class="image">
                    <img src="{{asset('assets/admin/uploads').'/'.$data['photo']}}" alt="Logo" class="custom_img">
                </div>
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
                <a href="{{route('admin.adminpanelsetting.edit')}}" class="btn btn-sm btn-warning">Edit</a>

            </td>
        </tr>
        
      </table>

        @else
         <div class="alert alert-danget">Sorry ! There are no data to display .</div>   
        @endif

    </div>
</div>
    </div>
</div>

    
@endsection