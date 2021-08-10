<?php

namespace App\Models;

use Carbon\Carbon;
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
        'publication_date' => 'date',
    ];

    public function getBorrowedAttribute(): bool
    {
        return !empty($this->user_id);
    }

    public function setPublicationDateAttribute($publication_date)
    {
        $this->attributes['publication_date'] =  Carbon::parse($publication_date);
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
