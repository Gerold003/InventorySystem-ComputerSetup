<?php

namespace App\Jobs;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert;
use Illuminate\Contracts\Mail\Mailable;

class SendAlertNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Alert::unread()
            ->with('product')
            ->where('created_at', '>=', now()->subDay())
            ->chunk(100, function ($alerts) {
                foreach ($alerts as $alert) {
                    // Send to inventory managers
                    Mail::to(config('inventory.alerts_email'))
                        ->send(new LowStockAlert($alert));
                    
                    $alert->update(['is_read' => true]);
                }
            });
    }
}