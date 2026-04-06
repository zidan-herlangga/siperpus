<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookComment extends Model
{
    protected $fillable = [
      'book_id', 
      'student_id', 
      'content', 
      'is_approved'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}