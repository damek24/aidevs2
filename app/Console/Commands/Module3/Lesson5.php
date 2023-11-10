<?php

namespace App\Console\Commands\Module3;

use App\Chat\GetName;
use App\Chat\MessageStructure;
use App\Console\OpenAICommand;
use App\Models\Person;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson5 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m3-l5';

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
        //before this run Artisan::call('app:parse-people-json');
        $key = text('api_key', default: config('openai.key') ?? '', required: true);
        $token = $this->getApp('people', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $question = $task['question'];
        \Laravel\Prompts\info($question);
        ['imie' => $name, 'nazwisko' => $last_name] = json_decode((new GetName($question))->sendMessage()->content, associative:  true);
        if($name === "Krysia") {
            $name = "Krystyna";
        }
        dump([$name, $last_name]);
        /** @var Person $model */
        $model = Person::query()->where('imie', $name)->where('nazwisko', $last_name)->first();
        $info = $model->context();
        $context = <<<CONTEXT
odpowiedz na pytanie na podstawie informacji

#context
$info
CONTEXT;
        $answer = (new MessageStructure($question, $context, false))->sendMessage()->content;
        \Laravel\Prompts\info($answer);
        $result = $this->sendAnswer($token, $answer);
        table(['code', 'msg', 'note'], [$result]);
        //dd($model);
    }
}
