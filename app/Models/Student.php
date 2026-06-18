<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\CustomVerifyEmail;
use App\Enums\StudentStatus;

use Illuminate\Notifications\Notifiable;
use App\Notifications\NewBorrowingNotification;
use Filament\Notifications\Notification;
use Laravel\Sanctum\HasApiTokens;

// Reset Password
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Student extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, HasApiTokens, CanResetPasswordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nis',
        'class',
        'avatar',
        'contact',
        'email',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'avatar' => '',
    ];

    protected static function booted(): void
    {
        static::created(function (Student $student) {
            $admins = \App\Models\Admin::all();

            Notification::make()
                ->title('Siswa Baru Terdaftar!')
                ->body($student->name . ' telah mendaftar sebagai siswa baru.')
                ->sendToDatabase($admins);
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => StudentStatus::class,
        ];
    }

    public function getIsActiveFlagAttribute(): bool
    {
        return $this->is_active === StudentStatus::Aktif;
    }

    public function getAvatarAttribute($value): string
    {
        return $value ?: 'default-avatar.png';
    }
    
    /**
     * Get the borrowings for the student.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function bookComments()
    {
        return $this->hasMany(BookComment::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}