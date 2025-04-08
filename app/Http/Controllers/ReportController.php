<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shift, User, Pause, Snooze};
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    /**
    * Display the reports
    * @param string $id the text to do the magic
    * @return View
    */
    public function index(Request $request, $id = null) : View
    {
        // If a specific time range is requested, filter the shifts.
        $startDate = $request->input('start_date') ?? Shift::query()->min('the_date');
        $endDate = $request->input('end_date') ?? Shift::query()->max('the_date');

        // Fetch users with shifts in the given range.
        $users = Shift::query()
                        ->whereBetween('the_date', [$startDate, $endDate])
                        ->join('Users', 'Users.id', 'Shifts.user_id')
                        ->select('Users.*')->distinct()->get();

        // Computation of the working activity time.
        $activityTimes = [];

        // In the given range, fetch shifts of a given user or all shifts.
        $query = $id ? Shift::where('user_id', $id) : Shift::query();
        $shifts = $query->whereBetween('the_date', [$startDate, $endDate])
                        ->join('Users', 'Users.id', 'Shifts.user_id')
                        ->select('Shifts.*', 'Users.name')
                        ->orderByDesc('Shifts.created_at')->get();
        
        // Fetch the user shifts.
        foreach($shifts as $shift)
        {
            $pausequery = Pause::where('shift_id', $shift->id);
            $snoozequery = Snooze::where('shift_id', $shift->id);

            // Initialize total break time and total snooze time to zero.
            $pauseTimeInSec = 0;
            $snoozeTimeInSec = 0;

            if ($pausequery->exists())
            {   
                $pauses = $pausequery->get();
                foreach ($pauses as $pause)
                    $pauseTimeInSec += Carbon::parse($pause->pause_off)->secondsSinceMidnight() - Carbon::parse($pause->pause_on)->secondsSinceMidnight();
            }

            if ($snoozequery->exists())
            {
                $snoozes = $snoozequery->get();
                foreach ($snoozes as $snooze)
                    $snoozeTimeInSec += Carbon::parse($snooze->snooze_off)->secondsSinceMidnight() - Carbon::parse($snooze->snooze_on)->secondsSinceMidnight();
            }

            $activityTimeInSec = Carbon::parse($shift->time_out)->secondsSinceMidnight() - Carbon::parse($shift->time_in)->secondsSinceMidnight();
            
            //Check if the total break time is greater than 30 min.
            if ($pauseTimeInSec > 1800)
                $activityTimeInSec -= $pauseTimeInSec;

            // Check if the total snooze time is greater than 30 min.
            if ($snoozeTimeInSec > 1800)
                $activityTimeInSec -= $snoozeTimeInSec;
            
            array_push($activityTimes, [
                'user_id' => $shift->user_id,
                'user_name' => $shift->name,
                'shift_date' => $shift->the_date,
                'active_time' => gmdate('H:i:s', $activityTimeInSec) // $activityTimeInSec->format('H:i:s');
            ]);
        }

        $paginatedActivityTimes = $this->paginate($activityTimes);

        return view('report', compact(
                    'paginatedActivityTimes',
                    'activityTimes',
                    'startDate',
                    'endDate',
                    'users',
                    'id'
                ));
    }

    /**
     * Paginate an array of items
     * @return LengthAwarePaginator
     */
    private function paginate(array $items, int $perPage = 10, ?int $page = null, $options = []): LengthAwarePaginator
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = collect($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
