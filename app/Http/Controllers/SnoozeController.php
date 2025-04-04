<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shift, Snooze};
use Carbon\Carbon;

class SnoozeController extends Controller
{
    /**
     * Create a snooze.
     */
    public function create(Request $request, string $shiftId)
    {
        // Ensure the shift exists
        $shift = Shift::find($shiftId);
        if (!$shift) {
            return response()->json(['error' => 'Shift not found'], 404);
        }
        
        // Handle nap start.
        $snooze = Snooze::create([
            'shift_id' => $shiftId,
            'snooze_on' => Carbon::now()->toTimeString(),
            'snooze_off' => null
        ]);

        return response()->json([
            'message' => 'Snooze started',
            'snooze' => $snooze
        ]);
    }

    /**
     * Update a snooze.
     */
    public function update(Request $request, string $id)
    {
        // Handle nap end.
        $snooze = Snooze::findOrFail($id);
        $snooze->update(['snooze_off' => Carbon::now()->toTimeString()]);

        return response()->json([
            'message' => 'Snooze ended',
            'snooze' => $snooze
        ]);
    }
}
