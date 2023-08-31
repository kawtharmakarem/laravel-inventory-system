
@if (@isset($data) && !@empty($data))
        
@php
    $i=1;
@endphp

<table id="example2" class="table table-bordered table-hover">
  <thead class="custom_thead">
    <th>#</th>
    <th>
      UnitName
  </th>
    <th>
      UnitType
    </th>
    <th>Status</th>
    <th>Created_at</th>

    <th>Updated_at</th>
    <th>Actions</th>


    </thead> 

  <tbody>
    @foreach ($data as $info)
    <tr>
        <td>{{$i}}</td>
        <td>{{$info->name}}</td>

        <td>
          @if ($info->is_master==1)
          basic_unit
          @else
          fragment_unit   
          @endif
        </td>

        
       <td>@if($info->active==1)
          Active  
        @else
        InActive
            
        @endif
      </td>
        <td>
          @php
              $dt=new DateTime($info->created_at);
              $date=$dt->format("Y-m-d");
              $time=$dt->format("h:i a");
              $newDateTime=date("A",strtotime($time));
              // $newDateTimeType=(($newDateTime=='AM')? '.A.M':'.P.M');
          @endphp
          {{$date}}
          {{$time}}
          {{-- {{$newDateTimeType}} --}}
          <br>
          By
          {{$info->added_by_admin}}

        </td>

        <td>
          @if($info->updated_by>0 and $info->updated_by!=null)
          @php
              $dt=new DateTime($info->updated_at);
              $date=$dt->format("Y-m-d");
              $time=$dt->format("h:i a");
              $newDateTime=date("A",strtotime($time));
              // $newDateTimeType=(($newDateTime=="AM")? '.A.M':'.P.M');

          @endphp
          {{$date}}
          {{$time}}
          {{-- {{$newDateTimeType}} --}}
          <br>
          By
          {{$info->updated_by_admin}}
          @else
          no updated to show
           @endif
        </td>


        <td>
            <a href="{{route('admin.uoms.edit',$info->id)}}" class="btn btn-sm btn-warning">Edit</a>
            <a href="{{route('admin.uoms.delete',$info->id)}}" class="btn btn-sm btn-danger are_you_sure">Delete</a>
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
<br>

@else
<div class="alert alert-danger">Sorry ! There are no data to display .</div>   
@endif