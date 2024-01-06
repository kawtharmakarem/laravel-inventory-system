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
        {{-- Stores --}}
    </a>
@endsection
@section('contentheaderactive')
   view 
@endsection
@section('content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title card_title_center">
        Categories Information
        {{-- Stores Informations --}}
    </h3>

{{-- for search/start --}}
      <input type="hidden" name="token_search" id="token_search"  value="{{csrf_token()}}">
      <input type="hidden" name="ajax_search_url" id="ajax_search_url" value="{{route('admin.inv_itemcard.ajax_search')}}">
{{-- for search/end --}}

      <a href="{{route('admin.inv_itemcard.create')}}" class="btn btn-warning">Add New</a>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
       <div class="row">

     <div class="col-md-4">
      <input checked type="radio" name="searchbyradio" id="searchbyradio" value="name"><label for="">ByName</label>
      <input  type="radio" name="searchbyradio" id="searchbyradio" value="barcode"><label for="">ByCode</label>
      <input type="radio" name="searchbyradio" id="searchbyradio" value="item_code"><label for="">BySystemCode</label>
      <input type="text" style="margin-top: 6px; !important;" id="search_by_text"  name="search_by_text" placeholder="name-code-systemcode" class="form-control"><br>
    </div>   
  
    <div class="col-md-4">
      <div class="form-group">
        <label for="item_type_search">SearchByExpenses</label>
        <select name="item_type_search" id="item_type_search" class="form-control">
         <option value="all">All</option>
         <option value="1">FixedExpenses</option>
         <option value="2">ConsumptionExpenses </option>
         <option value="3">Trustees</option>
         </select>
         @error('item_type_search')
           <span class="text-danger">{{$message}}</span>
         @enderror
      </div>
    </div>
      
<div class="col-md-4">
  <div class="form-group">
    <label for="inv_itemcard_categories_id">SearchByCategory</label>
    <select name="inv_itemcard_categories_id_search" id="inv_itemcard_categories_id_search" class="form-control">
      <option value="all">all</option>
      @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                  @foreach ($inv_itemcard_categories as $info )
                  <option value="{{ $info->id }}"> {{ $info->name }} </option>
                  @endforeach
                  @endif
    </select>
    @error('inv_itemcard_categories_id_search')
    <span class="text-danger">{{$message}}</span>
      
    @enderror
  </div>
</div>


      

    {{-- <div class="col-md-4">
      <div class="form-group">
        <label for="">SearchByUnit</label>
        <select name="is_master_search" id="is_master_search" class="form-control">
          <option value="all">Search all</option>
          <option value="1">basic_unit</option>
          <option value="0">fragment_unit</option>
        </select>
      </div>
    </div> --}}

  <div class="clearfix"></div>
       
        <div id="ajax_response_searchdiv" class="col-md-12">

            @if (@isset($data) && !@empty($data))
        
            @php
                $i=1;
            @endphp
  
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                  <th>Code</th>
                  <th>
                    Name
                    {{-- Store_Name --}}
                </th>
                  <th>Expenses</th>
                  <th>Category</th>
                  {{-- <th>
                    Basic
                    Category
                  </th> --}}
                  <th>
                    Period/
                    basic_unit
                  </th>
                  <th>
                    Period/
                    fragment_unit
                  </th>
                  <th>Status</th>
                  <th>Actions</th>

          
                  </thead> 
          
                <tbody>
                  @foreach ($data as $info)
                  <tr>
                      <td>{{$info->item_code}}</td>

                      <td>{{$info->name}}</td><!--name:arabic course-->
                      
                      <!--expenses:advertising-->
                      <td>@if($info->item_type==1)
                        FixedExpenses  
                      @elseif($info->item_type==2)
                        ConsumptionExpenses
                      @elseif($info->time_type==3)
                         Trustees
                         @else
                         Undefined
                      @endif
                    </td>
                     
                    <td>{{$info->inv_itemcard_categories_name}}</td><!--course type :languages-->

                    {{-- <td>{{$info->parent_item_name}}</td> --}}


                    <td>{{$info->uom_name}}</td>

                    <td>{{$info->retail_uom_name}}</td>
                     
 
                     <td>@if($info->active==1)
                        Active  
                      @else
                      InActive
                       
                      @endif
                    </td>
                    
                      
                      <td>
                          <a href="{{route('admin.inv_itemcard.edit',$info->id)}}" class="btn btn-sm btn-warning" style="margin-bottom: 1px">Edit</a>
                          {{-- <a href="{{route('admin.inv_itemcard.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a> --}}
                          <a href="{{route('admin.inv_itemcard.show',$info->id)}}" class="btn btn-sm btn-info">show</a>

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

    
@endsection
@section('script')
<script src="{{asset('assets/admin/js/inv_itemcard.js')}}"></script>
    
@endsection