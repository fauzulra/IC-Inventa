<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingGood extends Model
{
    use HasFactory;

    protected $table = 'outgoing_goods';

    protected $fillable = [
        'material_id',
        'project_id', // Ini menggantikan destination
        'quantity',
        'date_shipped',
    ];

    protected $casts = [
        'date_shipped' => 'date:d/m/Y',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Relasi ke tabel Project
    public function project()
    {
        return $this->belongsTo(Project::class);
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
    

