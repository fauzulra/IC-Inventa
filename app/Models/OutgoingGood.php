<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingGood extends Model
{
    use HasFactory;

    protected $table = 'outgoing_goods';

    protected $fillable = [
        'item_id',
        'quantity',
        'date_shipped',
        'destination', 
    ];

    protected $casts = [
        'date_shipped' => 'date',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    protected static function booted()
    {
        static::created(function ($outgoing) {
            $material = $outgoing->material;
            
            if ($material) {
                $material->decrement('stock', $outgoing->quantity);
            }
        });

        static::deleted(function ($outgoing) {
            $material = $outgoing->material;
            
            if ($material) {
                $material->increment('stock', $outgoing->quantity);
            }
        });
        
    }
}
    

