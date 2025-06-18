<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'cost',
        'quantity',
        'weight',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'cost'      => 'decimal:2',
            'weight'    => 'decimal:2',
            'is_active' => 'boolean',
            'quantity'  => 'integer',
        ];
    }

    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function inStock(): bool
    {
        return $this->quantity > 0;
    }
}
