@extends('layouts.admin')
@section('title')
Update Category
@endsection
@section('contentheader')
    Categories
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.inv_itemcard.index')}}">
        Categories
    </a>
@endsection
@section('contentheaderactive')
   Update
@endsection
@section('content')

<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Update Category
    </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       
        <form action="{{route('admin.inv_itemcard.update',$data['id'])}}" method="post" enctype="multipart/form-data">
            <div class="row">
            @csrf

        <div class="col-md-6">
             <div class="form-group">
                <label for="barcode">Category_Code <span class="barcodeCheckMessage"></span> </label>
                <input type="text" id="barcode" name="barcode" class="form-control" value="{{old('barcode',$data['barcode'])}}" placeholder="Enter category code" />
                 @error('barcode')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>
        
        <div class="col-md-6">
         <div class="form-group">
                <label for="name">
                   CategoryName
                </label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name',$data['name'])}}" placeholder="Enter Category Name"/>
                 @error('name')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>
       
        <div class="col-md-6">
            <div class="form-group">
                <label for="item_type">Expenses</label>
                {{-- @if($counterUsedBefore>0) disabled @endif --}}
                <select  class="form-control" name="item_type" id="item_type">
                    <option value="">Select..</option>
                    <option {{old('item_type',$data['item_type'])==1 ? 'selected':''}} value="1">FixedExpenses</option>
                    <option {{old('item_type',$data['item_type'])==2 ? 'selected':''}} value="2">ConsumptioExpenses</option>
                    <option {{old('item_type',$data['item_type'])==3 ? 'selected':''}} value="3">Trustees</option>
    
                </select>
                 @error('item_type')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="form-group">
                <label for="inv_itemcard_categories_id">Select MainCategory</label>
                <select name="inv_itemcard_categories_id" id="inv_itemcard_categories_id" class="form-control">
                <option value="">select....</option>
                @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                 @foreach ($inv_itemcard_categories as $info )
                   <option {{old('inv_itemcard_categories_id',$data['inv_itemcard_categories_id'])==$info->id ? 'selected':''}} value="{{$info->id}}">{{$info->name}}</option>  
                 @endforeach   
                @endif
                </select>
                 @error('inv_itemcard_categories_id')
                   <span class="text-danger">{{$message}}</span>  
                 @enderror
            </div>
        </div>

<!--delete this field-->
        <div class="col-md-6">
          <div class="form-group">
              <label for="parent_inv_itemcard_id">Is MainCategory in this table</label>
              <select name="parent_inv_itemcard_id" id="parent_inv_itemcard_id" class="form-control">
              <option selected value="0">is_main</option>
              @if (@isset($item_card_data) && !@empty($item_card_data))
               @foreach ($item_card_data as $info )
                 <option {{old('parent_inv_itemcard_id',$data['parent_inv_itemcard_id'])==$info->id ? 'selected':''}} value="{{$info->id}}">{{$info->name}}</option>  
               @endforeach   
              @endif
              </select>
               @error('parent_inv_itemcard_id')
                 <span class="text-danger">{{$message}}</span>  
               @enderror
          </div>
      </div>

        
        <div class="col-md-6">
          <div class="form-group">
              <label for="uom_id">Period/BasicUnit</label>
              {{-- @if($counterUsedBefore>0) disabled @endif --}}
              <select  name="uom_id" id="uom_id" class="form-control">
              <option value="">select...</option>
              @if (@isset($inv_uoms_parent) && !@empty($inv_uoms_parent))
               @foreach ($inv_uoms_parent as $info )
                 <option {{old('uom_id',$data['uom_id'])==$info->id ? 'selected':''}} value="{{$info->id}}">{{$info->name}}</option>  
               @endforeach   
              @endif
              </select>
               @error('uom_id')
                 <span class="text-danger">{{$message}}</span>  
               @enderror
          </div>
      </div>


      <div class="col-md-6">
        <div class="form-group">
            <label for="does_has_retailunit">Period/Is There fragmentUnit?</label>
            {{-- @if($counterUsedBefore>0) disabled @endif --}}
            <select class="form-control" name="does_has_retailunit" id="does_has_retailunit">
                <option value="">Select..</option>
                <option {{old('does_has_retailunit',$data['does_has_retailunit'])==1 ? 'selected':''}}  value="1">Yes</option>
                <option {{old('does_has_retailunit',$data['does_has_retailunit'])==0 ? 'selected':''}}  value="0">No</option>
           </select>
             @error('does_has_retailunit')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>
    </div>


      <div class="col-md-6" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1) style="display: none;" @endif  id="retail_uom_idDiv">
        <div class="form-group">
            <label for="retail_uom_id">Period/FragmentUnit with reference to(<span class="parentuomname"></span>)</label>
            {{-- @if($counterUsedBefore>0) disabled @endif --}}
            <select  name="retail_uom_id" id="retail_uom_id" class="form-control">
            <option value="">select...</option>
            @if (@isset($inv_uoms_child) && !@empty($inv_uoms_child))
             @foreach ($inv_uoms_child as $info )
               <option {{ old('retail_uom_id',$data['retail_uom_id'])==$info->id ? 'selected' : ''}} value="{{$info->id}}">{{$info->name}}</option>  
             @endforeach   
            @endif
            </select>
             @error('retail_uom_id')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>
    </div>

    <div class="col-md-6 related_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1) style="display: none;" @endif>
      <div class="form-group">
      <label for="retail_uom_quntToParent">Number of fragments (<span class="parentuomname"></span>)/(<span class="childuomname"></span>)</label>
      {{-- @if($counterUsedBefore>0) disabled @endif --}}
      <input  type="text" id="retail_uom_quntToParent" name="retail_uom_quntToParent" class="form-control" value="{{old('retail_uom_quntToParent',$data['retail_uom_quntToParent']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="Enter number of fragments"/>
       @error('retail_uom_quntToParent')
         <span class="text-danger">{{$message}}</span>  
       @enderror
  </div>
</div>

<div class="col-md-6 related_parent_counter">
  <div class="form-group">
  <label for="price">Price reference to basicUnit (<span class="parentuomname"></span>)</label>
  <input type="text" id="price" name="price" class="form-control" value="{{old('price',$data['price']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="Enter price with basic unit"/>
   @error('price')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>


<!--todo :delete this field-->
<div class="col-md-6 related_parent_counter">
  <div class="form-group">
  <label for="nos_gomla_price">Price/nos_gomla (<span class="parentuomname"></span>)</label>
  <input type="text" id="nos_gomla_price" name="nos_gomla_price" class="form-control" value="{{old('nos_gomla_price',$data['nos_gomla_price']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="price"/>
   @error('nos_gomla_price')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>

<!--todo :delete this field-->
<div class="col-md-6 related_parent_counter">
  <div class="form-group">
  <label for="gomla_price">Price/gomla (<span class="parentuomname"></span>)</label>
  <input type="text" id="gomla_price" name="gomla_price" class="form-control" value="{{old('gomla_price',$data['gomla_price']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="price"/>
   @error('gomla_price')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>


<div class="col-md-6 related_parent_counter">
  <div class="form-group">
  <label for="cost_price">cost_price (<span class="parentuomname"></span>)</label>
  <input type="text" id="cost_price" name="cost_price" class="form-control" value="{{old('cost_price',$data['cost_price']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="price"/>
   @error('cost_price')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>



<!--todo :delete this field-->
<div class="col-md-6 related_retail_counter"  @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
  <div class="form-group">
  <label for="price_retail">Price/price_retail (<span class="childuomname"></span>)</label>
  <input type="text" id="price_retail" name="price_retail" class="form-control" value="{{old('price_retail',$data['price_retail']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="price"/>
   @error('price_retail')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>

<!--todo :delete this field-->
<div class="col-md-6 related_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
  <div class="form-group">
  <label for="nos_gomla_price_retail">Price/nos_gomla_price_retail (<span class="childuomname"></span>)</label>
  <input type="text" id="nos_gomla_price_retail" name="nos_gomla_price_retail" class="form-control" value="{{old('nos_gomla_price_retail',$data['nos_gomla_price_retail']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="price"/>
   @error('nos_gomla_price_retail')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>

<!--todo :delete this file-->
<div class="col-md-6 related_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
  <div class="form-group">
  <label for="gomla_price_retail">Price/gomla_price_retail (<span class="childuomname"></span>)</label>
  <input type="text" id="gomla_price_retail" name="gomla_price_retail" class="form-control" value="{{old('gomla_price_retail',$data['gomla_price_retail']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="price"/>
   @error('gomla_price_retail')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>

<!--todo delete this field-->
<div class="col-md-6 related_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
  <div class="form-group">
  <label for="cost_price_retail">cost_price_retail (<span class="childuomname"></span>)</label>
  <input type="text" id="cost_price_retail" name="cost_price_retail" class="form-control" value="{{old('cost_price_retail',$data['cost_price_retail']*1)}}"  oninput="this.value=this.value.replace(/[^0-9.]/g,'')" placeholder="price"/>
   @error('cost_price_retail')
     <span class="text-danger">{{$message}}</span>  
   @enderror
</div>
</div>

<div class="col-md-6">
  <div class="form-group">
      <label for="has_fixed_price">Has fixed price</label>
        <select class="form-control" name="has_fixed_price" id="has_fixed_price">
                <option value="">Select..</option>
                <option  {{old('has_fixed_price',$data['has_fixed_price'])==1 ? 'selected':''}} value="1">fixed</option>
                <option  {{old('has_fixed_price',$data['has_fixed_price'])==0 ? 'selected':''}} value="0">not fixed</option>
            </select>
             @error('has_fixed_price')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>    
     </div> 

        
<div class="col-md-6">
  <div class="form-group">
      <label for="active">Status</label>
        <select class="form-control" name="active" id="active">
                <option value="">Select..</option>
                <option  {{old('active',$data['active'])==1 ? 'selected':''}} value="1">active</option>
                <option  {{old('active',$data['active'])==0 ? 'selected':''}} value="0">inactive</option>
            </select>
             @error('active')
               <span class="text-danger">{{$message}}</span>  
             @enderror
        </div>    
     </div> 
 
<div class="col-md-6" style="border: solid 5px #121E36;margin: 10px;">
  <div class="form-group">
    <label for="logo">Category Logo</label>
    <div class="image">
        <img src="{{ asset('assets/admin/uploads').'/'.$data['photo'] }}" alt="Logo" id="uploading" class="custom_img">
        <button type="button" class="btn btn-sm btn-danger" id="update_image">ChangeImage</button>
        <button type="button" class="btn btn-sm btn-danger" style="display: none" id="cancel_update_image">Cancel</button>
    </div>
    <div id="oldimage"></div>

</div>
         </div>
        
        </div>

        <div class="form-group text-center">
         <button  id="do_edit_item_cardd" type="submit" class="btn btn-warning">SaveUpdates</button>
         <a href="{{route('admin.inv_itemcard.index')}}" class="btn btn-secondary">Cancel</a>

        </div>



        </form>

    </div>
</div>
    
    
@endsection

<script type="text/javascript">
  function readURL(input){
  if(input.files && input.files[0]){
    var reader=new FileReader();
    reader.onload=function(e){
      $('#uploaded_img').attr('src',e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
  }
  </script>

@section('script')
<script src="{{asset('assets/admin/js/inv_itemcard.js')}}"></script> 
<script>
  var uom_id=$('#uom_id').val();
  if(uom_id!=''){
    var name=$("#uom_id option:selected").text();
    $(".parentuomname").text(name);
  }
  var retail_uom_id=$("#retail_uom_id").val();
  if(retail_uom_id!=''){
    var name=$("#retail_uom_id option:selected").text();
    $(".childuomname").text(name);
  }
  </script>  
@endsection
