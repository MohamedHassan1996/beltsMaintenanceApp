<?php

namespace App\Services\Select\Parameter;

use App\Models\BeltsParameterValue;
class ParameterSelectService
{
    public function getAllParameters(int $parameterId)
    {
        return BeltsParameterValue::select(['guid as value', 'parameter_value as label'])->where('parameter_id', $parameterId)->get();
    }

    /*public function getAllSubCategories(int $categoryId)
    {
        return Category::select(['id as value', 'name as label'])->where('parent_id', $categoryId)->get();
    }*/

    public function getAllMaterialeDeportare()
    {
        return BeltsParameterValue::select(['guid as value', 'parameter_value as label'])->whereNotNull('description')->where('parameter_id', 12 )->get();
    }

}

