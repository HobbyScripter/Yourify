<?php

namespace Yourify\Console\Commands;

use Illuminate\Console\Command;

class Yourify_update extends Command
{
    protected $signature = 'yourify:update';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
    }
}
