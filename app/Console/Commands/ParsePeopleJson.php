<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\Person;
use Illuminate\Console\Command;

class ParsePeopleJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-people-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://zadania.aidevs.pl/data/people.json';
        $content = file_get_contents($url);
        $json = json_decode($content, associative: true);
        $chunks = collect($json)->chunk(100);
        foreach ($chunks as $chunk) {
            Person::query()->upsert($chunk->all(), ['imie', 'nazwisko']);
        }
    }
}
