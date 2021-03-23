<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;
    protected $fillable=['title'];
    public function professors() {
        return $this->belongsToMany(App\Models\Professor::class);
    }
}
