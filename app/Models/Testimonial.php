<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'content',
        'rating',
        'is_approved',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class); // Laravel otomatis mencari tabel 'students'
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}