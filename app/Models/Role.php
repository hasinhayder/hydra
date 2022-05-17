<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','slug'
    ];
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];
}
