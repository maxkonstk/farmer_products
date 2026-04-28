<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class HealthCheckService
{
    /**
     * @return array<string, mixed>
     */
    public function livenessReport(): array
    {
        return [
            'status' => 'ok',
            'checked_at' => now()->toIso8601String(),
            'application' => config('app.name'),
            'environment' => app()->environment(),
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function readinessReport(): array
    {
        $checks = [
            'app_key' => $this->checkAppKey(),
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'assets' => $this->checkAssets(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];

        $status = collect($checks)->contains(fn (array $check): bool => $check['status'] === 'fail')
            ? 'fail'
            : (collect($checks)->contains(fn (array $check): bool => $check['status'] === 'warning') ? 'warning' : 'ok');

        return [
            'status' => $status,
            'checked_at' => now()->toIso8601String(),
            'application' => config('app.name'),
            'environment' => app()->environment(),
            'checks' => $checks,
        ];
    }

    /**
     * @return array{status: string, message: string, meta?: array<string, mixed>}
     */
    private function checkAppKey(): array
    {
        $configured = filled(config('app.key'));

        return [
            'status' => $configured ? 'ok' : 'fail',
            'message' => $configured ? 'APP_KEY настроен.' : 'APP_KEY отсутствует.',
        ];
    }

    /**
     * @return array{status: string, message: string, meta?: array<string, mixed>}
     */
    private function checkDatabase(): array
    {
        try {
            DB::select('select 1 as connection_ok');

            return [
                'status' => 'ok',
                'message' => 'База данных доступна.',
                'meta' => [
                    'connection' => config('database.default'),
                ],
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'fail',
                'message' => 'База данных недоступна.',
                'meta' => [
                    'connection' => config('database.default'),
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    /**
     * @return array{status: string, message: string, meta?: array<string, mixed>}
     */
    private function checkCache(): array
    {
        $cacheKey = 'health-check:'.Str::uuid();

        try {
            Cache::put($cacheKey, 'ok', now()->addMinute());
            $value = Cache::get($cacheKey);
            Cache::forget($cacheKey);

            if ($value !== 'ok') {
                return [
                    'status' => 'fail',
                    'message' => 'Кэш не вернул тестовое значение.',
                    'meta' => [
                        'store' => config('cache.default'),
                    ],
                ];
            }

            return [
                'status' => 'ok',
                'message' => 'Кэш работает.',
                'meta' => [
                    'store' => config('cache.default'),
                ],
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'fail',
                'message' => 'Кэш недоступен.',
                'meta' => [
                    'store' => config('cache.default'),
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    /**
     * @return array{status: string, message: string, meta?: array<string, mixed>}
     */
    private function checkAssets(): array
    {
        $manifestPath = public_path('build/manifest.json');
        $exists = is_file($manifestPath);

        return [
            'status' => $exists ? 'ok' : 'fail',
            'message' => $exists ? 'Frontend assets собраны.' : 'Не найден public/build/manifest.json.',
            'meta' => [
                'manifest_path' => $manifestPath,
            ],
        ];
    }

    /**
     * @return array{status: string, message: string, meta?: array<string, mixed>}
     */
    private function checkStorage(): array
    {
        $publicStoragePath = storage_path('app/public');
        $publicLinkPath = public_path('storage');
        $directoryExists = is_dir($publicStoragePath);
        $writable = $directoryExists && is_writable($publicStoragePath);
        $linked = is_link($publicLinkPath) || is_dir($publicLinkPath);

        $status = $directoryExists && $writable && $linked ? 'ok' : 'fail';

        return [
            'status' => $status,
            'message' => $status === 'ok'
                ? 'Публичное storage готово к работе.'
                : 'Проверь storage/app/public и public/storage.',
            'meta' => [
                'storage_path' => $publicStoragePath,
                'public_link_path' => $publicLinkPath,
                'directory_exists' => $directoryExists,
                'writable' => $writable,
                'linked' => $linked,
            ],
        ];
    }

    /**
     * @return array{status: string, message: string, meta?: array<string, mixed>}
     */
    private function checkQueue(): array
    {
        $connection = (string) config('queue.default', 'sync');

        if ($connection !== 'database') {
            return [
                'status' => 'ok',
                'message' => "Очередь использует драйвер {$connection}.",
                'meta' => [
                    'connection' => $connection,
                ],
            ];
        }

        if (! Schema::hasTable('jobs') || ! Schema::hasTable('failed_jobs')) {
            return [
                'status' => 'fail',
                'message' => 'Для database queue отсутствуют таблицы jobs/failed_jobs.',
                'meta' => [
                    'connection' => $connection,
                    'jobs_table' => Schema::hasTable('jobs'),
                    'failed_jobs_table' => Schema::hasTable('failed_jobs'),
                ],
            ];
        }

        try {
            $pendingJobs = (int) DB::table('jobs')->count();
            $failedJobs = (int) DB::table('failed_jobs')->count();
            $status = $failedJobs > 0 ? 'warning' : 'ok';

            return [
                'status' => $status,
                'message' => $failedJobs > 0
                    ? 'Есть failed jobs, очередь требует внимания.'
                    : 'Очередь готова к работе.',
                'meta' => [
                    'connection' => $connection,
                    'pending_jobs' => $pendingJobs,
                    'failed_jobs' => $failedJobs,
                ],
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'fail',
                'message' => 'Не удалось прочитать состояние очереди.',
                'meta' => [
                    'connection' => $connection,
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }
}
