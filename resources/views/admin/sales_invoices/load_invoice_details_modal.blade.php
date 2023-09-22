

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Invoice date</label>
                <input readonly type="date"  class="form-control"
                    value="{{ $invoice_data['invoice_date'] }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Invoice category</label>
                <select disabled  class="form-control">
                    <option value="">select invoice category</option>
                    @if (@isset($sales_material_types) && !@empty($sales_material_types))
                        @foreach ($sales_material_types as $info)
                            <option @if ($invoice_data['sales_material_type'] == $info->id) selected @endif value="{{ $info->id }}">
                                {{ $info->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Is there a customer</label>
                <select disabled  class="form-control">
                    <option @if ($invoice_data['is_has_customer'] == 1) selected @endif value="1" selected>He's a customer
                    </option>
                    <option @if ($invoice_data['is_has_customer'] == 0) selected @endif value="0">No customer</option>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Customers data
                </label>
                <select disabled  class="form-control">
                    @if (@isset($customers) && !@empty($customers))
                        @foreach ($customers as $info)
                            <option @if ($invoice_data['customer_code'] == $info->customer_code && $invoice_data['is_has_customer']==1) selected @endif
                                value="{{ $info->customer_code }}">{{ $info->name }}</option>
                        @endforeach

                    @endif
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Delegates data</label>
                <select disabled  class="form-control">
                    <option value="">select delegate</option>
                    @if (@isset($delegates) && !@empty($delegates))
                        @foreach ($delegates as $info)
                            <option @if ($invoice_data['delegate_code'] == $info->delegate_code) selected @endif
                                value="{{ $info->delegate_code }}">{{ $info->name }}</option>
                        @endforeach

                    @endif
                </select>
            </div>
        </div>
    </div>
     <div class="clearfix"></div>
    <hr style="border: 1px solid #121E36;">
   

       

        <div class="row">
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
                </thead>
                <tbody >
                    @if (!@empty($sales_invoice_details))
                     
                    @foreach ($sales_invoice_details as $info)
                    
                    <tr>
                        <td>
                            {{$info->store_name}}
                        </td>
                        <td>
                            @if($info->sales_item_type==1) PopularPrice @elseif($info->sales_item_type==2) HalfWholesalePrice  @elseif($info->sales_item_type==3) WholesalePrice @else Undefined @endif
                        </td>
                        <td>{{$info->item_name}}</td>
                        <td>{{$info->uom_name}}</td>
                        <td>{{$info->unit_price*1}}</td>
                        <td>{{$info->quantity*1}}</td>
                        <td>{{$info->total_price*1}}</td>
                        
                        
                    </tr>
                    
                    @endforeach
     
                    @endif

                </tbody>
            </table>
        </div>

        <div class="clearfix"></div>
        <hr style="border: 1px solid #121E36;">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                      <label>Total items</label>
                      <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');"  
                      class="form-control"  value="{{ $invoice_data['total_cost_items']*1 }}"  >
                 </div>
                </div>

            <div class="col-md-3">
                <div class="form-group">
                     <label>Tax percent</label>
                     <input readonly  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" 
                     class="form-control"  value="{{ $invoice_data['tax_percent']*1 }}"  >
                </div>
               </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Tax value</label>
                    <input readonly  value="{{$invoice_data['tax_value']*1}}" class="form-control">

                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Total before discount</label>
                    <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{$invoice_data['total_before_discount']*1}}" class="form-control">

                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Discount type</label>
                    <select disabled class="form-control">
                        <option value="">no discount</option>
                        <option @if($invoice_data['discount_type']==1) selected @endif value="1">Percent</option>
                        <option @if($invoice_data['discount_type']==2) selected @endif value="2">Value</option>

                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Discount percentage</label>
                    <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" value="{{$invoice_data['discount_percent']*1}}" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Discount value</label>
                    <input readonly  value="{{$invoice_data['discount_value']*1}}" class="form-control">
                </div>
            </div> 

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Final Total</label>
                    <input readonly  value="{{$invoice_data['total_cost']*1}}" class="form-control">

                </div>
            </div>
        </div>

        
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Bill type</label>
                    <select disabled  class="form-control" >
                        <option @if($invoice_data['bill_type']==1) selected  @endif value="1">Cash</option>
                        <option @if($invoice_data['bill_type']==2) selected @endif value="2">Deferred</option>
                    </select>
                </div>
            </div>
          
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">What paid</label>
                    <input readonly class="form-control"  value="{{$invoice_data['what_paid']*1}}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">What remain</label>
                    <input readonly class="form-control"  value="{{$invoice_data['what_remain']*1}}">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Notes for the invoice</label>
                    <input  readonly ="text" style="background-color: lightgoldenrodyellow" class="form-control" value="{{$invoice_data['notes']}}">
                </div>
            </div>
            <div class="col-md-12 text-centr">
                <hr>
            </div>

        </div>
        

