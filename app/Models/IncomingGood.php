<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingGood extends Model
{
    use HasFactory;

    protected $table = 'incoming_goods';

    protected $fillable = [
        'material_id', 
        'supplier_id', 
        'quantity', 
        'date_received', 
        'notes'
    ];

    public function material() {
        return $this->belongsTo(Material::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    protected static function booted()
    {
        static::created(function ($incoming) {
            $material = $incoming->material;
            
            if ($material) {
                $material->increment('stock', $incoming->quantity);
            }
        });

        static::deleted(function ($incoming) {
            $material = $incoming->material;
            
            if ($material) {
                $material->decrement('stock', $incoming->quantity);
            }
        });
    }
}