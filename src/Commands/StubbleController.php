<?php

namespace App\Console\Commands;

use App\Console\Commands\TestCommand;
use Illuminate\Support\Facades\Validator;

class StubbleController extends TestCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stubble:Controller {stub} {name} {--e|extension=php} {--p|path=/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move a stub to the Controllers directory';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // handle the copy
        return $this->copyStub('Controllers');
    }
}
