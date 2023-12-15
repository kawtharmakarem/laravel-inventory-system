
@if (@isset($data) && !@empty($data) && count($data)>0)
        
@php
    $i=1;
@endphp

<table id="example2" class="table table-bordered table-hover">TotalExchange :({{$totalCollectInSearch*(-1)}})
    <thead class="custom_thead">
        <th>auto_serial</th>
        <th>Cheque number</th>
        <th>Treasury</th>
        <th>amount</th>
        <th>Transaction</th>
        <th>AccountName</th>
        <th>Description</th>
        <th>User</th>
        <th>Actions</th>

    </thead>

    <tbody>
        @foreach ($data as $info)
            <tr>
                <td>{{ $info->auto_serial }}</td>
                <td>{{ $info->isal_number }}</td>
                <td>{{ $info->treasury_name }}
                    <br>
                    Shift{{$info->shift_code}}
                </td>
                <td>{{ $info->money * 1 * -1 }}</td>
                <td>{{ $info->mov_type_name }}</td>
                <td>{{ $info->account_name }}
                    <br>
                    ({{ $info->account_type_name }})
                </td>
                <td>{{ $info->byan }}</td>
                <td>
                    @php
                        $dt = new DateTime($info->created_at);
                        $date = $dt->format('Y-m-d');
                        $time = $dt->format('h:i');
                        $newDateTime = date('A', strtotime($time));
                        $newDateTimeType = $newDateTime == 'AM' ? 'A.M.' : 'P.M.';
                    @endphp
                    {{ $date }}<br>
                    {{ $time }}
                    {{ $newDateTimeType }}<br>
                    By
                    {{ $info->added_by_admin }}
                </td>
                <td>
                    <a href="{{ route('admin.treasuries.edit', $info->id) }}"
                        class="btn btn-sm btn-warning">Print</a>
                    <a href="{{ route('admin.treasuries.details', $info->id) }}"
                        class="btn btn-sm btn-secondary">More</a>
                </td>

            </tr>
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