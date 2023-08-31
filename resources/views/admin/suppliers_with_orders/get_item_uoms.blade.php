<div class="form-group">
    <label>Item unit information</label>
    <select id="uom_id_Add" class="form-control select2" style="width: 100%">
      <option value="">select received item_unit</option>
      @if (@isset($item_card_data) && !@empty($item_card_data))
      @if($item_card_data['does_has_retailunit']==1)
      <option data-isparentuom="1" value="{{$item_card_data['uom_id']}}">{{$item_card_data['parent_uom_name']}}(BasicUnit)</option>
      <option data-isparentuom="0" value="{{$item_card_data['retail_uom_id']}}">{{$item_card_data['retail_uom_name']}}(FragmentUnit)</option>

      @else
      <option data-isparentuom="1" value="{{$item_card_data['uom_id']}}">{{$item_card_data['parent_uom_name']}}(BasicUnit)</option>

      @endif
      @endif
    </select>
   
  </div>