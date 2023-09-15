<div class="col-md-3">
    <div class="form-group">
      <label for="">collect treasury</label>
      <select name="treasuries_id" id="treasuries_id" class="form-control">
        @if (!@empty($user_shift))
        <option selected value="{{$user_shift['treasuries_id']}}">{{$user_shift['name']}}</option>
        @else
         <option value="">You haven't treasury now</option> 
        @endif
      </select>
    </div>
  </div>

  <div class="col-md-3">
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

