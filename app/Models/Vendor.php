<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }
}
