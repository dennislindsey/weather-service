<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearWeatherCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:clear {zipCode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears any cached weather data for the given zip code';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $zipCode = $this->argument('zipCode');

        if (!preg_match('/\d{5}/', $zipCode)) {
            $this->error('Invalid zip code');

            return;
        }

        \Cache::delete("weather:{$zipCode}");

        $this->info("Weather data for {$zipCode} cleared");
    }
}
