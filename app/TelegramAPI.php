<?php


namespace App;


use Illuminate\Support\Facades\Http;

class TelegramAPI
{
    private $key='1540931985:AAH_o1_VmCh4sAWuZo8mrnJif5ImvuREoPA';
    public static function farsiNum($str) {
        $arabic_eastern = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $arabic_western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        return str_replace($arabic_western, $arabic_eastern, $str);
    }
    public static function sendMessage($chatId,$text,$markup='') {
        $markup= preg_replace('/\s\s+/', ' ', $markup);
        $url='https://api.telegram.org/bot'.env('BOT_KEY').'/sendMessage';
        // $text=TelegramAPI::farsiNum($text);
        return Http::get($url,['chat_id'=>$chatId,'text'=>$text,'reply_markup'=>$markup,'parse_mode'=>'HTML']);
    }
    public static function removeKeyboard() {
        return ' {"remove_keyboard":true
                        ,"selective":false}
            ';
    }
    public static function homeKeyboard() {
        return '{
            "keyboard": [
                [
                    {"text":"🔎 جستجو استاد"}
                ],
                [
                    {"text":"🤖 درباره پروژه"},
                    {"text":"❓ استاد پیدا نشد ؟"}
                ]
                ],
                "resize_keyboard":true
        }';
    }

}