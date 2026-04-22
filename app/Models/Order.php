<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'request_date',
        'user_id',
        'project_id',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class); 
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
