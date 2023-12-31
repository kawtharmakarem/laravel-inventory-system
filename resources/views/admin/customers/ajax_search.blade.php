@if (@isset($data) && !@empty($data) && count($data)>0)
<table id="example2" class="table table-bordered table-hover">
  <thead class="custom_thead">
    <th> Name</th>
    <th>Customer_code</th>

    <th>Account_number</th>
    <th>Balance</th>
    <th>Address</th>
    <th>Notes</th>
    <th>ActivationCase</th>
    <th>Actions</th>
  </thead> 
  <tbody>
    @foreach ($data as $info)
    <tr>
    <td>{{$info->name}}</td>
    <td>{{$info->customer_code}}</td>

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
    <td>{{$info->notes}}</td>
    <td>@if($info->active==1) active @else inactive @endif</td>
    <td>
            <a href="{{route('admin.customers.edit',$info->id)}}" class="btn btn-sm btn-warning" style="margin-bottom: 1px">Edit</a>
     </td>

    </tr>
    
    @endforeach
    </tbody>  
  
</table>
              <div class="col-md-12" id="ajax_pagination_in_search">
                {{ $data->links() }}
            
             </div>

        @else
         <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
        @endif
            