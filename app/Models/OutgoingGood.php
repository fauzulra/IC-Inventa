<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingGood extends Model
{
    use HasFactory;

    protected $table = 'outgoing_goods';

    protected $fillable = [
        'source_project_id', 
        'destination_project_id', 
        'material_id', 
        'quantity', 
        'date_shipped'
    ];

    // Relasi untuk proyek asal
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
    public function sourceProject()
    {
        return $this->belongsTo(Project::class, 'source_project_id');
    }

    public function destinationProject()
    {
        return $this->belongsTo(Project::class, 'destination_project_id');
    }
}
    

