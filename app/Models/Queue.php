<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'report_id'
        ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function delayReport()
    {
        return $this->belongsTo(DelayReport::class, 'report_id');
    }
}
