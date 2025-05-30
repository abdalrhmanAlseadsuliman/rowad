<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function recentlyReadBooks()
    {
        return $this->hasMany(RecentlyReadBook::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function studyPlans()
    {
        return $this->belongsToMany(StudyPlan::class, 'study_plan_book');
    }
}
