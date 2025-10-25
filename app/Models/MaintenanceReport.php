<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MaintenanceReport extends Model
{
    protected $fillable = [
        'maintenance_guid',
        'report',
        'report_date',
        'leave_at',
        'arrive_at',
        'is_one_work_period',
        'work_times',
        'number_of_meals',
        'note',
        'path',
        'parameter_guids',
        'maintenance_detail_types_guids',
        'report_number'
    ];

    protected function casts(): array
    {
        return [
            'work_times' => 'array'
        ];
    }

    protected function productCodices(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? array_map('trim', explode(',', $value)) : [],
            set: fn ($value) => is_array($value) ? implode(',', $value) : $value
        );
    }
}
