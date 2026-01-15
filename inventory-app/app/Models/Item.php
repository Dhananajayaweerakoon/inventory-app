<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    
    protected $fillable = ['name', 'unit', 'quantity'];

   
    public function logs() {
        return $this->hasMany(InventoryLog::class)->latest();
    }
}