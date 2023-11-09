<?php

namespace App\Console\Commands;

use App\Data\EmbeddingModel;
use App\Helpers\QdrantClient;
use App\Models\Link;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\warning;

class ParseJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-json';

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
//        Link::query()->delete();
//        $url = 'https://unknow.news/archiwum.json';
//        $content = file_get_contents($url);
//        $json = json_decode($content, associative: true);
//        $chunks = collect($json)->chunk(100);
//        foreach ($chunks as $chunk) {
//            Link::query()->upsert($chunk->all(), 'uuid');
//        }
        \Laravel\Prompts\info('creating embeddings');
        /** @var Collection<int, Link> $models */
        $models = Link::query()->get();
        $bar = $this->output->createProgressBar($models->count());
        $client = new QdrantClient('openai_devs');
        foreach ($models as $model)
        {
            $point = null;
            while ($point === null)
            {
                try {
                    $point = EmbeddingModel::fromInput($model->info, $model->uuid);
                }catch (\Throwable $e) {
                    warning($e->getMessage());
                }

            }
            $client->upsert($point);
            $bar->advance();
        }
    }
}
