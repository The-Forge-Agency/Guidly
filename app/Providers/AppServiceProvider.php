<?php

namespace App\Providers;

use App\Models\Guide;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->ensureDemoGuide();
    }

    private function ensureDemoGuide(): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        try {
            Cache::remember('demo_guide_seeded', 86400, function () {
                if (Schema::hasTable('guides') && ! Guide::where('slug', 'bellecour')->exists()) {
                    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DemoGuideSeeder', '--force' => true]);
                }

                return true;
            });
        } catch (\Throwable $e) {
            Log::warning('Demo guide seeding failed: '.$e->getMessage());
        }
    }
}
