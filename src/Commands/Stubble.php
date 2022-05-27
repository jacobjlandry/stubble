<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class Stubble extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stubble';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stub your app';

    protected $stubsDirectory = __DIR__ . "/../stubs/";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // display help
    }

    protected function validateStub($stub)
    {
        // remove any extensions and add .stub
        $stub = preg_replace("/\..*$/", "", $stub) . ".stub";
        
        // make sure the stub exists
        if(!file_exists($this->stubsDirectory . $stub)) {
            $this->error("Stub {$stub} does not exist!");
            return 0;
        }

        return 1;
    }

    protected function getNameWithExtension() 
    {
        // grab extension and provide default value
        $extension = $this->option('extension');
        if(empty($extension)) {
            $extension = 'php';
        }
        // grab file name to create (remove extension)
        $name = preg_replace("/\..*$/", "", $this->argument('name')) . "." . $extension;

        return $name;
    }

    protected function copyStub($type)
    {
        // grab stub name
        $stub = $this->argument('stub');
        $validated = $this->validateStub($stub);
        if(!$validated) {
            return $validated;
        }

        // get name with extension
        $name = $this->getNameWithExtension();

        // publish stub
        $this->moveFile($type, $stub, $name);
    }

    public function path($type)
    {
        // get path
        $path = $this->option('path', '/');

        // trim trailing and leading / to make sure we don't duplicate or leave them out
        $path = preg_replace("/^\//", "", $path);
        $path = preg_replace("/\/$/", "", $path);

        // add the trailing slash back if we're not empty
        if(!empty($path)) {
            $path .= "/";
        }

        // get path. /app/Models/$path, /app/Http/Controllers/$path, /app/Providers/$path, /resources/views/$path/
        switch($type) {
            default:
            case 'Models':
                return "/app/{$type}/{$path}";
                break;

            case 'Providers':
            case 'Controllers':
                return "/app/Http/{$type}/{$path}";
                break;

            case 'Views':
                return "/resources/views/{$path}";
                break;
        }
    }

    protected function moveFile($type, $stub, $name, $path)
    {
        $this->info("Copying {$stub} to {$type}/{$name}");
        $path = $this->path($type);   
        
        // copy $stub => $path/$name
        Storage::copy($stub, "$path/{$name}");
        $this->info("{$type} {$name} created!");
    }
}
