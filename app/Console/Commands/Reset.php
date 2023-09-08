<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Reset extends Command
{
    protected $signature = 'app:reset';

    protected $description = 'Reset the search and cache';

    public function handle()
    {
        Artisan::call('scout:flush "App\\\Models\\\Band"');
        Artisan::call('scout:flush "App\\\Models\\\Album"');
        Artisan::call('scout:flush "App\\\Models\\\Song"');
        $this->info('Flushed search');
        Artisan::call('scout:import "App\\\Models\\\Band"');
        Artisan::call('scout:import "App\\\Models\\\Album"');
        Artisan::call('scout:import "App\\\Models\\\Song"');
        $this->info('Imported search');
        Artisan::call('cache:clear');
        $this->info('Cleared cache');
    }
}
