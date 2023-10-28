@extends('layouts.admin')
@section('title')
Collection Screen
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endsection
@section('contentheader')
Accounts
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.collect_transaction.index')}}">CollectionScreen</a>
@endsection
@section('contentheaderactive')
   View 
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Collect Transactions Informations</h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_url_get_account_balance" id="ajax_url_get_account_balance" value="{{route('admin.collect_transaction.get_account_balance')}}">
{{-- for search/end --}}

    </div>

    <!-- /.card-header -->
    <div class="card-body">
    @if(!@empty($checkExistsOpenShift))
        <form action="{{route('admin.collect_transaction.store')}}" method="POST" class="custom_form">
            <div class="row">
                @csrf

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">TransactionDate</label>
                        <input type="date" name="move_date" id="move_date" class="form-control" value="{{old('move_date',date('Y-m-d'))}}">
                   @error('move_date')
                     <span class="text-danger">{{$messages}}</span>  
                   @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Accounts</label>
                        <select name="account_number" id="account_number" class="form-control select2" >
                            <option value="">select account collected from it</option>
                            @if (@isset($accounts) && !@empty($accounts))
                              @foreach ($accounts as $info)
                              <option data-type="{{$info->account_type}}" @if(old('account_number')==$info->account_number) selected='selected' @endif  value="{{$info->account_number}}">{{$info->name}} ({{$info->account_type_name}})</option>
                                 
                              @endforeach  
                            @endif
                        </select>
                        @error('account_number')
                        <span class="text-danger">{{$message}}</span>
                            
                        @enderror
                    </div>
                </div>

                  
                <div class="col-md-4" id="account_numberStatusDiv" style="display: none">

                </div>

                <div class="col-md-4" id="get_account_balanceDiv" style="display: none"></div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Transaction Type</label>
                        <select name="mov_type" id="mov_type" class="form-control" >
                            <option value="">select transaction type</option>
                            @if (@isset($mov_type) && !@empty($mov_type))
                              @foreach ($mov_type as $info)
                              <option @if(old('mov_type')==$info->id) selected='selected' @endif value="{{$info->id}}">{{$info->name}}</option>
                                 
                              @endforeach  
                            @endif
                        </select>
                        @error('mov_type')
                        <span class="text-danger">{{$message}}</span>
                            
                        @enderror
                    </div>
                </div>


            
                <div class="col-md-4" id="AccountStatusDiv" style="display: none">


                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">TreasuriesInformation</label>
                        <select name="treasuries_id" id="treasuries_id" class="form-control">
                       <option value="{{$checkExistsOpenShift['treasuries_id']}}">{{$checkExistsOpenShift['treasuries_name']}}</option>
                        </select>
                        @error('treasuries_id')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">The balance available in the treasury</label>
                        <input readonly name="treasuries_balance" id="treasuries_balance" class="form-control" value="{{$checkExistsOpenShift['treasuries_balance_now']}}">
                        @error('treasuries_balance')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Value of collected amount</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'')" name="money" id="money" class="form-control" value="{{old('money')}}">
                        @error('money')
                         <span class="text-danger">{{$message}}</span>   
                        @enderror
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Description</label>
                     <textarea name="byan" id="byan" class="form-control" cols="10" rows="4">{{old('byan','collecting')}}</textarea>
                    </div>
                </div>

             <div class="col-md-12">
                <div class="form-group text-center">
                    <button type="submit" id="do_collect_now_btn" class="btn btn-warning btn-sm">Collect Now</button>
                </div>
             </div>

            </div>

        </form>
        @else
        <div class="alert alert-secondary">
            Warning ! There is no open shift for you to be able to collect
        </div>
        @endif
       
        <div id="ajax_response_searchdiv">

            @if (@isset($data) && !@empty($data) && count($data)>0)
        
            
  
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>auto_serial</th>
                  <th>Cheque number</th>
                  <th>Treasury</th>
                  <th>amount</th>

                  <th>Transaction</th>
                  <th>AccountName</th>
                  <th>Description</th>
                  <th>User</th>
                  <th>Actions</th>

                  </thead> 
          
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                      <td>{{$info->auto_serial}}</td>
                      <td>{{$info->isal_number}}</td>
                      <td>{{$info->treasury_name}}</td>
                      <td>{{$info->money*(1)}}</td>
                      <td>{{$info->mov_type_name}}</td>
                      <td>{{$info->account_name}}<br>
                       ({{$info->account_type_name}}) 
                    </td>

                      <td>{{$info->byan}}</td>
                      <td>
                        @php
                            $dt=new DateTime($info->created_at);
                            $date=$dt->format('Y-m-d');
                            $time=$dt->format("h:i");
                            $newDateTime=date("A",strtotime($time));
                            $newDateTimeType=(($newDateTime=='AM')? 'A.M.':'P.M.');
                        @endphp
                        {{$date}}<br>
                        {{$time}}
                        {{$newDateTimeType}}<br>
                        By
                        {{$info->added_by_admin}}
                      </td>
                      <td>
                          <a href="{{route('admin.treasuries.edit',$info->id)}}" class="btn btn-sm btn-warning">Print</a>
                          <a href="{{route('admin.treasuries.details',$info->id)}}" class="btn btn-sm btn-secondary">More</a>
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

<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/admin/js/collect_transaction.js')}}"></script>
<script>

 //Initialize Select2 Elements
 $('.select2').select2({
   theme: 'bootstrap4'
 });


</script>
@endsection
