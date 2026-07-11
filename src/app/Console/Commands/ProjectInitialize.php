<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectInitialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project Initialization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:fresh', [
            '--force' => true,
        ]);

        // Setup Filament Shield (creates super_admin role in Spatie)
        $this->call('shield:setup', [
            '--fresh' => true,
        ]);

        $this->call('shield:generate', [
            '--all'   => true,
            '--panel' => 'admin',
        ]);

        $this->call('db:seed', [
            '--force' => true,
        ]);

        $this->call('storage:link', ['--force' => true]);
        $this->call('filament:optimize-clear');
        $this->call('optimize:clear');
    }
}
