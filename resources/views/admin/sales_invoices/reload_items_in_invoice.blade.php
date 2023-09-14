<h3 class="card-title card_title_center">
    Items Added To The Invoice
</h3>
<table id="example2" class="table table-bordered table-hover">
    <thead class="custom_thead">
        <th>Branch</th>
        <th>Sales type</th>
        <th>Category</th>
        <th>Unit</th>
        <th>Unit price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Actions</th>
    </thead>
    <tbody id="itemsrowtableContainerBody">
        @if (!@empty($sales_invoice_details))
         
        @foreach ($sales_invoice_details as $info)
        
        <tr>
            <td>
                {{$info->store_name}}
                <input type="hidden" name="item_total_array[]" class="item_total_array" value="{{$info->total_price}}">
            </td>
            <td>
                @if($info->sales_item_type==1) PopularPrice @elseif($info->sales_item_type==2) HalfWholesalePrice  @elseif($info->sales_item_type==3) WholesalePrice @else Undefined @endif
            </td>
            <td>{{$info->item_name}}</td>
            <td>{{$info->uom_name}}</td>
            <td>{{$info->unit_price*1}}</td>
            <td>{{$info->quantity*1}}</td>
            <td>{{$info->total_price*1}}</td>
            <td>
                <button data-id="{{$info->id}}" class="btn btn-sm btn-danger are_you_sure remove_active_row_item">Delete</button>
            </td>
            
        </tr>
        
        @endforeach

        @endif

    </tbody>
</table>