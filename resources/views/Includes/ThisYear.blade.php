<div id="time_card">
    <h3>{{farsiNum(\Morilog\Jalali\Jalalian::now()->getDayOfYear())}} روز</h3>
    <p class="passed">از سال {{farsiNum(\Morilog\Jalali\Jalalian::now()->getYear())}} سپری شده </p>
    <div class="progress">
        <div class="bar" style="width:{{round(100*(\Morilog\Jalali\Jalalian::now()->getDayOfYear()/365))}}%;"></div>
    </div>
</div>
