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
}
