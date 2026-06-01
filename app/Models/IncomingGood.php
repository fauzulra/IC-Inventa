<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingGood extends Model
{
    use HasFactory;

    // Pastikan fillable sesuai dengan field di database
    protected $fillable = [
        'project_id',
        'material_id',
        'supplier_id',
        'quantity',
        'po_number',
        'date_received',
    ];

    // Relasi ke tabel Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relasi ke tabel Material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Relasi ke tabel Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}