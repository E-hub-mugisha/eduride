<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Trip;
use App\Models\User;
use App\Mail\TripNotStartedMail;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {

    $trips = Trip::where('status', 'pending')
        ->whereTime('start_time', '<', now())
        ->get();

    foreach ($trips as $trip) {

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)
                ->send(new TripNotStartedMail($trip));
        }
    }

})->everyFiveMinutes();