<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'description',
        'phone',
        'address'
    ];

    public function material()
    {
        return $this->hasMany(Material::class);
    }

    public function incomingGoods()
    {
        return $this->hasMany(IncomingGood::class);
    }
}
