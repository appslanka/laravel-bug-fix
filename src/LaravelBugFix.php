<?php

namespace Appslanka\LaravelBugFix;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Http;

class LaravelBugFix extends ExceptionHandler
{
    protected $config;

    public function __construct($app, $config)
    {
        parent::__construct($app); // Call parent constructor
        $this->config = $config;
    }

    public function report(Exception $exception)
    {
        // Custom reporting logic
        $payload = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];

        Http::withHeaders([
            'Authorization' => 'Bearer '.$this->config['api_key'],
        ])->post($this->config['api_url'], $payload);

        // Call the parent report method to ensure other reporting is done
        parent::report($exception);
    }
}
