<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudyPlan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'plan_id');
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'study_plan_book');
    }
}
