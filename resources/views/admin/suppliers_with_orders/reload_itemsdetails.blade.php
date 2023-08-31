@if (@isset($details) && !@empty($details) && count($details)>0)
  
      @php
          $i=1;
      @endphp

      <table id="example2" class="table table-bordered table-hover">
          <thead class="custom_thead">
            <th>#</th>
            <th>Item</th>
            <th>Uom</th>
            <th>Ammount</th>
            <th>Price</th>
            <th>Total</th>

            <th>Actions</th>
            </thead> 
    
          <tbody>
            @foreach ($details as $info)
            <tr>
                <td>{{$i}}</td>
                <td>{{$info->item_card_name}}
                  @if ($info->item_card_type==2)
                  <br>
                  production_date {{$info->production_date}}
                  <br>
                  Expire_date {{$info->expire_date}}
                    
                  @endif
                </td>
                <td>{{$info->uom_name}}</td>
                <td>{{$info->deliverd_quantity*(1)}}</td>
                <td>{{$info->unit_price*(1)}}</td>
                <td>{{$info->total_price*(1)}}</td>
                <td>
                  @if ($data['is_approved']==0)
                  <button  data-id="{{$info->id}}" class="btn btn-sm btn-warning load_edit_item_details">Edit</button>
                  <a href="" class="btn btn-sm btn-danger are_you_sure">Delete</a>
                    
                  @endif
                </td>
                

                
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
      