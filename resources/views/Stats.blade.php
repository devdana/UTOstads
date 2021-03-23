<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ثبت رای برای {{$professor->fullName()}}</title>
    <link rel="stylesheet" href="/css/All.css">
    <link rel="stylesheet" href="/css/materialdesignicons.min.css">
</head>
<body>
<script src="/js/chart.min.js"></script>
<section class="container" id="stats">
   <h1>نتایج نظرسنجی درباره {{$professor->fullName()}}</h1>
   <div class="uad">
        <div class="cell">
        <i class="mdi mdi-arrow-up"></i>
        <span class="title">قوی ترین محدوده</span>
        <span class="val">{{$professor->bestSection()}}</span>
        </div>
        <div class="cell">
        <i class="mdi mdi-arrow-down"></i>
        <span class="title">ضعیف ترین محدوده</span>
        <span class="val">اخلاق</span>
        </div>
   </div>
   <div class="vote_distro">
       <div class="guide">
           <h3>راهنمای نمودار ها</h3>
           <div>
               <div style="background:#6090FF;"></div>
               <span>عالی</span>
           </div>  
           <div>
               <div style="background:#4ECC47;"></div>
               <span>خوب</span>
           </div>
           <div>
               <div style="background:#8D8D8D;"></div>
               <span>متوسط</span>
           </div>
           <div>
               <div style="background:#FDD864;"></div>
               <span>بد</span>
           </div>
           <div>
               <div style="background:#FCA6AE;"></div>
               <span>افتضاح</span>
           </div>
       </div>
       @foreach($sections as $section)
       <div class="stat_section">
           <h2>{{$section[1]}}</h2>
   <canvas id="{{$section[0]}}_chart" height="180px" class="mean_chart"></canvas>
<script>
var ctx = document.getElementById('{{$section[0]}}_chart').getContext('2d');
var {{$section[0]}}Chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels:["5/5","4/5","3/5","2/5","1/5"],
        datasets: [{
            label: 'تعداد آرا',
            data: [
                @for($i=5;$i>=1;$i+=-1)
                {{$professor->votes->where($section[0],$i)->count()}}
                @if($i!==1)
                ,
                @endif
                @endfor
            ],
            backgroundColor: [
                '#6090FF',
                '#4ECC47',
                '#8D8D8D',
                '#FDD864',
                '#FCA6AE'
            ]
            // borderWidth: 1
        }]
    },
    options: {
        tooltips : {
            enabled:false
        },
        legend  : {
            defaultFontFamily:"'IRANYekan'",
            position:'bottom',
            align:'start',
            display:0
        }
        
    }
});
</script>
   </div>
   @endforeach
   </div>
</section>
<script src="/js/jquery.js"></script>
<script src="/js/vote.js"></script>
</body>
</html>
