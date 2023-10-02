@extends('layouts.admin')
@section('title')
    Category Settings
@endsection
@section('contentheader')
Categories
{{-- Stores --}}
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.inv_itemcard.index')}}">
        Categories
    </a>
@endsection
@section('contentheaderactive')
   View category details
@endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">Show Category Informations</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if (@isset($data) && !@empty($data))
        <div class="row">

      <table id="example2" class="table table-bordered table-hover">
         <tr>
            <td colspan="3">
             <label for="">SystemCategoryCode :</label>
             {{$data['item_code']}}
             <input type="hidden" name="token_search" id="token_search" value="{{csrf_token()}}">
             {{-- <input type="hidden" name="ajax_search_movements" id="ajax_search_movements" value="{{route('admin.inv_itemcard.ajax_search_movements')}}"> --}}
            </td>
        </tr>

       <tr>
        <td>
          <label for="barcode">CategoryCode</label><br>
          {{$data['barcode']}}
        </td>
        <td>
          <label for="name">CategoryName</label><br>
          {{$data['name']}}
        </td>
        <td>
          <label for="item_type">Expenses</label><br>
          @if($data['item_type']==1)fixed @elseif($data['item_type']==2)Advertisements &teachers  @elseif($data['item_type']==3) Trustees @else Undefined @endif
          </td>
       </tr>

       <tr>
        <td><label for="inv_itemcard_categories_id">MainCategory</label><br>
          {{$data['inv_itemcard_categories_name']}}</td>
          <td>
            <label for="parent_inv_itemcard_id">ParentCategory</label><br>
            {{$data['parent_item_name']}}
          </td>
        <td>
          <label for="uom_id">Period/BasicUnit</label><br>
          {{$data['uom_name']}}
        </td>
       </tr>

       <tr>
        <td @if($data['does_has_retailunit']==0) colspan="3" @endif>
         <label for="does_has_retailunit">IsThereFragmentUnit?</label><br>
         @if($data['does_has_retailunit']==1) yes @else no @endif
        </td>
        @if ($data['does_has_retailunit']==1)
        <td>
          <label for="">Period/FragmentUnit</label><br>
          {{$data['retail_uom_name']}}
        </td>
        <td>
          <label for="">The ratio {{$data['uom_name']}} / {{$data['retail_uom_name']}}</label><br>
          {{$data['retail_uom_quntToParent']*1}}
        </td>
           @endif
       </tr>

        <tr>
          <td>
            <label for="price">PriceReferenceToBasicUnit ({{$data['uom_name']}})</label><br>
            {{$data['price']*1}}
          </td>
          <td>
            <!--delete this td-->
            <label for="nos_gomla_price">Price/nos_gomla</label><br>
            {{$data['nos_gomla_price']*1}}
          </td>
          <td>
            <!--delete this td-->
            <label for="gomla_price">Price/gomla ({{$data['uom_name']}})</label><br>
            {{$data['gomla_price']*1}}
          </td>
          
        </tr>

        <tr>
         <td @if($data['does_has_retailunit']==0) colspan="3" @endif>
          <label for="cost_price">CostPrice with ({{$data['uom_name']}})</label><br>
          {{$data['cost_price']*1}}

         </td>
         @if($data['does_has_retailunit']==1)
         <td>
          <label for="price_retail">Price/price_retail ({{$data['retail_uom_name']}})</label><br>
          {{$data['price_retail']*1}}
         </td>
         <td>
          <label> Price/nos_gomla_price_retail ({{ $data['retail_uom_name']}})</label> <br>
          {{ $data['nos_gomla_price_retail'] *1}}
       </td>
          @endif
        </tr>

        {{-- <tr>
          <td colspan="2">
             كمية الصنف الحالية (  {{ $data['All_QUENTITY']*1  }} {{ $data['Uom_name']  }})
          </td>
       </tr> --}}
 
       <tr>
        <td>
           <label>HasFixedPrice</label> <br>
           @if($data['has_fixed_price']==1) fixed  @else  not fixed @endif
        </td>
        <td colspan="2">
           <label>Status</label> <br>
           @if($data['active']==1) active  @else  inactive @endif
        </td>
     </tr>



        <tr>
            <td class="width30">IllustrativeImage</td>
            <td colspan="2">
                <div class="image">
                    <img src="{{asset('assets/admin/uploads').'/'.$data['photo']}}" alt="" class="custom_img">
                </div>
            </td>
        </tr>

        <tr>
            <td class="width30">DateOfLastUpdate</td>
            <td colspan="2">
                
                @if ($data['updated_by']>0 and $data['updated_by']!=null)
                   
                @php
                    $d=new DateTime($data['updated_at']);
                    $date=$d->format("Y-m-d");
                    $time=$d->format("h:i");
                    $newDateTime=date("A",strtotime($time));
                    $newDateTimeType=$newDateTime=="AM"?'A.M.':'P.M.';
                @endphp
                {{$date}}
                {{$time}}
                {{$newDateTimeType}}
                By
            {{$data['updated_by_admin']}}


                @else
                 There is no update   
                @endif
                <a href="{{route('admin.inv_itemcard.edit',$data['id'])}}" class="btn btn-sm btn-warning">Edit</a>

            </td>
        </tr>
        
      </table>

        @else
         <div class="alert alert-danget">Sorry ! There are no data to display .</div>   
        @endif
        </div>

    </div>
</div>


    
@endsection