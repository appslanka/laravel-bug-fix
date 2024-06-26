<?php

namespace Appslanka\LaravelBugFix;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
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
        if (! $this->shouldReport($e) || ! $this->config['enabled']) {
            info('LBF NOT reporting >>> '.now());

            return parent::report($e);
        }

        // Generate a unique key for this error report
        $errorKey = md5($e->getMessage().$e->getFile().$e->getLine());
        if (Cache::has($errorKey)) {
            info('LBF NOT reporting because cached key >>> '.now());

            return;
        }

        // Cache this error with a timeout period (e.g., 1 hour)
        Cache::put($errorKey, true, now()->addMinutes(5));

        info('LBF reporting >>> '.now());

        $payload = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'code' => $e->getCode(),
            'error_key' => $errorKey,
        ];

        // Include request details if enabled in configuration
        if ($this->config['include_request_details']) {
            $payload += [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'headers' => request()->headers->all(),
                'parameters' => $this->maskSensitiveFields(request()->all()),
                'user_agent' => request()->header('User-Agent'),
                'ip' => request()->ip(),
                'php_version' => phpversion(),

            ];
        }

        // Include cookies details if enabled in configuration
        if ($this->config['include_cookies']) {
            $payload += [
                'cookies' => $_COOKIE,
            ];
        }

        // Include session details if enabled in configuration
        if ($this->config['include_sessions']) {
            $payload += [
                'sessions' => Request::hasSession() ? Session::all() : [],
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
                'email' => $user->email,
            ])->filter()->all(); // Remove null values

            // Add user details to payload if not empty
            if (! empty($userDetails)) {
                $payload['user'] = $userDetails;
            }
        }

        Http::withHeaders([
            'Authorization' => 'Bearer '.$this->config['api_key'],
        ])
            ->timeout(10)
            ->post($this->config['api_url'], $payload);

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
