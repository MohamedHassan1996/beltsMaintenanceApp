<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Maintenance;

use App\Enums\Maintenance\MaintenanceType;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Maintenance;
use App\Models\MaintenanceReport;
use App\Models\MaintenanceStockItem;
use App\Models\BeltsParameterValue;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class OperatorMaintenanceReportController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }

    public function store(Request $request) {



        try {
            $data = $request->all();


            DB::beginTransaction();


            $maintenanceTypesGuids = [
                '28e1c7d1-3a11-4660-8e6c-66dab6e17ec5' => MaintenanceType::INSTALLATION->value,
                'fa7202e8-65a4-49b4-83f5-39784ca1f22f' => MaintenanceType::MAINTANANCE->value,
                'e7740d9b-551f-416f-954c-a648c281d436' => MaintenanceType::CONTROL->value
            ];

            foreach ($data['reports'] as $key => $report) {
                $path = null;


                if(isset($report['path'])) {
                    $path = Storage::disk('public')->putFileAs('maintenance_reports', $report['path'], Str::random(10).'.'.$report['path']->getClientOriginalExtension());
                }

                $parameterGuids = isset($report['parameterGuids']) ? implode(',', $report['parameterGuids']) : "";


                $maintenance = Maintenance::where('guid', $report['maintenanceGuid'])->first();

                $maintenanceStartDate = isset($report['date']) ? Carbon::parse($report['date'])->startOfDay() : Carbon::parse($maintenance->start_date)->startOfDay();

                $maintenanceReport = MaintenanceReport::create([
                    'maintenance_guid' => $report['maintenanceGuid'],
                    'leave_at' => $report['leaveAt'],
                    'arrive_at' => $report['arriveAt'],
                    'is_one_work_period' => $report['isOneWorkPeriod'],
                    'work_times' => $report['workTimes'],
                    'number_of_meals' => $report['numberOfMeals'],
                    'note' => $report['note'],
                    'path' => $path,
                    'report_date' => $maintenanceStartDate,
                    'parameter_guids' => $parameterGuids,
                ]);

            }


            DB::commit();

            return ApiResponse::success([], __('crud.created'));

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


    }
}
