<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shift, User, Pause, Snooze};
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
    * Display the reports
    * @return View
    */
    public function show() : View
    {
        $users = User::get();
        $shifts = Shift::get();
        $activityTime = [];
        
        // Computation of the working activity
        foreach($users as $user)
        {
            foreach($shifts as $shift)
            {
                $pausequery = Pause::where('shift_id', $shift->id);
                $snoozequery = Snooze::where('shift_id', $shift->id);
                $snoozecheck = Snooze::where('shift_id', $shift->id)->exists();
                if ($pausequery->exists())
                {   
                    $pauses = $pausequery->get();
                    foreach ($pauses as $pause)
                        $pauseTime[$user->id] += ($pause->pause_off - $pause->pause_in);
                }

                if ($snoozequery->exists())
                {
                    $snoozes = $snoozequery->get();
                    foreach ($snoozes as $snooze)
                        $snoozeTime[$user->id] += ($snooze->snooze_off - $snooze->snooze_in);
                }

                $activityTime[$user->id] = $shift->timeOut - $shift->timeIn;
                $activityTime['date'] = $shift->date;

                if ($pauseTime[$user->id] > '00:30:00')
                    $activityTime[$user->id]= $activityTime[$user->id] - $pauseTime[$user->id];
                if ($snoozeTime[$user->id] > '00:30:00')
                    $activityTime[$user->id]= $activityTime[$user->id] - $snoozeTime[$user->id];
            }
        }
        return view('report', compact('activityTime'));
    }
}
