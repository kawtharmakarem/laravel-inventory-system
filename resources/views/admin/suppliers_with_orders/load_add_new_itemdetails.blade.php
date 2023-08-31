
<div class="row">

    <div class="col-md-4">
      <div class="form-group">
        <label for="">ItemsInformations</label>
        <select id="item_code_add"  class="form-control select2" style="width: 100%">
          <option value="">select item_card</option>
          @if(@isset($item_cards) && !@empty($item_cards))
          @foreach ($item_cards as $info)
            <option data-type="{{$info->item_type}}" value="{{$info->item_code}}">{{$info->name}}</option>
          @endforeach
         @endif
        </select>
        @error('')
          
        @enderror
      </div>
    </div>

    
    
    <div class="col-md-4 related_to_itemCard" style="display: none" id="UomDivAdd">
      
    </div>
  
   <div class="col-md-4 related_to_itemCard" style="display: none">
    <div class="form-group ">
      <label>Received quantity</label>
      <input type="text" id="quantity_add" name="quantity_add" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
       </div>
   </div>

   <div class="col-md-4 related_to_itemCard" style="display: none">
    <div class="form-group ">
      <label>Unit price</label>
      <input type="text" id="price_add" name="price_add" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
       </div>
   </div>

   <div class="col-md-4 related_to_date" style="display: none">
    <div class="form-group ">
      <label>Production date</label>
      <input type="date" id="production_date" name="production_date" class="form-control"/>
       </div>
   </div>

   <div class="col-md-4 related_to_date" style="display: none">
    <div class="form-group ">
      <label>Expire date</label>
      <input type="date" id="expire_date" name="expire_date" class="form-control"/>
       </div>
   </div>

   <div class="col-md-4 related_to_itemCard" style="display: none">
    <div class="form-group ">
      <label>Total</label>
      <input readonly type="text" id="total_add" name="price_add" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'')"/>
       </div>
   </div>
  
   <div class="col-md-12">
    <div class="form-group text-center">
     <button type="button" class="btn btn-warning" id="add_to_bill">Add to bill</button>
    </div>
  </div>

  </div>