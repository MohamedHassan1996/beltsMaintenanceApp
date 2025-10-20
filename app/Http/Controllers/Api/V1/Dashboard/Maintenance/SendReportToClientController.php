<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Maintenance;

use App\Helpers\ApiResponse;
use App\Mail\SendMaintenanceReportMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MaintenanceReport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class SendReportToClientController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
        ];
    }

    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();

            $maintenanceReports = MaintenanceReport::whereIn('maintenance_guid', $request->maintenanceGuids)->where('is_sent', 0)->get();

            foreach ($maintenanceReports as $maintenanceReport) {
                if(!empty($maintenanceReport->path)) {

                    Mail::to('mr10dev10@gmail.com')->send(new SendMaintenanceReportMail(
                    $maintenanceReport->path
                    ));
                    Mail::to('heshamatef050@gmail.com')->send(new SendMaintenanceReportMail(
                    $maintenanceReport->path
                    ));
                    Mail::to('mohamedelhaddad997@gmail.com')->send(new SendMaintenanceReportMail(
                    $maintenanceReport->path
                    ));
                    $maintenanceReport->is_sent = 1;
                    $maintenanceReport->save();
                }

            }

            DB::commit();

            return ApiResponse::success([], 'Maintenance report sent successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }



}
