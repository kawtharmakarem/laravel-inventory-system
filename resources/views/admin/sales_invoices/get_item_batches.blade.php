@if (@isset($item_card_Data) && !@empty($item_card_Data))
<div class="form-group"> 
    <label>      
        Quantities in selected branch
    </label>
    <select  id="inv_itemcard_batches_autoserial" name="inv_itemcard_batches_autoserial" class="form-control " style="width: 100%;">
      @if (@isset($inv_itemcard_batches) && !@empty($inv_itemcard_batches) && count($inv_itemcard_batches)>0 )
      @if($uom_Data['is_master']==1)
      @foreach ( $inv_itemcard_batches as $info )
      @if($item_card_Data['item_type']==2)
<option data-quantity="{{$info->quantity}}" value="{{ $info->auto_serial }}"> number : {{ $info->quantity*(1) }} {{ $uom_Data['name'] }} ,Production_date {{ $info->production_date }} ,with cost of: {{ $info->unit_cost_price*1 }}  for unit   </option>

      @else
      <option data-quantity="{{$info->quantity}}" value="{{ $info->auto_serial }}"> number : {{ $info->quantity*(1) }} {{ $uom_Data['name'] }} ,with cost of: {{ $info->unit_cost_price*1 }}  for unit  </option>

      @endif
      
       @endforeach

      @else

  @foreach ( $inv_itemcard_batches as $info )
  @php
  $quantity=$info->quantity*$item_card_Data['retail_uom_quntToParent'];
  $unit_cost_price=$info->unit_cost_price/$item_card_Data['retail_uom_quntToParent'];

  @endphp

  @if($item_card_Data['item_type']==2)
<option value="{{ $info->auto_serial }}"> number : {{ $quantity*(1) }} {{ $uom_Data['name'] }} ,Production_date: {{ $info->production_date }} with cost of: {{ $unit_cost_price*1 }}  for one unit   </option>

  @else
  <option value="{{ $info->auto_serial }}"> number : {{ $quantity*(1) }} {{ $uom_Data['name'] }}  with cost of: {{ $unit_cost_price*1 }}  for one unit   </option>

  @endif
  
   @endforeach



      @endif
    



    
    @endif
    </select>
   
    </div>

    @endif