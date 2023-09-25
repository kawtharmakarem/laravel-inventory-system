@if (@isset($data) && !@empty($data) && count($data)>0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th> Name</th>
                  <th>Account_number</th>
                  <th>Account_Type</th>
                  <th>Is_Parent</th>
                  <th>Parent_Account</th>
                  <th>Balance</th>
                  <th>ActivationCase</th>
                  <th>Actions</th>
                </thead> 
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                  <td>{{$info->name}}</td>
                  <td>{{$info->account_number}}</td>
                  <td>{{$info->account_types_name}}</td>
                  <td>@if($info->is_parent==1) Yes  @else No @endif</td>
                  <td>{{$info->parent_account_name}}</td>
                  <td>
                    @if($info->is_parent==0)
                    @if ($info->current_balance>0)
                    Debit ({{$info->current_balance*1}}) S.p
                    @elseif ($info->current_balance<0)
                    Credit ({{$info->current_balance*(-1)}}) S.p
                    @else
                    Balanced (0) S.P
                    @endif
                    @else
                    من ميزان المراجعة
                    @endif
                  </td>
                  <td>@if($info->active==1) active @else inactive @endif</td>
                  <td>
                          <a href="{{route('admin.accounts.edit',$info->id)}}" class="btn btn-sm btn-warning" style="margin-bottom: 1px">Edit</a>
                          <a href="{{route('admin.accounts.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
                          <a href="{{route('admin.accounts.show',$info->id)}}" class="btn btn-sm btn-info">show</a>
                   </td>
          
                  </tr>
                  
                  @endforeach
                  </tbody>  
                
              </table>

             <br>
  <div class="col-md-12" id="ajax_pagination_in_search">
    {{ $data->links() }}

 </div>

        @else
         <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
        @endif
            