<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'email', 'location', 'employment_type',
        'is_approved', 'is_spam', 'is_external', 'external_link'
    ];
    
    protected $casts = [
        'is_approved' => 'boolean',
        'is_spam' => 'boolean',
        'is_external' => 'boolean',
    ];
    
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
    
    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }
    
    public function scopeLocal($query)
    {
        return $query->where('is_external', false);
    }
}
