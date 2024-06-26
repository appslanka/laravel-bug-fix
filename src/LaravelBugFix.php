<?php

namespace Appslanka\LaravelBugFix;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Http;
use Throwable;

class LaravelBugFix extends ExceptionHandler
{
    protected $config;

    public function __construct($app, $config)
    {
        parent::__construct($app); // Call parent constructor
        $this->config = $config;
    }

    public function report(Throwable $e)
    {
        // Custom reporting logic
        $payload = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];

        Http::withHeaders([
            'Authorization' => 'Bearer '. $this->config['api_key'],
        ])->post($this->config['api_url'], $payload);

        info("Calling Laravel bug fix reporting >>>");
        // Call the parent report method to ensure other reporting is done
        parent::report($e);
    }
}
