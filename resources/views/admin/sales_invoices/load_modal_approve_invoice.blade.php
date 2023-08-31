@if(@isset($data) && !@empty($data))
@if($data['is_approved']==0)

<form action="{{route('admin.supplierswithorders.do_approve',$data['auto_serial'])}}" method="post">
  @csrf
  <div class="row">

    <div class="col-md-6">
      <div class="form-group">
        <label for="">All items in the bill</label>
       <input readonly  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" id="total_cost_items" name="total_cost_items" class="form-control" value="{{$data['total_cost_items']*1}}">
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="">Tax percent for added value</label>
       <input  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" id="tax_percent" name="tax_percent" class="form-control" value="{{$data['tax_percent']*1}}">
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="">Added tax value</label>
       <input  readonly  id="tax_value" name="tax_value" class="form-control" value="{{$data['tax_value']*1}}">
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="">total before discount</label>
       <input  readonly  id="total_before_discount" name="total_before_discount" class="form-control" value="{{$data['total_before_discount']*1}}">
      </div>
    </div>
 
    <div class="col-md-6">
      <div class="form-group">
        <label for="">Discount type</label>
        <select name="discount_type" id="discount_type" class="form-control">
          <option value="">no discount</option>
          <option value="1" @if($data['discount_type']==1) selected @endif>percentage</option>
          <option value="2" @if($data['discount_type']==2) selected @endif>manual</option>

        </select>
      </div>
    </div>


    <div class="col-md-6">
      <div class="form-group">
        <label for="">Discount percent</label>
        <input  value="{{ $data['discount_percent']*1}}" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="discount_percent"  id="discount_percent" class="form-control"
        @if($data['discount_type']=="" || $data['discount_type']==null) readonly @endif >
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="">Discount value</label>
       <input readonly id="discount_value" name="discount_value" class="form-control" value="{{$data['discount_value']*1}}">
      </div>
    </div>
    

    <div class="col-md-6">
      <div class="form-group">
        <label for="">Final total after discount</label>
       <input readonly id="total_cost" name="total_cost" class="form-control" value="{{$data['total_cost']*1}}">
      </div>
    </div>
</div> 


<div class="row" id="shiftDiv">

  <div class="col-md-6">
    <div class="form-group">
      <label for="">Exchange treasury</label>
      <select name="treasuries_id" id="treasuries_id" class="form-control">
        @if (!@empty($user_shift))
        <option selected value="{{$user_shift['treasuries_id']}}">{{$user_shift['name']}}</option>
        @else
         <option value="">You haven't treasury now</option> 
        @endif
      </select>
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group">
      <label for="">Avaliable balance in the treasury</label>
      <input readonly name="treasuries_balance" id="treasuries_balance" class="form-control"
      @if (!@empty($user_shift))
      value="{{$user_shift['balance']*1}}"
      @else
      value="0"
      @endif
      >
    </div>
  </div>

</div>

  <div class="row">

    <div class="col-md-6">
      <div class="form-group">
        <label for="">Bill type</label>
       <select name="bill_type" id="bill_type" class="form-control">
         <option value="1" @if($data['bill_type']==1) selected @endif>Cash bill</option>
         <option value="2" @if($data['bill_type']==2) selected @endif>Deferred bill</option>
        </select>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for=""> What Paid to supplier now</label>
        <input name="what_paid" id="what_paid" class="form-control" @if($data['bill_type']==2) value="0" @else readonly value="{{$data['total_cost']*1}}" @endif>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="">what remain to supplier</label>
        <input readonly name="what_remain" id="what_remain" class="form-control" @if($data['bill_type']==2) value="{{$data['total_cost']*1}}" @else value="0" @endif>
      </div>
    </div>

    <div class="col-md-12 text-center">
      <hr>
      <button type="submit" id="do_close_invoice_now" class="btn btn-warning">Approve&Transfer Now</button>
    </div>


</div>


</form>




@else
<div class="alert alert-danger">
  Sorry! this invoice has been approved 
  </div>

@endif


  @else
   <div class="alert alert-danger">
    Sorry! No data to display
    </div> 
@endif