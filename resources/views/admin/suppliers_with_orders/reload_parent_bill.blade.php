@if (@isset($data) && !@empty($data))
        
<table id="example2" class="table table-bordered table-hover">
        
  <tr>
      <td class="width30">code</td>
      <td>{{$data['auto_serial']}}</td>
  </tr>

  <tr>
    <td class="width30">The invoice number registered in the purchase invoice</td>
    <td>{{$data['doc_num']}}</td>
</tr>

<tr>
  <td class="width30">Bill date</td>
  <td>{{$data['order_date']}}</td>
</tr>

<tr>
  <td class="width30">supplier name</td>
  <td>{{$data['supplier_name']}}</td>
</tr>



  <tr>
      <td class="width30">Bill type</td>
      <td>@if ($data['bill_type']==1)
        Cash bill
      @else
      Deferred bill
      @endif</td>
  </tr>

  <tr>
    <td class="width30">Branch for received bill</td>
    <td class="width30">{{$data['store_name']}}</td>
  </tr>

  <tr>
    <td class="width30">Total invoice</td>
    <td>{{$data['total_before_discount']*(1)}}</td>
</tr>

  @if ($data['discount_type']!=null)

  <tr>
    <td class="width30">Discount on the invoice</td>
    <td>
    @if ($data['discount_type']==1)
     Percent_discount ({{$data['discount_percent']*(1)}}) EqualTo ({{$data['discount_value']*1}})
    @else
    Manual_discount EqualTo ({{$data['discount_value']}})

    @endif
    </td>

</tr>
 @else

  <tr>
    <td class="width30">Discount on the invoice</td>
    <td>There is no discount</td>
</tr>
    
  @endif
  
  <tr>
      <td class="width30">Added value percentage</td>
      <td>
        @if ($data['tax_percent']>0)
        No added value
        @else
         Percentage({{$data['tax_percent']*(1)}}) % and EqualTo ({{$data['tax_value']*(1)}}) 
        @endif
      </td> 
  </tr>

  <tr>
    <td class="width30">Invoice status</td>
    <td>@if($data['is_approved']==1) close and archived @else open @endif</td>
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
          @if($data['is_approved']==0)
          <a href="{{route('admin.supplierswithorders.edit',$data['id'])}}" class="btn btn-sm btn-warning">Edit</a>
          <a href="{{route('admin.supplierswithorders.delete',$data['id'])}}" class="btn btn-sm are_you_sure btn-danger">Delete</a>
          <button  type="button" class="btn btn-sm btn-secondary" id="load_close_approve_invoice">Load&transfer</button>
          
          @endif
      </td>
  </tr>
  
</table>
@else
<div class="alert alert-danger">
    Sorry ! no data to display !!
</div>

@endif