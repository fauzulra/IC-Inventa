<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'name',
        'project_id',
        'quantity',
        'unit',
        'request_date',
        'status'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class); 
    }

    public function project() 
    {
        return $this->belongsTo(Project::class);
    }
}