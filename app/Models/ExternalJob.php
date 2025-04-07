<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalJob extends Model
{
    use HasFactory;

    protected $fillable = ['external_id', 'title', 'description', 'link'];
    
    protected $primaryKey = 'external_id';
    public $incrementing = false;
}
