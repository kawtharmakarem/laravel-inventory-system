<br>
<p style="color: brown">
    @if ($the_final_balance>0)
   Debit ({{$the_final_balance*1}}) S.P
    @elseif ($the_final_balance<0)
    Credit({{$the_final_balance*(-1)}}) S.p
    @else
     Balanced   
    @endif
</p>