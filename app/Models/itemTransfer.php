<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemTransfer extends Model
{
    use HasFactory;

    protected $table = 'item_transfers';

    protected $fillable = [
        'material_id', 
        'from_project_id', 
        'to_project_id', 
        'quantity', 
        'transfer_date', 
        'status'
    ];

    public function material() {
        return $this->belongsTo(Material::class);
    }

    public function fromProject() {
        return $this->belongsTo(Project::class, 'from_project_id');
    }

    public function toProject() {
        return $this->belongsTo(Project::class, 'to_project_id');
    }
}
