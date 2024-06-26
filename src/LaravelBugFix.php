<?php

namespace Appslanka\LaravelBugFix;

use Throwable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
            'code' => $e->getCode(),
        ];

        // Include request details if enabled in configuration
        if ($this->config['include_request_details']) {
            $payload += [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'headers' => request()->headers->all(),
                'input' => $this->maskSensitiveFields(request()->all()),
                'user_agent' => request()->header('User-Agent'),
                'ip' => request()->ip(),
            ];
        }

        // Include server details if enabled in configuration
        if ($this->config['include_server_details']) {
            $payload += [
                'php_version' => phpversion(),
                'server_params' => $_SERVER,
                'get_params' => $_GET,
                'post_params' => $_POST,
                'cookies' => $_COOKIE,
            ];
        }

        // Include current user details if authenticated
        $user = Auth::user();
        if ($user) {
            // Determine the identifier field based on configuration
            $identifierField = $this->config['exception_user_identifier_field'] ?? 'id'; // Default to 'id'

            // Prepare user details to be included in payload
            $userDetails = collect([
                $identifierField => $user->{$identifierField},
                'name' => $user->name,
                'email' => $user->email
            ])->filter()->all(); // Remove null values

            // Add user details to payload if not empty
            if (!empty($userDetails)) {
                $payload['user'] = $userDetails;
            }
        }

        Http::withHeaders([
            'Authorization' => 'Bearer '.$this->config['api_key'],
        ])->post($this->config['api_url'], $payload);

        info('Calling Laravel bug fix reporting >>>');
        // Call the parent report method to ensure other reporting is done
        parent::report($e);
    }

    private function maskSensitiveFields(array $data)
    {
        foreach ($this->config['masked_fields'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->maskValue($data[$field]);
            }
        }

        return $data;
    }

    private function maskValue($value)
    {
        return str_repeat('****', strlen($value));
    }
}
