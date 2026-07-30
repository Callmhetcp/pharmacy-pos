<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicine;
use App\Models\Notification;
use Carbon\Carbon;

class CheckMedicineExpiry extends Command
{
    protected $signature = 'medicine:check-expiry';

    protected $description = 'Check medicines nearing expiry and create notifications';

    public function handle()
    {
        $today = Carbon::today();

        $medicines = Medicine::whereNotNull('expiry_date')->get();

        foreach ($medicines as $medicine) {

            $days = $today->diffInDays(
                Carbon::parse($medicine->expiry_date),
                false
            );

            if ($days <= 30) {

                $title = 'Medicine Expiring Soon';
                $type  = 'warning';

                if ($days <= 0) {
                    $title = 'Medicine Expired';
                    $type  = 'danger';
                }

                Notification::updateOrCreate(
                    [
                        'medicine_id' => $medicine->id,
                        'title'       => $title,
                    ],
                    [
                        'message' => $medicine->name .
                            ' expires on ' .
                            Carbon::parse($medicine->expiry_date)->format('d M Y'),

                        'type' => $type,

                        'is_read' => false,

                        'user_id' => null,
                    ]
                );

            } else {

                Notification::where('medicine_id', $medicine->id)
                    ->whereIn('title', [
                        'Medicine Expiring Soon',
                        'Medicine Expired'
                    ])
                    ->delete();

            }

        }

        $this->info('Expiry notifications checked successfully.');
    }
}