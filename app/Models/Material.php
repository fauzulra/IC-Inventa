<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';
    
    protected $fillable = [
        'name',
        'unit', 
        'stock', 
        'supplier_id'
    ];

    public function supplier() 
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function incomingGoods() 
    {
        return $this->hasMany(IncomingGood::class);
    }

    // Relasi untuk melihat histori barang ini pernah ditransfer ke mana saja
    public function itemTransfers() 
    {
        return $this->hasMany(ItemTransfer::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withPivot('stock')->withTimestamps();
    }
}