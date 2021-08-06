<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'author', 'publication_date', 'user_id'
    ];

    protected $appends = [
        'borrowed'
    ];

    protected $casts = [
        'publication_date' => 'date:Y-m-d',
    ];

    public function getBorrowedAttribute(): bool
    {
        return !empty($this->user_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
