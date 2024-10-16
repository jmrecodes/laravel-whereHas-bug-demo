<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function lineItems()
    {
        return $this->hasMany(InvoiceProductRelationship::class)->whereHas('product', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}
