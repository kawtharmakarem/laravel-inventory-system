@if (!@empty($parent_bill_data))
@if ($parent_bill_data['is_approved']==0)
@if(!@empty($item_data_details))


<div class="row">

    <div class="col-md-4">
      <div class="form-group">
        <label for="">ItemsInformations</label>
        <select id="item_code_add"  class="form-control select2" style="width: 100%">
          <option value="">select item_card</option>
          @if(@isset($item_cards) && !@empty($item_cards))
          @foreach ($item_cards as $info)
            <option  @if($item_data_details['item_code']==$info->item_code) selected="selected" @endif data-type="{{$info->item_type}}" value="{{$info->item_code}}">{{$info->name}}</option>
          @endforeach
         @endif
        </select>
        @error('item_code_add')
          <span class="text-danger">{{$message}}</span>
        @enderror
      </div>
    </div>

    
    
    <div class="col-md-4 related_to_itemCard" id="UomDivAdd">
        <div class="form-group">
            <label>Item unit information</label>
            <select id="uom_id_Add" class="form-control select2" style="width: 100%">
              <option value="">select item_unit</option>
              @if (@isset($item_card_data) && !@empty($item_card_data))
              @if($item_card_data['does_has_retailunit']==1)
              <option  @if($item_card_data['uom_id']==$item_data_details['uom_id']) selected @endif value="{{$item_card_data['uom_id']}}"  data-isparentuom="1" >{{$item_card_data['parent_uom_name']}}(BasicUnit)</option>
              <option  @if($item_card_data['retail_uom_name']==$item_data_details['uom_id']) selected @endif value="{{$item_card_data['retail_uom_name']}}" data-isparentuom="0">{{$item_card_data['retail_uom_name']}}(FragmentUnit)</option>
        
              @else
              <option selected  data-isparentuom="1" value="{{$item_card_data['uom_id']}}">{{$item_card_data['parent_uom_name']}}(BasicUnit)</option>
        
              @endif
              @endif
            </select>
           
          </div>
    </div>
  

   <div class="col-md-4 related_to_itemCard">
    <div class="form-group ">
      <label>Received quantity</label>
      <input type="text" id="quantity_add" name="quantity_add" value="{{$item_data_details['deliverd_quantity']*(1)}}" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
       </div>
   </div>

   <div class="col-md-4 related_to_itemCard">
    <div class="form-group ">
      <label>Unit price</label>
      <input type="text" id="price_add" name="price_add" value="{{$item_data_details['unit_price']*(1)}}" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
       </div>
   </div>

   <div class="col-md-4 related_to_date" @if($item_data_details['item_card_type']!=2) style="display: none" @endif>
    <div class="form-group ">
      <label>Production date</label>
      <input type="date" id="production_date" value="{{$item_data_details['production_date']}}" name="production_date" class="form-control"/>
       </div>
   </div>

   <div class="col-md-4 related_to_date" @if($item_data_details['item_card_type']!=2) style="display: none" @endif>
    <div class="form-group ">
      <label>Expire date</label>
      <input type="date" id="expire_date" value="{{$item_data_details['expire_date']}}" name="expire_date" class="form-control"/>
       </div>
   </div>

   <div class="col-md-4 related_to_itemCard">
    <div class="form-group ">
      <label>Total</label>
      <input readonly type="text" id="total_add" value="{{$item_data_details['total_price']*(1)}}" name="price_add" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
       </div>
   </div>

   <div class="col-md-12">
    <div class="form-group text-center">
        <button data-id="{{$item_data_details['id']}}" type="button" class="btn btn-sm btn-warning" id="EditDetailsItem">UpdateBill</button>
    </div>
   </div>
  

  </div>
  



@else
<div class="alert alert-danger">
    Unable to find required data
</div>
@endif



@else 
<div class="alert alert-danger">
 Sorry! An approved and archived invoice cannot be updated
</div>

@endif


@else
   <div class="alert alert-danger">
    Sorry! No data to display
    </div> 
@endif