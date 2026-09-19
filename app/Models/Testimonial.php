<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Testimonial extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(fn (): ?bool => Cache::forget('homepage.testimonials'));
        static::deleted(fn (): ?bool => Cache::forget('homepage.testimonials'));
    }

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