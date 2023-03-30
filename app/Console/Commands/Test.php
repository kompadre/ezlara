<?php

namespace App\Console\Commands;

use App\Models\Tienda;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //
        $tienda = new Tienda();
        $tienda->setAttribute('nombre', 'H&S');
        $tienda->save();

        $tienda = new Tienda();
        $tienda->setAttribute('nombre', 'FNAC');
        $tienda->save();


    }
}
