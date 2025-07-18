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

    /**
     * ملاحظات المستخدم الحالي فقط على هذا الكتاب
     */
    public function userNotes()
    {
        return $this->hasMany(Note::class)->where('user_id', auth()->id());
    }


    public function recentlyReadBooks()
    {
        return $this->hasMany(RecentlyReadBook::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function studyPlans()
    {
        return $this->belongsToMany(StudyPlan::class, 'study_plan_book');
    }
}
