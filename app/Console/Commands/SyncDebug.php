<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncDebug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get information via the synchronization domain about the current state of the synchronization and output it here for debugging purposes.
    }
}
