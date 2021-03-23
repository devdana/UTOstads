<header class="header">
    <div class="logo">
        <img src="/assets/Logo.png" alt="">
        <div>
            <a href="/"><h1>رزولوت</h1>
            <p>برای اونهایی که به هدف میزنند !</p>
            </a>
        </div>
    </div>
    <div class="auth">
        @if(Auth::check())
            <div class="auth">
                <a href="/logout" class="mdi mdi-power"></a>
                <span>{{farsiNum(Auth::user()->name)}}
                </span>
            </div>
        @else
            <div id="unauthenticated">
                <a href="/login" class="prime">ورود به حساب کاربری</a>
                <a href="/register">ثبت نام</a>
            </div>
        @endif
    </div>
</header>
