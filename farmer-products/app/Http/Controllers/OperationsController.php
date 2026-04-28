<?php

namespace App\Http\Controllers;

use App\Services\HealthCheckService;
use Illuminate\Http\JsonResponse;

class OperationsController extends Controller
{
    public function __construct(private readonly HealthCheckService $healthChecks)
    {
    }

    public function health(): JsonResponse
    {
        return response()
            ->json($this->healthChecks->livenessReport())
            ->header('Cache-Control', 'no-store, private');
    }

    public function ready(): JsonResponse
    {
        $report = $this->healthChecks->readinessReport();

        return response()
            ->json($report, $report['status'] === 'fail' ? 503 : 200)
            ->header('Cache-Control', 'no-store, private');
    }
}
