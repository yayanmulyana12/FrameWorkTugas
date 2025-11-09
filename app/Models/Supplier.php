<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'supplier_name',
        'supplier_address',
        'phone',
        'comment',
    ];

    // biar saat dipanggil $supplier->name tetap bisa
    public function getNameAttribute()
    {
        return $this->supplier_name;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id', 'id');
    }
}
