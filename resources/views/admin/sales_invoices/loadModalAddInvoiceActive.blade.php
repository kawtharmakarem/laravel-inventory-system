@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Invoice date</label>
            <input type="date" name="invoice_date_activeAdd" id="invoice_date_activeAdd" value="@php echo date('Y-m-d'); @endphp" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">InvoiceCategory</label>
            <select name="sales_material_type_id_activeAdd" id="sales_material_type_id_activeAdd" class="form-control">
                <option value="">Select invoice category</option>

                @if (@isset($sales_material_types) && !@empty($sales_material_types))
                    @foreach ($sales_material_types as $info)
                        <option value="{{ $info->id }}">{{ $info->name }}</option>
                    @endforeach

                @endif
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Is there a customer</label>
            <select name="is_has_customer_activeAdd" id="is_has_customer_activeAdd" class="form-control">
                <option value="1" selected>He's is a customer</option>
                <option value="0" selected>Not a customer</option>
            </select>
        </div>
    </div>

    <div class="col-md-4" id="customer_codeDiv">
        <div class="form-group">
            <label for="">Customers data (<a title="Add new customer" href="#">New<i
                        class="fa fa-plus-circle"></i></a>)</label>
            <select name="customer_code_activeAdd" id="customer_code_activeAdd" class="form-control select2">
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
            <select name="delegate_code_activeAdd" id="delegate_code_activeAdd" class="form-control select2">
                <option value="">select delegate</option>
                @if (@isset($delegates) && !@empty($delegates))
                    @foreach ($delegates as $info)
                        <option value="{{ $info->delegate_code }}">{{ $info->name }}</option>
                    @endforeach

                @endif
            </select>
        </div>
    </div>
     
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Bill type</label>
            <select name="bill_type_activeAdd" id="bill_type_activeAdd" class="form-control">
                <option value="1">Cash</option>
                <option value="2">Deferred</option>
            </select>
        </div>
    </div>

    <div class="col-md-12 text-center">
        <hr>
        <button type="submit" id="Do_Add_new_active_invoice" class="btn btn-warning">AddNew (open new active
            invoice)</button>
    </div>

</div>

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
