<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    use HasFactory;

    protected $table = 'item_transfers';

    protected $fillable = [
        'material_id', 
        'quantity', 
        'transfer_date', 
        'to_project_id', 
        'status'
    ];

    public function material() {
        return $this->belongsTo(Material::class);
    }

    public function toProject() {
        return $this->belongsTo(Project::class, 'to_project_id');
    }
}
