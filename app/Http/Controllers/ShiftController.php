<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shift, User};
use Illuminate\View\View;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
    * Show all shifts.
    * @param string $id the text to do the magic
    * @return View
    **/
    public function index($id = null): View
    {
        $users = Shift::query()->join('Users', 'Users.id', 'Shifts.user_id')->select('Users.*')->distinct()->get();

        $query = $id ? User::whereId($id)->firstOrFail()->shifts() : Shift::query();
        $shifts = $query->paginate(10);

        return view('index', compact('shifts', 'users', 'id'));
    }

    /**
    * Show the shifts for a given user.
    * @param string $id the text to do the magic
    * @return dd
    **/
    public function showUser(string $id)
    {
        // Retrieve all the shifts for the given user
        $shifts = Shift::where('user_id', $id)->get();
        $user = User::find($id);
        // return view('index', ['shift' => $shifts, 'user'=> $user]);
        dd(['shift' => $shifts, 'username' => $user->name]);
    }
    
    /**
     * Create a shift.
     */
    public function create(Request $request)
    {
        $userId = auth()->id();
        $today = Carbon::today()->toDateString();

        // Check if the user already has a shift for today
        $existingShift = Shift::where('user_id', $userId)->whereDate('the_date', $today)->first();

        if ($existingShift) {
            return response()->json(['error' => 'You have already clocked in today.'], 400);
        }

        // Start shift.
        $shift = Shift::create([
            'user_id' => $userId,
            'the_date' => $today,
            'time_in' => Carbon::now()->toTimeString(),
            'time_out' => null
        ]);

        return response()->json([
            'message' => 'Time in recorded',
            'shift' => $shift,
        ]);
    }
    
    /**
     * Show a shift.
     */
    public function show(string $id)
    {
        // Retrieve the shift
        $shift = Shift::find($id);
        dd($shift);
    }
    
    /**
     * Edit a shift.
     */
    public function edit(string $id)
    {
        // Retrieve the shift
        $shift = Shift::find($id);
        dd(['shift' => $shift]);
    }

    /**
     * Update a shift.
     */
    public function update(Request $request, string $id)
    {
        // End shift.
        $shift = Shift::findOrFail($id);
        $shift->update(['time_out' => Carbon::now()->toTimeString()]);

        return response()->json([
            'message' => 'Time out recorded',
            'shift' => $shift
        ]);
    }

}
