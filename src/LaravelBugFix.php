<?php

namespace Appslanka\LaravelBugFix;

use Exception;
use Illuminate\Support\Facades\Http;

class LaravelBugFix {
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }


    public function report(Exception $exception)
    {
        $payload = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['api_key'],
        ])->post($this->config['api_url'], $payload);
    }
}
