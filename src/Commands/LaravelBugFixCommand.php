<?php

namespace Appslanka\LaravelBugFix\Commands;

use Illuminate\Console\Command;

class LaravelBugFixCommand extends Command
{
    public $signature = 'laravel-bug-fix';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
