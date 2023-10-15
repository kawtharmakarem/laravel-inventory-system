<div class="row">
  <div class="col-12">
    <div class="card">

      <div class="card-header">
        <h3 class="card-title card-title-center">
          Show more
        </h3>
      </div>

      <div class="card-body">
        @if(@isset($data) && !@empty($data))
        <table id="example2" class="table table-bordered table-hover">

          <tr>
            <td class="width30">Delegate/percent_type</td>
            <td>@if($data['percent_type']==1) FixedWage @else PercentWage @endif</td>
          </tr>

          <tr>
            <td class="width30">Delegate commission in sales/Popular price</td>
            <td>{{$data['percent_sales_commission_kataei']*1}}</td>
          </tr>
           
          <tr>
            <td class="width30">Delegate commission in sales/Half wholesale price</td>
            <td>{{$data['percent_sales_commission_nosjomla']*1}}</td>
          </tr>

          <tr>
            <td class="width30">Delegate commission in sales/Wholesale price</td>
            <td>{{$data['percent_sales_commission_jomla']*1}}</td>
          </tr>

          <tr>
            <td class="width30">Percent collect commission</td>
            <td>{{$data['percent_collect_commission']*1}}</td>
          </tr>

          <tr>
            <td class="width30">Delegate's Address</td>
            <td>{{$data['address']}}</td>
          </tr>

          <tr>
            <td class="width30">Delegate's Phones</td>
            <td>{{$data['phones']}}</td>
          </tr>

          <tr>
            <td class="width30">Notes</td>
            <td>{{$data['notes']}}</td>
          </tr>

          <tr>
            <td class="width30">ActivationCase</td>
            <td>@if($data['active']==1) Active @else Inactive @endif</td>
          </tr>
      
          <tr>
            <td class="width30">Added Date</td>
            <td>
             @php
               $dt=new DateTime($data['created_at']);
               $date=$dt->format("Y-m-d");
               $time=$dt->format("h:i");
               $newDateTime=date("A",strtotime($time));
               $newDateTimeType=(($newDateTime=="AM")? "AM" : "PM");

             @endphp
             {{$date}}
             {{$time}}
             {{$newDateTimeType}}<br>
             By
             {{$data['added_by_admin']}}
            </td>
          </tr>

          <tr>
            <td class="width30">Last Update Date</td>
            <td>
              @if($data['updated_by']>0 and $data['updated_by']!=null)
              @php
                $dt=new DateTime($data['updated_at']);
                $date=$dt->format("Y-m-d");
                $time=$dt->format("h:i");
                $newDateTime=date("A",strtotime($time));
                $newDatetimeType=(($newDateTime=="AM")? 'AM':'PM');
              @endphp

              {{$date}}
              {{$time}}
              {{$newDateTimeType}}<br>
              By
              {{$data['updated_by_admin']}}
              @else
              no update
              @endif
              <a href="{{route('admin.adminpanelsetting.edit')}}" class="btn btn-sm btn-warning">Update</a>
            </td>
          </tr>
          


        </table>
        @else
        <div class="alert alert-danger">
          Sorry ! there is no data to Display
        </div>
      @endif
      </div>
      
    </div>
  </div>
</div>