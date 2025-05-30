<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudyPlanBook extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
