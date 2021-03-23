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
<section class="container">
    <div class="profile">
        <img src="http://profile.ut.ac.ir{{$professor->photoUrl}}" alt="">
        <div class="info">
            <h1>{{$professor->fullName()}}</h1>
            <h2>{{$professor->degree}}</h2>
            <div class="colleges">
            @foreach($professor->colleges as $college)
                <span>
                <i class="mdi mdi-office-building"></i>
                {{$college->title}}
                </span>
            @endforeach
            </div>
        </div>
    </div>
    <div class="pre_vote">
        <p>شما در حال ثبت رای برای {{$professor->fullName()}} هستید .
            سعی کنید تا حد ممکن عادلانه و منصفانه رای دهید .
            رای شما میتواند در آینده به دانشجویان دیگر کمک کند که تصمیمات تحصیلی بهتری بگیرند.
</br>
برای ثبت رای روی شروع نظرسنجی کلیک کنید .
        </p>
        <span id="start_survey">
            <i class="mdi mdi-clipboard-edit-outline"></i>
            شروع نظرسنجی
        </span>
    </div>
    <div class="vote_sections">
        <form method="post" action="/vote/{{$professor->id}}?cred={{$user->chatId}}">
        <div class="section">
            <p>
                <i class="mdi mdi-arrow-left"></i>
                اخلاق و رفتار این استاد را چگونه ارزیابی میکنید ؟
            </p>
            <div class="options">
                <input type="hidden" name='behaviour'>
                <span class="option" rate="5"><i class="mdi mdi-emoticon"></i>خوش اخلاق و با انرژی</span>
                <span class="option" rate="4"><i class="mdi mdi-emoticon-happy"></i>خوش رفتار و مودب</span>
                <span class="option" rate="3"><i class="mdi mdi-emoticon-neutral"></i>قابل قبول</span>
                <span class="option"rate="2"><i class="mdi mdi-emoticon-angry"></i>بد اخلاق</span>
                <span class="option" rate="1"><i class="mdi mdi-emoticon-dead"></i> بسیار بداخلاق و عصبی</span>
            </div>
            <span class="nextQuestion">
                <i class="mdi mdi-arrow-left"></i>سوال بعدی
            </span>
        </div>
        <div class="section">
            <p>
                <i class="mdi mdi-arrow-left"></i>
                تدریس و دانش علمی استاد را چگونه ارزیابی میکنید ؟
            </p>
            <div class="options">
                <input type="hidden" name='teaching'>
                <span class="option" rate="5"><i class="mdi mdi-checkbox-multiple-marked-circle-outline"></i>تدریس عالی و کامل</span>
                <span class="option" rate="4"><i class="mdi mdi-checkbox-marked-circle-outline"></i>تدریس خوب و کافی</span>
                <span class="option" rate="3"><i class="mdi mdi-checkbox-blank-outline"></i>میتوانست بهتر باشد</span>
                <span class="option"rate="2"><i class="mdi mdi-checkbox-blank-circle-outline"></i>بد نیست</span>
                <span class="option" rate="1"><i class="mdi mdi-checkbox-blank-off-outline"></i> تدریس ناقص یا گنگ</span>
            </div>
            <span class="nextQuestion">
                <i class="mdi mdi-arrow-left"></i>
                سوال بعدی
            </span>
        </div>
        <div class="section">
            <p>
                <i class="mdi mdi-arrow-left"></i>
                فشار درسی را از نظر حجم تمرین ها ، امتحانات یا پروژه ها با این استاد چگونه است ؟
            </p>
            <div class="options">
                <input type="hidden" name='workPreassure'>
                <span class="option" rate="5"><i class="mdi mdi-leaf"></i>مناسب و معقول</span>
                <span class="option" rate="3"><i class="mdi mdi-thermometer-chevron-up"></i>کمی زیاد</span>
                <span class="option" rate="3"><i class="mdi mdi-thermometer-chevron-down"></i>کمی ناکافی</span>
                <span class="option"rate="1"><i class="mdi mdi-muffin"></i>خیلی کم / راحت</span>
                <span class="option" rate="1"><i class="mdi mdi-ev-plug-chademo"></i> خیلی زیاد / سخت</span>
            </div>
            <span class="nextQuestion">
                <i class="mdi mdi-arrow-left"></i>
                سوال بعدی
            </span>
        </div>

        <div class="section">
            <p>
                <i class="mdi mdi-arrow-left"></i>
                این استاد در تصحیح امتحانات و نمره دادن چگونه عمل میکند ؟
            </p>
            <div class="options">
                <input type="hidden" name='grading'>
                <span class="option" rate="5"><i class="mdi mdi-chevron-triple-up"></i>دست-باز نمره میدهد</span>
                <span class="option" rate="3"><i class="mdi mdi-chevron-up"></i>کمی ارفاق میکند</span>
                <span class="option" rate="3"><i class="mdi mdi-approximately-equal"></i>عادلانه و منطقی نمره میدهد</span>
                <span class="option"rate="1"><i class="mdi mdi-emoticon-cry-outline"></i>سخت نمره میدهد</span>
                <span class="option" rate="1"><i class="mdi mdi-emoticon-devil-outline"></i>خیلی سخت میگیرد</span>
            </div>
            <button type="submit" id="submit">
                <i class="mdi mdi-check"></i>
                ثبت و ارسال رای
            </button>
        </div>
        {{csrf_field()}}
        </form>
    </div>
</section>
<script src="/js/jquery.js"></script>
<script src="/js/vote.js"></script>
</body>
</html>
