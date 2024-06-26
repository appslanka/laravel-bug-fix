<?php

namespace Appslanka\LaravelBugFix;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Http;
use Throwable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

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
            'code' => $e->getCode(), // Exception or error code
            'previous' => $e->getPrevious(), // Previous exception if available
            'url' => request()->fullUrl(), // Full URL of the request
            'method' => request()->method(), // HTTP method of the request
            'headers' => request()->headers->all(), // All request headers
            'input' => request()->all(), // All request input data
            'user_agent' => request()->header('User-Agent'), // User agent string
            'ip' => request()->ip(), // Client IP address
            'request_time' => now()->toDateTimeString(), // Current timestamp
            'php_version' => phpversion(), // PHP version running the application
            'server_params' => $_SERVER, // All server parameters
            'get_params' => $_GET, // All GET parameters
            'post_params' => $_POST, // All POST parameters
            'cookies' => $_COOKIE, // All cookies
            'session' => session()->all(), // All session data (if using session)
        ];

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['api_key'],
        ])->post($this->config['api_url'], $payload);

        info('Calling Laravel bug fix reporting >>>');
        // Call the parent report method to ensure other reporting is done
        parent::report($e);
    }
}
