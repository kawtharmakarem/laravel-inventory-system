@extends('layouts.admin')
@section('title')
Purchase
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
Transactions
@endsection
@section('contentheaderlink')
<a href="{{route('admin.supplierswithorders.index')}}">Purchase invoices </a>
@endsection
@section('contentheaderactive')
ViewDetails
@endsection
@section('content')
<div class="row">
    <div class="col-12">
<div class="card">

    <div class="card-header">
      <h3 class="card-title card_title_center">Purchase invoice details</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div id="ajax_response_searchDivParentBill">

        @if (@isset($data) && !@empty($data))
        
        <table id="example2" class="table table-bordered table-hover">
        
          <tr>
              <td class="width30">code</td>
              <td>{{$data['auto_serial']}}</td>
          </tr>
  
          <tr>
            <td class="width30">The invoice number registered in the purchase invoice</td>
            <td>{{$data['doc_num']}}</td>
        </tr>
  
        <tr>
          <td class="width30">Bill date</td>
          <td>{{$data['order_date']}}</td>
      </tr>
  
        <tr>
          <td class="width30">supplier name</td>
          <td>{{$data['supplier_name']}}</td>
      </tr>
  
  
  
          <tr>
              <td class="width30">Bill type</td>
              <td>@if ($data['bill_type']==1)
                Cash bill
              @else
              Deferred bill
              @endif</td>
          </tr>

          <tr>
            <td class="width30">Branch for received bill</td>
            <td class="width30">{{$data['store_name']}}</td>
          </tr>
  
          <tr>
            <td class="width30">Total invoice</td>
            <td>{{$data['total_before_discount']*(1)}}</td>
        </tr>
  
          @if ($data['discount_type']!=null)
  
          <tr>
            <td class="width30">Discount on the invoice</td>
            <td>
            @if ($data['discount_type']==1)
             Percent_discount ({{$data['discount_percent']*(1)}}) EqualTo ({{$data['discount_value']*1}})
            @else
            Manual_discount EqualTo ({{$data['discount_value']}})
  
            @endif
            </td>
  
        </tr>
         @else
  
          <tr>
            <td class="width30">Discount on the invoice</td>
            <td>There is no discount</td>
        </tr>
            
          @endif
          
          <tr>
              <td class="width30">Added value percentage</td>
              <td>
                @if ($data['tax_percent']>0)
                No added value
                @else
                 Percentage({{$data['tax_percent']*(1)}}) % and EqualTo ({{$data['tax_value']*(1)}}) 
                @endif
              </td> 
          </tr>
  
          <tr>
            <td class="width30">Invoice status</td>
            <td>@if($data['is_approved']==1) close and archived @else open @endif</td>
          </tr>
          <tr>
            <td class="width30">Added Date</td>
            <td>
                 @php
                    $d=new DateTime($data['created_at']);
                    $date=$d->format("Y-m-d");
                    $time=$d->format("h:i a");
                    // $newDateTime=date("A",strtotime($time));
                    // $newDateTimeType=$newDateTime=="AM"?'A.M.':'P.M.';
                @endphp
                {{$date}}
                {{$time}}
                {{-- {{$newDateTimeType}} --}}
                By
            {{$data['added_by_admin']}}
  
            </td>
        </tr>
        
       
          <tr>
              <td class="width30">Date of last Update</td>
              <td>
                  
                  @if ($data['updated_by']>0 and $data['updated_by']!=null)
                     
                  @php
                      $d=new DateTime($data['updated_at']);
                      $date=$d->format("Y-m-d");
                      $time=$d->format("h:i a");
                      // $newDateTime=date("A",strtotime($time));
                      // $newDateTimeType=$newDateTime=="AM"?'A.M.':'P.M.';
                  @endphp
                  {{$date}}
                  {{$time}}
                  {{-- {{$newDateTimeType}} --}}
                  By
              {{$data['updated_by_admin']}}
  
  
                  @else
                   There is no update   
                  @endif
                  @if($data['is_approved']==0)
                  <a href="{{route('admin.supplierswithorders.edit',$data['id'])}}" class="btn btn-sm btn-warning">Edit</a>
                  <a href="{{route('admin.supplierswithorders.delete',$data['id'])}}" class="btn btn-sm are_you_sure btn-danger">Delete</a>
                  <button  type="button" class="btn btn-sm btn-secondary" id="load_close_approve_invoice">Load&transfer</button>
                  
                  @endif
              </td>
          </tr>
          
        </table>
       
      </div>
    <!--treasuries_delivery-->
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Items added to the invoice
        @if($data['is_approved']==0)
        <button type="button" class="btn btn-warning" id="load_modal_add_detailsBtn">
          Add items to the invoice
        </button>
        @endif
      </h3>
      <input type="hidden" id="token_search" name="" value="{{csrf_token()}}">
      <input type="hidden" id="ajax_get_item_uoms_url" name="" value="{{route('admin.supplierswithorders.get_item_uoms')}}">
      <input type="hidden" id="ajax_add_new_details" name="" value="{{route('admin.supplierswithorders.add_new_details')}}">
      <input type="hidden" id="ajax_reload_itemsdetails" name="" value="{{route('admin.supplierswithorders.reload_itemsdetails')}}">
      <input type="hidden" id="ajax_reload_parent_bill" name="" value="{{route('admin.supplierswithorders.reload_parent_bill')}}">
      <input type="hidden" id="ajax_load_edit_item_details" name="" value="{{route('admin.supplierswithorders.load_edit_item_details')}}">
      <input type="hidden" id="ajax_load_modal_add_details" name="" value="{{route('admin.supplierswithorders.load_modal_add_details')}}">
      <input type="hidden" id="ajax_edit_item_details" name="" value="{{route('admin.supplierswithorders.edit_item_details')}}">
      <input type="hidden" id="ajax_load_modal_approve_invoice" name="" value="{{route('admin.supplierswithorders.load_modal_approve_invoice')}}">
      <input type="hidden" id="ajax_load_usershiftDiv" name="" value="{{route('admin.supplierswithorders.load_usershiftDiv')}}">

      <input type="hidden" id="autoserialparent" name="" value="{{$data['auto_serial']}}">


    </div>
    
    <div id="ajax_response_searchDivDetails">

      @if (@isset($details) && !@empty($details) && count($details)>0)
  
      @php
          $i=1;
      @endphp

      <table id="example2" class="table table-bordered table-hover">
          <thead class="custom_thead">
            <th>#</th>
            <th>Item</th>
            <th>Uom</th>
            <th>Ammount</th>
            <th>Price</th>
            <th>Total</th>

            <th>Actions</th>
            </thead> 
    
          <tbody>
            @foreach ($details as $info)
            <tr>
                <td>{{$i}}</td>
                <td>{{$info->item_card_name}}
                  @if ($info->item_card_type==2)
                  <br>
                  production_date {{$info->production_date}}
                  <br>
                  Expire_date {{$info->expire_date}}
                    
                  @endif
                </td>
                <td>{{$info->uom_name}}</td>
                <td>{{$info->deliverd_quantity*(1)}}</td>
                <td>{{$info->unit_price*(1)}}</td>
                <td>{{$info->total_price*(1)}}</td>
                <td>
                  @if ($data['is_approved']==0)
                  <button data-id="{{$info->id}}"  class="btn btn-sm btn-warning load_edit_item_details">Edit</button>
                  <a href="{{route('admin.supplierswithorders.delete_details',["id"=>$info->id,"id_parent"=>$data['id']])}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
                    
                  @endif
                </td>
                

                
            </tr>
            @php
                $i++;
            @endphp
            @endforeach
            </tbody>  
          
        </table>
  @else
   <div class="alert alert-danger">Sorry ! There are no data to display .</div>   
  @endif
      
      </div>
    <!--end of treasuries_delivery-->

    @else
    <div class="alert alert-danget">Sorry ! There are no data to display .</div>   
   @endif

   </div>
 </div>
</div>
</div>
    

<!--modal-->

<div class="modal fade modal-x1" id="add_item_modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-warning">
      <div class="modal-header">
        <h4 class="modal-title text-center">Add items to the invoice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="add_item_modal_body" style="background-color: white !important;color: black">   


        

       

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--/modal-->

<div class="modal fade modal-x1" id="edit_item_modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-warning">
      <div class="modal-header">
        <h4 class="modal-title text-center">Edit item in the invoice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="edit_item_modal_body" style="background-color: white !important;color: black">   





        

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--start of approve modal-->
<div class="modal fade modal-x1" id="ModalApproveInvoice">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-warning">
      <div class="modal-header">
        <h4 class="modal-title text-center">Load&transfer the invoice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="ModalApproveInvoice_body" style="background-color: white !important;color: black">   





        

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--end of approve modal-->


    @endsection
    @section('script')

    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/supplier_with_orders.js')}}"></script>
 <script>
 
     //Initialize Select2 Elements
     $('.select2').select2({
       theme: 'bootstrap4'
     });
   
 
   </script>
 @endsection



