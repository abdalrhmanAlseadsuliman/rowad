<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id', 'note'];

    // علاقة الملاحظة مع الطالب
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة الملاحظة مع الكتاب
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}