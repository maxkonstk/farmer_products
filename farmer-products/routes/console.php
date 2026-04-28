<?php

use App\Services\HealthCheckService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:smoke-check {--json : Output readiness report as JSON}', function () {
    $report = app(HealthCheckService::class)->readinessReport();

    if ($this->option('json')) {
        $this->line(json_encode($report, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return $report['status'] === 'fail' ? SymfonyCommand::FAILURE : SymfonyCommand::SUCCESS;
    }

    $rows = collect($report['checks'] ?? [])
        ->map(fn (array $check, string $name): array => [
            $name,
            $check['status'] ?? 'unknown',
            $check['message'] ?? '',
            isset($check['meta']) ? json_encode($check['meta'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '',
        ])
        ->values()
        ->all();

    $this->info("Readiness status: {$report['status']}");
    $this->table(['Check', 'Status', 'Message', 'Meta'], $rows);

    if ($report['status'] === 'fail') {
        $this->error('Readiness failed.');

        return SymfonyCommand::FAILURE;
    }

    if ($report['status'] === 'warning') {
        $this->warn('Readiness passed with warnings.');

        return SymfonyCommand::SUCCESS;
    }

    $this->info('Readiness passed.');

    return SymfonyCommand::SUCCESS;
})->purpose('Run deploy smoke-checks for database, cache, assets, storage and queue');
