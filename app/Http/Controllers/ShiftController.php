<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shift, User};
use Illuminate\View\View;

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
        // For shift start
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
        // For break on/off, snooze on/off and shift end
    }

}
