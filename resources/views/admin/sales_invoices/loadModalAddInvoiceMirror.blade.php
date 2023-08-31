<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Invoice date</label>
            <input type="date" name="invoice_date" value="@php echo date('Y-m-d'); @endphp" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Is there a customer</label>
            <select name="is_has_customer" id="is_has_customer" class="form-control">
                <option value="1" selected>He's is a customer</option>
                <option value="0" selected>Not a customer</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Customer data (<a title="Add new customer" href="#">New<i
                        class="fa fa-plus-circle"></i></a>)</label>
            <select name="customer_code" id="customer_code" class="form-control select2">
                <option value="">no customer</option>
                @if (@isset($customers) && !@empty($customers))
                    @foreach ($customers as $info)
                        <option value="{{ $info->customer_code }}">{{ $info->name }}</option>
                    @endforeach

                @endif
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Delegate data (<a title="Add new customer" href="#">New<i
                        class="fa fa-plus-circle"></i></a>)</label>
            <select name="customer_code" id="customer_code" class="form-control select2">
                <option value="">select delegate</option>
                @if (@isset($customers) && !@empty($customers))
                    @foreach ($customers as $info)
                        <option value="{{ $info->customer_code }}">{{ $info->customer_name }}</option>
                    @endforeach

                @endif
            </select>
        </div>
    </div>

</div>

<div class="clearfix"></div>
<hr style="border: 1px solid #121E36">


<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="">Branch date</label>
            <select name="store_id" id="store_id" class="form-control">
                <option value="">select branch</option>
                @if (@isset($stores) && !@empty($stores))
                    @foreach ($stores as $info)
                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Sale type</label>
            <select name="sales_item_type" id="sales_item_type" class="form-control">
                <option value="1">Popular price</option>
                <option value="2">Half wholesale price</option>
                <option value="3">Wholesale price</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Items</label>
            <select name="item_code" id="item_code" class="form-control select2" style="width: 100%">
                <option value="">select item</option>
                @if (@isset($item_cards) && !@empty($item_cards))
                    @foreach ($item_cards as $info)
                        <option data-item_type="{{ $info->item_type }}" value="{{ $info->item_code }}">
                            {{ $info->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>



    <div class="col-md-3" style="display: none;" id="UomDiv">

    </div>


    <div class="col-md-6" style="display: none;" id="inv_itemcard_batchesDiv">
        hi
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="">Quantity</label>
            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="item_quantity" id="item_quantity"
                class="form-control" value="1">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="">Price</label>
            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="item_price" id="item_price"
                class="form-control" value="">

        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Is normal sale?</label>
            <select name="is_normal_orOther" id="is_normal_orOther" class="form-control">
                <option value="1">normal</option>
                <option value="2">gift</option>
                <option value="3">Advertising</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Total</label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="item_total" id="item_total"
                class="form-control" value="">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <button style="margin-top: 35px;" class="btn btn-warning btn-sm" id="AddItemToIvoiceDetailsRow">Add to
                the invoice</button>
        </div>
    </div>



</div>
<div class="clearfix"></div>
<hr style="border: 1px solid #121E36">
<div class="row">
    <h3 class="card-title card_title_center">Items add to the invoice</h3>
    <table id="example2" class="table table-bordered table-hover">
        <thead class="custom_thead">
            <th>Branch</th>
            <th>Sales type</th>
            <th>Item</th>

            <th>Sales unit</th>
            <th>Unit price</th>
            <th>Qauntity</th>
            <th>Total</th>
            <th>Actions</th>

        </thead>

        <tbody id="itemsrowtableContainerbody">


        </tbody>

    </table>

</div>
<div class="clearfix"></div>
<hr style="border: 1px solid #121E36">

<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Total items</label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" id="total_cost_items"
                name="total_cost_items" class="form-control" value="">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Tax percent</label>
            <input type="" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" id="tax_percent"
                name="tax_percent" class="form-control" value="0">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Tax value</label>
            <input readonly id="tax_value" name="tax_value" class="form-control" value="0">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Total before discount</label>
            <input readonly id="total_before_discount" name="total_before_discount" class="form-control"
                value="0">
        </div>
    </div>

   

    <div class="col-md-3">
      <div class="form-group">
        <label for="">Discount type</label>
        <select name="discount_type" id="discount_type" class="form-control">
          <option value="">No discount</option>
          <option value="1">percentage</option>
          <option value="2">manual</option>
        </select>
      </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Discount percent</label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" id="discount_percent"
                name="discount_percent" class="form-control" value="0">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Discount value</label>
            <input readonly id="discount_value" name="discount_value" class="form-control" value="0">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="">Final total</label>
            <input readonly id="total_cost" name="total_cost" class="form-control" value="0">
        </div>
    </div>

</div>

<div class="row" id="shiftDiv">

  <div class="col-md-3">
    <div class="form-group">
      <label for="">Treasury name</label>
      <select name="treasuries_id" id="treasuries_id" class="form-control"  value="0">
        @if (!@empty($user_shift))
          <option selected value="{{$user_shift['treasuries_id']}}">{{$user_shift['name']}}</option>
       @else
       <option value="">Sorry ,you haven't treasury now </option>
      @endif
      </select>
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label for="">Current balance in treasury</label>
      <input readonly id="treasuries_balance" name="treasuries_balance" class="form-control" 
      @if (!@empty($user_shift))
      value="{{$user_shift['balance']*1}}"
       @else
       value="0" 
      @endif>
     
    </div>
  </div>

</div>

<div class="row">

  <div class="col-md-3">
    <div class="form-group">
      <label for="">Bill type</label>
      <select name="bill_type" id="bill_type" class="form-control">
        <option value="1">Cash bill</option>
        <option value="2">Deferred bill</option>
      </select>
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label for="">What paid</label>
      <input name="what_paid" id="what_paid" class="form-control" value="0">
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label for="">what remain</label>
      <input readonly name="what_remain" id="what_remain" class="form-control" value="0">
    </div>
  </div>

  <div class="col-md-12">
    <div class="form-group">
      <label for="">Notes</label>
      <input style="background-color: lightgoldenrodyellow" class="form-control" name="notes" id="notes" value="">
    </div>
  </div>

  <div class="col-md-12 text-center">
    <hr>
    <button type="submit" id='Do_Add_invoice' class="btn btn-sm btn-warning">Print Invoice price display mirror</button>
  </div>
  
</div>
