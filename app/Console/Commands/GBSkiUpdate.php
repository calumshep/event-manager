<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GBSkiUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gbski:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the stored data from GBSki.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('Starting import...');

        $bar = $this->output->createProgressBar(2);

        $bar->start();
        $this->info('  Getting data from gbski.com');

        $data = App::environment('local') // ignore SSL when testing locally
            ? Http::withOptions(['verify' => false])->get('https://www.gbski.com/competitors.php?csv&season=2024')
            : Http::get('https://www.gbski.com/competitors.php?csv&season=2024');

        $bar->advance();
        $this->info('  Saving data to local file');

        if (Storage::put('competitors.csv', $data->body())) {
            $bar->advance();
            $this->line('  Import complete.');
        } else {
            $this->error('  Import could not complete.');
        }
    }
}
