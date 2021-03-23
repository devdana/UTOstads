<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\TelegramAPI as api;

class Professor extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'firstName','lastName','firstNameEng','lastNameEng','photoUrl','email','degree','utId','utProfileUrl'
    ];
    public function colleges() {
        return $this->belongsToMany(\App\Models\College::class);
    }
    public function fullName() {
        return $this->firstName.' '.$this->lastName;
    }
    public function votes() {
        return $this->hasMany(\App\Models\Vote::class);
    }
    public function hasVotes() {
        return $this->votes->count();
    }
    public function score($section='mean') {
        return round($this->votes->pluck($section)->avg());
    }
    public function scoreForHumen() {
        if($this->votes->count()) {
            return api::farsiNum($this->score()." از 5 ( بر اساس ".$this->votes->count()." رای )");
        }
        return 'هیچ نظری برای استاد ثبت نشده.';
    }
    public function bestSection() {
        $sections=[
            ['teaching','تدریس'],
            ['behaviour','اخلاق'],
            ['workPreassure','فشار درسی'],
            ['grading','نمره دهی'],
        ];
        $result = $sections[0];
        foreach($sections as $i) {
            if($this->score($i[0])>=$this->score($result[0])) {
                $result=$i;
            }
        }
        return $result[1];
    }
    
    public function worstSection() {
        $sections=[
            ['teaching','تدریس'],
            ['behaviour','اخلاق'],
            ['workPreassure','فشار درسی'],
            ['grading','نمره‌دهی'],
        ];
        $result = $sections[0];
        foreach($sections as $i) {
            if($this->score($i[0])<=$this->score($result[0])) {
                $result=$i;
            }
        }
        return $result[1];
    }
}
