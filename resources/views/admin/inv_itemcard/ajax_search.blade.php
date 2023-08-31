@if (@isset($data) && !@empty($data) && count($data)>0)
        
@php
    $i=1;
@endphp

<table id="example2" class="table table-bordered table-hover">
    <thead class="custom_thead">
      <th>#</th>
      <th>
        Name
        {{-- Store_Name --}}
    </th>
      <th>Expenses</th>
      <th>Category</th>
      <th>
        Basic
        Category
      </th>
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
          <td>{{$i}}</td>

          <td>{{$info->name}}</td><!--name:arabic course-->
          
          <!--expenses:advertising-->
          <td>@if($info->item_type==1)
            FixedExpenses  
          @elseif($info->item_type==2)
            Advertisements&teachers
          @elseif($info->time_type==3)
             Trustees
             @else
             Undefined
          @endif
        </td>
         
        <td>{{$info->inv_itemcard_categories_name}}</td><!--course type :languages-->

        <td>{{$info->parent_item_name}}</td><!--i will delete later-->


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
              <a href="{{route('admin.inv_itemcard.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
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

  <div class="col-md-12" id="ajax_pagination_in_search">
    {{ $data->links() }}

 </div>

@else
<div class="alert alert-danger">Sorry ! There are no data to display .</div>   
@endif