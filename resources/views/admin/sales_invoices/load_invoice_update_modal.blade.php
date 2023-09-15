@if ($invoice_data['is_approved'] == 0)
    <input type="hidden" name="invoiceautoserial" id="invoiceautoserial" value="{{ $invoice_data['auto_serial'] }}">
    @section('css')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endsection

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Invoice date</label>
                <input type="date" name="invoice_date" id="invoice_date" class="form-control"
                    value="{{ $invoice_data['invoice_date'] }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="">Invoice category</label>
                <select name="sales_material_type_id" id="sales_material_type_id" class="form-control">
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
                <select name="is_has_customer" id="is_has_customer" class="form-control">
                    <option @if ($invoice_data['is_has_customer'] == 1) selected @endif value="1" selected>He's a customer
                    </option>
                    <option @if ($invoice_data['is_has_customer'] == 0) selected @endif value="0">No customer</option>
                </select>
            </div>
        </div>

        <div class="col-md-4" id="customer_codeDiv">
            <div class="form-group">
                <label for="">Customers data
                    (<a title="AddNew" href="#">NewCustomer<i class="fa fa-plus-circle"></i></a>)
                </label>
                <select name="customer_code" id="customer_code" class="form-control">
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
                <select name="delegate_code" id="delegate_code" class="form-control">
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
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Branches data</label>
                <select name="store_id" id="store_id" class="form-control select2">
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
                <label for="">Items data</label>
                <select name="item_code" id="item_code" class="form-control select2">
                    <option value="">select item</option>
                    @if (@isset($item_cards) && !@empty($item_cards))
                        @foreach ($item_cards as $info)
                            <option data-item_type="{{ $info->item_type }}" value="{{ $info->item_code }}">
                                {{ $info->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <!--uoms-->
        <div class="col-md-3" style="display: none;" id="UomDiv">
        </div>
        <!--batches-->
        <div class="col-md-6" style="display: none;" id="inv_itemcard_batchesDiv">
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="">quantity</label>
                <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="item_quantity" id="item_quantity"
                    class="form-control" value="1">
            </div>
        </div>

        <div class="col-md-3">
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
                <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="item_total"
                    id="item_total" class="form-control" value="">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <button style="margin-top: 35px;" class="btn btn-sm btn-warning" id="AddItemToIvoiceDetailsActive">
                Add item activily</button>
            </div>
        </div>
    </div>

        <div class="clearfix"></div>
        <hr style="border: 1px solid #121E36;">

        <div class="row" id="activeItemisInInvoiceDiv">
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
                            <button  data-id="{{$info->id}}" class="btn btn-sm btn-danger are_you_sure remove_active_row_item">Delete</button>
                        </td>
                        
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
                      <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="total_cost_items"  id="total_cost_items" 
                      class="form-control"  value="{{ $invoice_data['total_cost_items']*1 }}"  >
                 </div>
                </div>

            <div class="col-md-3">
                <div class="form-group">
                     <label>Tax percent</label>
                     <input  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="tax_percent"  id="tax_percent" 
                     class="form-control"  value="{{ $invoice_data['tax_percent']*1 }}"  >
                </div>
               </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Tax value</label>
                    <input readonly  name="tax_value" id="tax_value" value="{{$invoice_data['tax_value']*1}}" class="form-control">

                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Total before discount</label>
                    <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="total_before_discount" id="total_before_discount" value="{{$invoice_data['total_before_discount']*1}}" class="form-control">

                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Discount type</label>
                    <select name="discount_type" id="discount_type" class="form-control">
                        <option value="">no discount</option>
                        <option @if($invoice_data['discount_type']==1) selected @endif value="1">Percent</option>
                        <option @if($invoice_data['discount_type']==2) selected @endif value="2">Value</option>

                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Discount percentage</label>
                    <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="discount_percent" id="discount_percent" value="{{$invoice_data['discount_percent']*1}}" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Discount value</label>
                    <input readonly  name="discount_value" id="discount_value" value="{{$invoice_data['discount_value']*1}}" class="form-control">
                </div>
            </div> 

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Final Total</label>
                    <input readonly  name="total_cost" id="total_cost" value="{{$invoice_data['total_cost']*1}}" class="form-control">

                </div>
            </div>
        </div>

        <div class="row" id="shiftDiv">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Collect treasury</label>
                    <select name="treasuries_id" id="treasuries_id" class="form-control">
                    @if (!@empty($user_shift))
                    <option selected value="{{$user_shift['treasuries_id']}}">{{$user_shift['name']}}</option>
                     @else
                     <option value="">Sorry ! you haven't treasury now !!</option>   
                    @endif
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Balance in treasury</label>
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
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Bill type</label>
                    <select name="bill_type" class="form-control" id="bill_type">
                        <option @if($invoice_data['bill_type']==1) selected  @endif value="1">Cash</option>
                        <option @if($invoice_data['bill_type']==2) selected @endif value="2">Deferred</option>
                    </select>
                </div>
            </div>
          
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">What paid</label>
                    <input name="what_paid" id="what_paid" class="form-control" @if($invoice_data['bill_type']==1) readonly @endif value="@if($invoice_data['bill_type']==1) {{$invoice_data['total_cost']*1}} @else 0 @endif">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">What remain</label>
                    <input name="what_remain" id="what_remain" class="form-control"  value="@if($invoice_data['bill_type']==1) 0 @else {{$invoice_data['what_remain']*1}} @endif">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Notes for the invoice</label>
                    <input type="text" style="background-color: lightgoldenrodyellow" name="notes" id="notes" class="form-control" value="{{$invoice_data['notes']}}">
                </div>
            </div>
            <div class="col-md-12 text-centr">
                <hr>
            </div>

        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-sm btn-warning" id="DoApproveInvoiceFinally" style="margin-top: 31px;">Approve&transfer Invoice</button>
        </div>

@else
<div class="alert alert-danger">
    Sorry! Approved invoice cannot be edit!!!
</div>
@endif
@section('script')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sales_invoices.js') }}"></script>

    <script>
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endsection
