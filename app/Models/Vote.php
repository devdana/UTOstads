<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    public function professor() {
        return $this->belongsTo(App\Models\Professor::class);
    }
    public function user() {
        return $this->belongsTo(App\Models\User::class);
    }
}
