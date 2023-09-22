  @if (@isset($data) && !@empty($data) && count($data)>0)
   @php
     $i=1;
   @endphp
   
  <table id="example2" class="table table-bordered table-hover">
      <thead class="custom_thead">
        <th>Code</th>
        <th>BillDate</th>

        <th>Customer</th>
        <th>SalesMaterialType</th>

        <th>BillType</th>
        <th>Bill_total</th>

        <th>BillStatus</th>
        <th>Actions</th>


        </thead> 

      <tbody>
        @foreach ($data as $info)
        <tr>
            <td>{{$info->auto_serial}}</td>
            <td>{{$info->invoice_date}}</td>
            <td>{{$info->customer_name}}</td>
            <td>{{$info->sales_material_types_name}}</td>

            <td>@if ($info->bill_type==1) Cash_Bill @elseif($info->bill_type==2) Deferred_Bill @else Undefined @endif</td>
            <td>{{$info->total_cost*(1)}}</td>

            <td>@if ($info->is_approved==1) approved invoice @else open bill @endif</td>


            <td>
              @if($info->is_approved==0)
                <button data-autoserial="{{$info->auto_serial}}" class="btn btn-sm btn-warning load_invoice_update_modal">Edit</button>
                <a href="{{route('admin.sales_invoices.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
                @endif
               <button data-autoserial="{{$info->auto_serial}}" class="btn btn-sm load_invoice_details_modal btn-info">View</button>
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
