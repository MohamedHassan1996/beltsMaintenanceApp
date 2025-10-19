<?php

namespace App\Models;

use App\Enums\Maintenance\MaintenanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Maintenance extends Model
{
    protected $connection = 'beltsMaintenances';


    public function anagraphic()
    {
        return $this->belongsTo(Anagraphic::class, 'anagraphic_guid', 'guid');
    }

    public function status(): HasOne
    {

        return $this->hasOne(BeltsParameterValue::class, 'guid', 'status_guid');
    }

    public function importance(): HasOne{

        return $this->hasOne(BeltsParameterValue::class, 'guid', 'importance_guid');
    }
}
