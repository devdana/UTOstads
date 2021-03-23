<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Professor;
use App\Models\College;
use App\Models\User;
use App\TelegramAPI as api;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{

    public function Main(Request $request) {
        try {
            if (!$request->isJson()) {
                return 'Go Fuck YourSelf !';
            }
            $user=$request->message['from'];
            $userId=$user['id'];
            $message=$request->message['text'];
            $find=User::where('chatId',$userId)->first();
            if(!$find) {
                $u=new User();
                $u->fullName=$user['first_name'].' '.$user['last_name'];
                $u->chatId=$userId;
                $u->username=$user['username'];
                $u->stage='justSignedUp';
                $u->save();
                $xss=api::sendMessage($userId,'✅ ثبت نام با موفقیت انجام شد . برای پیدا کردن استاد مورد نظر به بخش جستجو استاد بروید :',api::homeKeyboard());
                $u->updateStage('home');
                $find=$u;
                return $xss;
            }
            if($find and $message=='/start') {
                return $find->backHome();
            }
            return $this->handle($find,$request);
        }
        catch (\Exception $exception) {
            Log::error($exception);
            return response($exception);
        }
    }
    public function Search($query) {
        $result=[];
        $result[0]=DB::table('professors')->where('firstName','like',$query)->get();
        $result[1]=DB::table('professors')->where('lastName','like',$query)->get();
        $result[2]=DB::table('professors')->where('firstNameEng','like',$query)->get();
        $result[3]=DB::table('professors')->where('lastNameEng','like',$query)->get();
        $final = $result[0]->merge($result[1])->merge($result[2])->merge($result[3]);
        foreach(Professor::get() as $prof) {
            if($prof->firstName.' '.$prof->lastName==$query
            or $prof->lastName.' '.$prof->firstName==$query
            or strtolower($prof->firstNameEng.' '.$prof->lastNameEng)==strtolower($query)
            or strtolower($prof->lastNameEng.' '.$prof->firstNameEng)==strtolower($query)
            
            ) {
                $final->push($prof);
            }
        }
        $final=$final->unique();
        return $final;
    }
    public function SearchByRequest(Request $request)
    {
        return $this->Search($request['for']);
    }
    public function Colleges() {
        $profiles = file_get_contents("profiles.json");
        $profiles=json_decode($profiles)->results;
        foreach($profiles as $x){
            if(property_exists($x,'organistaions')) {
                foreach($x->organistaions as $org) {
                    $col = College::where('title',$org->name)->count();
                    if(!$col) {
                        $college=College::create(['title'=>$org->name]);
                    }
                }
            }
        }
        return 'OK';
    }

    public function Proffs() {
        $profiles = file_get_contents("profiles.json");
        $profiles=json_decode($profiles)->results;
        foreach($profiles as $x){
            // dd($x);
            $pro = new Professor([
                'firstName'=>$x->firstName,
                'firstNameEng'=>$x->firstName_ar_SA,
                'lastName'=>$x->lastName,
                'lastNameEng'=>$x->lastName_ar_SA,
                'degree'=>$x->degree,
                'photoUrl'=>$x->image,
                'utProfileUrl'=>$x->url,
                'utId'=>$x->teacherId,
                'email'=>$x->email,
            ]);
            $pro->save();
            if(property_exists($x,'organistaions')) {
                foreach($x->organistaions as $org) {
                    $col = College::where('title',$org->name)->get()->first();
                    $pro->colleges()->attach($col->id);
                }
            }
        }
        return 'OK';
    }

    public function handle($user,$request) {
        
        $message=$request->message['text'];
        if($message=='HOME') {
            $user->updateStage('home');
            return 'Back Home Now !';
        }
        if($user->stage=='home') {
            if($message=='🔎 جستجو استاد') {
                api::sendMessage($user->chatId,'نام استاد را وارد کنید :',api::removeKeyboard());
                return $user->updateStage('professorSearch');
            }
            if($message=="🤖 درباره پروژه") {
                $response ="🎒 استادشناسی دانشگاه تهران (آزمایشی)
                
💡 هدف از این پروژه ایجاد یک محیط بیطرف و پایدار هست که دانشجو ها هم بتونن مثل استاد ها طرف مقابل را ارزیابی کنن. این ارزیابی بعدا به سایر دانشجو ها کمک میکنه که با توجه به ویژگی های خودشون و استاد مورد نظرشون انتخاب واحد هوشمندانه‌‌تری داشته باشن و از دردسر های غیر ضروری دوری کنن . پروژه در نسخه آزمایشی هست و بعد از رفع ایرادات و مستند‌سازی ورژن اول دیپلوی میشه. بعد از انتشار نسخه اول لینک سورس کد هم در Github همینجا قرار میگیره. 
اگر دست به کد هستید و وقت آزاد دارید که کمک کنید یا نظری برای پیشرفت در ورژن بعدی دارید به من پیام بدید :
@dana_mirafzal
dana.mr8822@gmail.com";
                return api::sendMessage($user->chatId,$response);
            }
            
            if($message=="❓ استاد پیدا نشد ؟") {
                $response ="💡ما نام و مشخصات اساتید رو از رخ‌نما دانشگاه دریافت میکنیم. بنابراین اگر شما نتونستید استاد را پیدا کنید احتمالا یکی از دو حالت زیر برقراره :

1️⃣ حالت اول : الگوریتم جستجو ما خیلی قوی عمل نکرده(که البته خودمون میدونیم مشکلاتی داره و داریم سعی میکنیم بهترش کنیم) . در اینصورت لطفا جستجو رو با نام خانوادگی استاد انجام بدید. احتمالا مشکل حل میشه.
                
2️⃣ حالت دوم : استاد در سامانه رخ‌نما ثبت نشده . دیدیم که بعضی وقتا بعضی استاد ها توی رخ نما صفحه پروفایل ندارن! بالاخره تعداد زیاده پیش میاد دیگه ! در این صورت به من پیام بدید تا سریع استاد رو اضافه کنم (البته نه به رخ‌نما اون دست من نیست ! به ربات خودمون) اگر ایمیل و دانشکده های استاد رو هم داشته باشین دیگه عالی میشه!
@dana_mirafzal
";
                return api::sendMessage($user->chatId,$response);
            }
        }
        if($user->stage=='professorSearch') {
            if($message=="🔙 بازگشت") {
                return $user->backHome();
            }
            $keyboard=' {"keyboard": [';
            
            $profs = $this->Search($message);
            // return $profs;
            foreach($profs as $prof) {
                $keyboard=$keyboard.'  [{
                    "text":"👤 '.$prof->firstName.' '.$prof->lastName.'"
               }],';
            }
            $keyboard=$keyboard.'  [{
                "text":"🔙 بازگشت"
             }
            ]
            ]
            ,"resize_keyboard":true}
            ';
         
            if($profs->count()) {
                api::sendMessage($user->chatId,'انتخاب کنید :',$keyboard);
                $user->updateStage('professorsList');
                return "OK:)";
            }
            else {
                $keyboard='{
                "keyboard": [
                    [
                        {"text":"🔙 بازگشت"}
                    ]
                ],
                "resize_keyboard":true
                }';
                $user->updateStage('professorSearch');
                $x= api::sendMessage($user->chatId,'هیچ استادی تحت این نام پیدا نشد !',$keyboard);
                return $x;
            }
        }
        if($user->stage=='professorsList' and $message!=="🔙 بازگشت") {
            foreach(Professor::get() as $prof) {
                if($message=='👤 '.$prof->fullName()) {
                    $subject=$prof;
                }
            }
           if(isset($subject)) {
                $subject=Professor::find($subject->id);
                $keyboard='
            {
            "inline_keyboard":[
            [{"text":"🌟 به این استاد رای دهید","url":"'.env('APP_URL').'/vote/'.$subject->id.'?cred='.$user->chatId.'"}]
           ';
           if($subject->hasVotes()) {
               $keyboard=$keyboard.'
               ,[
                {"text":"📊 نتایج نظرسنجی","url":"'.env('APP_URL').'/stats/'.$subject->id.'"}
            ]
               ';
           }
           $keyboard=$keyboard.'
            ]
            }
            ';
                $response="
                👨‍🏫 ".$subject->firstName." ".$subject->lastName." ( ".$subject->degree." )
🏫 فعال در : ".$subject->colleges->pluck('title')->join(', ')."
✉️ ایمیل : ".$subject->email."
⭐️ امتیاز دانشجویان : ".$subject->scoreForHumen()."

";
                if($subject->hasVotes()) {
                    $response=$response.'📚 کیفیت تدریس : '.str_repeat('🟢',$subject->score('teaching')).str_repeat('⚪️',5-$subject->score('teaching'));;
                    $response=$response.'
';
                    $response=$response.'🙄 اخلاق کاری       : '.str_repeat('🟢',$subject->score('behaviour')).str_repeat('⚪️',5-$subject->score('behaviour'));;
                    $response=$response.'
';
                    $response=$response.'🌡 فشار‌ درسی      : '.str_repeat('🟢',$subject->score('workPreassure')).str_repeat('⚪️',5-$subject->score('workPreassure'));;
                    $response=$response.'
';
                    $response=$response.'📄 نمره دهی         : '.str_repeat('🟢',$subject->score('grading')).str_repeat('⚪️',5-$subject->score('grading'));;
                }
                $response=$response.'
                
                ';
                // $user->updateStage('professorProfile');
                return api::sendMessage($user->chatId,$response,$keyboard);
            }
            else {
                if(str_split($message,1)!=='👤s') {
                    if($message=="🔙 بازگشت") {
                        return $user->backHome();
                    }
                    $keyboard=' {"keyboard": [';
                    
                    $profs = $this->Search($message);
                    // return $profs;
                    foreach($profs as $prof) {
                        $keyboard=$keyboard.'  [{
                            "text":"👤 '.$prof->firstName.' '.$prof->lastName.'"
                       }],';
                    }
                    $keyboard=$keyboard.'  [{
                        "text":"🔙 بازگشت"
                     }
                    ]
                    ]
                    ,"resize_keyboard":true}
                    ';
                 
                    if($profs->count()) {
                        api::sendMessage($user->chatId,'انتخاب کنید :',$keyboard);
                        $user->updateStage('professorsList');
                        return "OK:)";
                    }
                    else {
                        $keyboard='{
                        "keyboard": [
                            [
                                {"text":"🔙 بازگشت"}
                            ]
                        ],
                        "resize_keyboard":true
                        }';
                        $user->updateStage('professorSearch');
                        $x= api::sendMessage($user->chatId,'هیچ استادی تحت این نام پیدا نشد !',$keyboard);
                        return $x;
                    }
                }
                else {
                    api::sendMessage($user->chatId,'لطفا از گزینه ها انتخاب کنید یا برای جستجو مجدد بازگشت را انتخاب کنید.');
                }
                return "ERROR";
            }
        }
        if($user->stage=='professorsList' and $message=="🔙 بازگشت") {
                return $user->backHome();           
        }
        if($user->stage=='professorProfile') {
            if($message=="🔙 بازگشت") {
                return $user->backHome();
            }
           
        }
        return "DONE. ";
    }
}
