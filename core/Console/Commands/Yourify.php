<?php

namespace Yourify\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class Yourify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yourify:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Yourify without issues, thanks!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Try{
            \DB::connection();
        }catch(Exception $e){
            $this->error('Unable to cennect to database.');
            $this->error('Please fill valid database credentials into .env and rerun this command.');
            return;
        }

        $this->comment('Attempting to Install Yourify - 0.1.1');

        if( !env('APP_KEY')){
            $this->info('Generating app key');
            \Artisan::call('key:generate');
        }else{
            $this->comment('App key exists -- skipping...');
        }

        $this->info('Migrating database...');

        \Artisan::call('migrate',['force' => true]);

        $this->comment('Database Migrated Successfully...');

        $this->info('Seeding DB data...');

        Artisan::call('db:seed', ['--force' => true]);

        $this->comment('Database Seeded Successfully...');
        $this->comment('Successfully Installed! You can now run the software');
    }
}
