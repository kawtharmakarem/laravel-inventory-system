@extends('layouts.admin')
@section('title')
Sales
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
Sales Invoices
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.sales_invoices.index')}}">
      Sales
    </a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Sales invoices for customers
      </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.sales_invoices.ajax_search')}}">
      <input type="hidden" name="ajax_get_item_uoms" id="ajax_get_item_uoms" value="{{route('admin.sales_invoices.get_item_uoms')}}">
      <input type="hidden" name="ajax_load_modal_add_mirror" id="ajax_load_modal_add_mirror" value="{{route('admin.sales_invoices.load_modal_add_mirror')}}">
      <input type="hidden" name="ajax_load_modal_add_active" id="ajax_load_modal_add_active" value="{{route('admin.sales_invoices.load_modal_add_active')}}">

      <input type="hidden" name="ajax_get_item_batches" id="ajax_get_item_batches" value="{{route('admin.sales_invoices.get_item_batches')}}">
      <input type="hidden" name="ajax_get_item_unit_price" id="ajax_get_item_unit_price" value="{{route('admin.sales_invoices.get_item_unit_price')}}">
      <input type="hidden" name="ajax_get_Add_new_item_row" id="ajax_get_Add_new_item_row" value="{{route('admin.sales_invoices.get_Add_new_item_row')}}">
      <input type="hidden" name="ajax_get_store" id="ajax_get_store" value="{{route('admin.sales_invoices.store')}}">
      <input type="hidden" name="ajax_get_load_invoice_update_modal" id="ajax_get_load_invoice_update_modal" value="{{route('admin.sales_invoices.load_invoice_update_modal')}}">
      <input type="hidden" name="ajax_get_Add_item_to_invoice" id="ajax_get_Add_item_to_invoice" value="{{route('admin.sales_invoices.Add_item_to_invoice')}}">
      <input type="hidden" name="ajax_get_reload_items_in_invoice" id="ajax_get_reload_items_in_invoice" value="{{route('admin.sales_invoices.reload_items_in_invoice')}}">
      <input type="hidden" name="ajax_get_recalclate_parent_invoice" id="ajax_get_recalclate_parent_invoice" value="{{route('admin.sales_invoices.recalclate_parent_invoice')}}">
      <input type="hidden" name="ajax_get_remove_active_row_item" id="ajax_get_remove_active_row_item" value="{{route('admin.sales_invoices.remove_active_row_item')}}">
      <input type="hidden" name="ajax_DoApproveInvoiceFinally" id="ajax_DoApproveInvoiceFinally" value="{{route('admin.sales_invoices.DoApproveInvoiceFinally')}}">
      <input type="hidden" name="ajax_load_usershiftDiv" id="ajax_load_usershiftDiv" value="{{route('admin.sales_invoices.load_usershiftDiv')}}">
      <input type="hidden" name="ajax_load_invoice_details_modal" id="ajax_load_invoice_details_modal" value="{{route('admin.sales_invoices.load_invoice_details_modal')}}">
      <input type="hidden" name="ajax_ajax_search" id="ajax_ajax_search" value="{{route('admin.sales_invoices.ajax_search')}}">

      
{{-- for search/end --}}

      <button class="btn btn-secondary" id="LoadModalAddBtnMirror" data-target="#AddNewInvoiceModalMirror" data-toggle="modal">Invoice price display mirror</button>
      <button class="btn btn-warning" id="LoadModalAddBtnActive" data-target="#AddNewInvoiceModalActive" data-toggle="modal">AddActiveInvoice</button>
 
    </div>

    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">

       
        <div class="col-md-4">
          <input checked type="radio" name="searchbyradio" id="searchbyradio"  value="auto_serial"><label>ByCode</label>
          <input type="radio" name="searchbyradio" id="searchbyradio" value="customer_code"><label>ByCustomer</label>
          <input type="radio" name="searchbyradio" id="searchbyradio" value="account_number"><label>AccountNumber</label>
          <input type="text" id="search_by_text" name="search_by_text" class="form-control"><br>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">SearchByCustomers</label>
            <select name="customer_code_search" id="customer_code_search" class="form-control">
              <option value="all">ByAll</option>
              <option value="without">NoCustomer</option>
              @if (@isset($customers) && !@empty($customers))
              @foreach ($customers as $info)
                <option value="{{$info->customer_code}}">{{$info->name}}</option>
              @endforeach
                
              @endif
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="">SearchByDelegates</label>
            <select name="delegates_code_search" id="delegates_code_search" class="form-control">
              <option value="all">SearchByall</option>
              @if (@isset($delegates) && !@empty($delegates))
               @foreach ($delegates as $info)
                 <option value="{{$info->delegate_code}}">{{$info->name}}</option>
               @endforeach 
              @endif
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="">SearchBySalesMaterialTypes</label>
            <select name="sales_material_types_search" id="sales_material_types_search" class="form-control">
              <option value="all">all</option>
              @if (@isset($sales_material_types) && !@empty($sales_material_types))
              @foreach ($sales_material_types as $info)
              <option value="{{$info->id}}">{{$info->name}}</option>
                
              @endforeach
                
              @endif
            </select>

          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="">SearchByBillType</label>
            <select name="bill_type_search" id="bill_type_search" class="form-control">
              <option value="all">all</option>
              <option value="1">Cash</option>
              <option value="2">Deferred</option>
            </select>

          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="">Discount type</label>
            <select name="discount_type_search" id="discount_type_search" class="form-control">
              <option value="all">SearchAll</option>
              <option value="without">no discount</option>
              <option value="1">percentage</option>
              <option value="2">manual</option>
            </select>
          </div>
        </div>

        <div class="col-md-d">
          <div class="form-group">
            <label for="">ApprovedCase</label>
            <select name="is_approved_search" id="is_approved_search" class="form-control">
              <option value="all">SearchByAll</option>
              <option value="0">open</option>
              <option value="1">Approved&transferd</option>
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="">From bill date</label>
            <input type="date" name="invoice_date_from" id="invoice_date_from" value="" class="form-control">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="">To bill date</label>
            <input type="date" name="invoice_date_to" id="invoice_date_to" class="form-control">
          </div>
        </div>



<div class="clearfix"></div>
<div class="col-md-12">

        <div id="ajax_response_searchdiv">

            @if (@isset($data) && !@empty($data) && count($data)>0)
             @php
               $i=1;
             @endphp
             
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>Code</th>
                  <th>BillDate</th>

                  <th>Customer</th>
                  <th>SalesMaterialType</th>

                  <th>BillType</th>
                  <th>Bill_total</th>

                  <th>BillStatus</th>
                  <th>Actions</th>

          
                  </thead> 
          
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                      <td>{{$info->auto_serial}}</td>
                      <td>{{$info->invoice_date}}</td>
                      <td>{{$info->customer_name}}</td>
                      <td>{{$info->sales_material_type_name}}</td>

                      <td>@if ($info->bill_type==1) Cash_Bill @elseif($info->bill_type==2) Deferred_Bill @else Undefined @endif</td>
                      <td>{{$info->total_cost*(1)}}</td>

                      <td>@if ($info->is_approved==1) approved invoice @else open bill @endif</td>


                      <td>
                        @if($info->is_approved==0)
                          <button data-autoserial="{{$info->auto_serial}}" class="btn btn-sm btn-warning load_invoice_update_modal">Edit</button>
                          <a href="{{route('admin.sales_invoices.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
                          @endif
                         <button data-autoserial="{{$info->auto_serial}}" class="btn btn-sm load_invoice_details_modal btn-info">View</button>
                        </td>
          
                  </tr>
                  @php
                    $i++;
                  @endphp
                  @endforeach
                  </tbody>  
                
              </table>

             <br>
             {{ $data->links() }}<br>

        @else
         <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
        @endif
        </div>
            </div>
      </div>

    </div>


  <!--Invoice Modal Mirror-->
    <div class="modal fade" id="AddNewInvoiceModalMirror">
      <div class="modal-dialog modal-xl">
        <div class="modal-content bg-warning">
          <div class="modal-header">
            <h4 class="modal-title text-center">Invoice price display mirror</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" id="AddNewInvoiceModalMirrorBody" style="background-color: white !important;color: black">   
 
    
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  <!--end Invoice Modal Mirror-->
    <!--start Invoice Modal Active-->
    <div class="modal fade" id="AddNewInvoiceModalActive">
      <div class="modal-dialog modal-xl">
        <div class="modal-content bg-warning">
          <div class="modal-header">
            <h4 class="modal-title text-center">Add New Active Invoice</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" id="AddNewInvoiceModalActiveBody" style="background-color: white !important;color: black">   
 
    
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!--end Invoice Modal Active-->
    <!--start  Update Invoice Modal-->
    <div class="modal fade" id="updateInvoiceModalActiveInvoice">
      <div class="modal-dialog modal-xl">
        <div class="modal-content bg-warning">
          <div class="modal-header">
            <h4 class="modal-title text-center">Update Active Invoice</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" id="updateInvoiceModalActiveInvoiceBody" style="background-color: white !important;color: black">   
 
    
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!--end  Update Invoice Modal-->
    <!--start InvoiceModalActiveDetails-->
    <div class="modal fade" id="InvoiceModalActiveDetails">
      <div class="modal-dialog modal-xl">
        <div class="modal-content bg-warning">
          <div class="modal-header">
            <h4 class="modal-title text-center">Sales Invoice Details</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" id="InvoiceModalActiveDetailsBody" style="background-color: white !important;color: black">   
 
    
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
   <!--end InvoiceModalActiveDetails-->




</div>

 

@endsection
@section('script')

    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/sales_invoices.js')}}"></script>

 <script>
 
     //Initialize Select2 Elements
     $('.select2').select2({
       theme: 'bootstrap4'
     });
   
 
   </script>
 @endsection

 {{-- <button type="button" class="btn btn-warning swalDefaultWarning">
  Launch Warning Toast
</button> --}}
{{-- <button type="button" class="btn btn-warning toastrDefaultWarning">
  Launch Warning Toast
</button> --}}