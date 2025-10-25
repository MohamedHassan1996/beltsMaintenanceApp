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

class GenerateReportNumberController extends Controller implements HasMiddleware
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

            // Get the latest report
            $latestReport = MaintenanceReport::latest()->first();

            if ($latestReport && $latestReport->report_number) {
                // Extract numeric part before the dash
                [$lastNumber, $lastYear] = explode('-', $latestReport->report_number);

                // Increment number
                $newNumber = (int)$lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // Current year (last two digits)
            $yearSuffix = date('y');

            // Combine into format: 7135-25
            $newReportNumber = $newNumber . '-' . $yearSuffix;

            DB::commit();

            return ApiResponse::success([
                'reportNumber' => $newReportNumber,
            ], '');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }



}
