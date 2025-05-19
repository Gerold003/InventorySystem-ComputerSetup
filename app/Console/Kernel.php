<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Inventory;
use App\Models\Alert; // Ensure the Alert model exists in this namespace

class Kernel extends ConsoleKernel
{
    /**
     * Define your scheduled tasks
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Inventory::whereColumn('quantity', '<', 'low_stock_threshold')
                ->each(function ($inventory) {
                    Alert::create([
                        'product_id' => $inventory->product_id,
                        'type' => 'low_stock',
                        'message' => "Low stock for {$inventory->product->name}"
                    ]);
                });
        })->daily();
    
    // Daily inventory alerts check (at 8:00 AM)
    $schedule->command('inventory:check-low-stock')
             ->dailyAt('08:00')
             ->onOneServer();

        // Weekly database backups
        $schedule->command('backup:run --only-db')
                 ->weekly()->at('02:00');
    }

    /**
     * Register Artisan commands
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}