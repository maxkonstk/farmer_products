<?php

namespace Tests\Feature;

use App\Services\HealthCheckService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationsHealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_and_ready_endpoints_return_operational_json(): void
    {
        $healthResponse = $this->get(route('ops.health'));

        $healthResponse->assertOk();
        $healthResponse->assertHeader('Cache-Control', 'no-store, private');
        $healthResponse->assertJsonPath('status', 'ok');
        $healthResponse->assertJsonPath('application', config('app.name'));

        $readyResponse = $this->get(route('ops.ready'));

        $readyResponse->assertOk();
        $readyResponse->assertHeader('Cache-Control', 'no-store, private');
        $readyResponse->assertJsonPath('status', 'ok');
        $readyResponse->assertJsonStructure([
            'status',
            'checked_at',
            'checks' => [
                'app_key',
                'database',
                'cache',
                'assets',
                'storage',
                'queue',
            ],
        ]);
    }

    public function test_ready_endpoint_returns_503_when_readiness_fails(): void
    {
        app()->instance(HealthCheckService::class, new class extends HealthCheckService
        {
            public function readinessReport(): array
            {
                return [
                    'status' => 'fail',
                    'checked_at' => now()->toIso8601String(),
                    'application' => config('app.name'),
                    'environment' => 'testing',
                    'checks' => [
                        'database' => [
                            'status' => 'fail',
                            'message' => 'База данных недоступна.',
                        ],
                    ],
                ];
            }
        });

        $response = $this->get(route('ops.ready'));

        $response->assertStatus(503);
        $response->assertJsonPath('status', 'fail');
    }

    public function test_smoke_check_command_reports_successful_readiness(): void
    {
        $this->artisan('app:smoke-check --json')
            ->expectsOutputToContain('"status":"ok"')
            ->assertExitCode(0);
    }

    public function test_smoke_check_command_fails_when_service_reports_failure(): void
    {
        app()->instance(HealthCheckService::class, new class extends HealthCheckService
        {
            public function readinessReport(): array
            {
                return [
                    'status' => 'fail',
                    'checked_at' => now()->toIso8601String(),
                    'application' => config('app.name'),
                    'environment' => 'testing',
                    'checks' => [
                        'assets' => [
                            'status' => 'fail',
                            'message' => 'Не найден public/build/manifest.json.',
                        ],
                    ],
                ];
            }
        });

        $this->artisan('app:smoke-check')
            ->expectsOutputToContain('Readiness failed.')
            ->assertExitCode(1);
    }
}
