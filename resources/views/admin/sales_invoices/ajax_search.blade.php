
@if (@isset($data) && !@empty($data))
        
<table id="example2" class="table table-bordered table-hover">
  <thead class="custom_thead">
    <th>Code</th>
    <th>Supplier</th>
    <th>BillDate</th>

    <th>BillType</th>
    <th>BillStatus</th>
    <th>Branch to receive bill</th>
     <th>Bill_total</th>
    <th>Actions</th>


    </thead> 

  <tbody>
    @foreach ($data as $info)
    <tr>
        <td>{{$info->auto_serial}}</td>
        <td>{{$info->supplier_name}}</td>
        <td>{{$info->order_date}}</td>

        <td>@if ($info->bill_type==1) Cash_Bill @elseif($info->bill_type==2) Deferred_Bill @else Undefined @endif</td>
        <td>@if ($info->is_approved==1) approved invoice @else open bill @endif</td>

        <td>{{$info->store_name}}</td>
      <td>{{$info->total_cost*(1)}}</td>

        <td>
          @if($info->is_approved==0)
            <a href="{{route('admin.supplierswithorders.edit',$info->id)}}" class="btn btn-sm btn-warning">Edit</a>
            <a href="{{route('admin.supplierswithorders.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
            @endif
            <a href="{{route('admin.supplierswithorders.show',$info->id)}}" class="btn btn-sm btn-secondary ">Details</a>

          </td>

    </tr>
    
    @endforeach
    </tbody>  
  
</table>
 <br>
 <div class="col-md-12" id="ajax_pagination_in_search">
    {{ $data->links() }}

 </div>
<br>

@else
<div class="alert alert-danger">Sorry ! There are no data to display .</div>   
@endif