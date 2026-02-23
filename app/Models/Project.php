<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'code', 
        'name', 
        'location', 
        'logistics_contact', 
        'status'
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

}