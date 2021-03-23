<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professor;
use App\Models\User;
use App\Models\Vote;
use App\TelegramAPI as api;
class VoteController extends Controller
{
    public function New(Professor $professor,Request $request) {
        if(!$request->has('cred')) {
            return 'درخواست نامعتبر !';
        }
        $user = User::where('chatId',$request['cred'])->get()->first();
        if(!$user) {
            return 'Invalid credentials.';
        }
        $check=Vote::where('user_id',$user->id)->where('professor_id',$professor->id)->count(); 
        if($check) {
            return 'Already Voted !';
        }
        return view('Vote',['user'=>$user,'professor'=>$professor]);
    }
    public function Store(Professor $professor,Request $request) {
        $request->validate([
            'cred'=>'required',
            'teaching'=>'required|integer|min:1|max:5',
            'workPreassure'=>'required|integer|min:1|max:5',
            'grading'=>'required|integer|min:1|max:5',
            'behaviour'=>'required|integer|min:1|max:5',
        ]);
        $user = User::where('chatId',$request->cred)->get()->first();
        if(!$user) {
            return 'Invalid Request';
        }
        $check=Vote::where('user_id',$user->id)->where('professor_id',$professor->id)->count();
        if($check) {
            return 'Already Voted !';
        }
        $vote = new Vote();
        $vote->professor_id=$professor->id;
        $vote->user_id=$user->id;
        $vote->teaching=$request->teaching;
        $vote->workPreassure=$request->workPreassure;
        $vote->grading=$request->grading;
        $vote->behaviour=$request->behaviour;
        $vote->mean =round( array_sum([
            intval($request->teaching),
            intval($request->workPreassure),
            intval($request->grading),
            intval($request->grading),
        ])/4);
        $vote->save();
        api::sendMessage($user->chatId,'✅ رای شما برای '.$professor->fullName().'  با موفقیت ثبت شد.',api::removeKeyboard());
        $user->backHome();
        return view('Done');
    }
    public function Stats(Professor $professor) {
        if(!$professor->hasVotes()) {
            return "No Votes Yet.";
        }
        $sections = [
            ['teaching','کیفیت تدریس'],
            ['behaviour','اخلاق'],
            ['grading','نمره دهی'],
            ['workPreassure','فشار کاری'],
        ];
        return view('Stats',['professor'=>$professor,'sections'=>$sections]);
    }
}
