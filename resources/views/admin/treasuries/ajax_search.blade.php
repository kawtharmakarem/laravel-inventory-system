
@if (@isset($data) && !@empty($data))
        
@php
    $i=1;
@endphp

<table id="example2" class="table table-bordered table-hover">
    <thead class="custom_thead">
      <th>#</th>
      <th>Treasury_Name</th>
      <th>Is_Main</th>
      <th>Last_exchange_cheque(debit)</th>
      <th>Last_collect_cheque(credit)</th>
      <th>Status</th>
      <th>Actions</th>

      </thead> 

    <tbody>
      @foreach ($data as $info)
      <tr>
          <td>{{$i}}</td>
          <td>{{$info->name}}</td>
          <td>@if ($info->is_master==1)
            Main  
          @else
              Sub
          @endif</td>
          <td>{{$info->last_isal_exchange}}</td>
          <td>{{$info->last_isal_collect}}</td>
          <td>@if($info->active==1)
            Active  
          @else
          InActive
              
          @endif</td>
          <td>
              <a href="{{route('admin.treasuries.edit',$info->id)}}" class="btn btn-sm btn-warning">Edit</button>
              <a href="" data-id="{{$info->id}}" class="btn btn-sm btn-secondary">More</button>
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