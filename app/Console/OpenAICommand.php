<?php

namespace App\Console;


use GuzzleHttp\Client;
use Illuminate\Console\Command;

class OpenAICommand extends Command
{
    protected string $base_url = 'https://zadania.aidevs.pl';

    protected function getTask(string $token): array
    {
        $client = new Client();
        $response = $client->get($this->base_url . '/task/' . $token);
        return json_decode($response->getBody()->getContents(), associative: true, flags: JSON_THROW_ON_ERROR);
    }

    protected function sendTaskMessage(string $field, string $message, string $token)
    {
        $client = new Client();
        $response = $client->post($this->base_url . '/task/' . $token, [
            'form_params' => [$field => $message],
            'timeout' => 20,
        ]);
        return json_decode($response->getBody()->getContents(), associative: true, flags: JSON_THROW_ON_ERROR);
    }

    protected function getApp(string $appName, string $key): array
    {
        $client = new Client();
        $response = $client->post($this->base_url . '/token/' . $appName, [
            'json' => ['apikey' => $key]
        ]);
        return json_decode($response->getBody()->getContents(), associative: true, flags: JSON_THROW_ON_ERROR);
    }

    protected function sendAnswer(string  $token, string|array|object $answer): array
    {
        $client = new Client();
        $response = $client->post($this->base_url . '/answer/' . $token, [
            'json' => ['answer' => $answer]
        ]);
        return json_decode($response->getBody()->getContents(), associative: true, flags: JSON_THROW_ON_ERROR);
    }
}
