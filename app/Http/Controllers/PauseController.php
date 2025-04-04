<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shift, Pause};
use Carbon\Carbon;

class PauseController extends Controller
{
    /**
     * Create a pause.
     */
    public function create(Request $request, string $shiftId)
    {
        // Ensure the shift exists
        $shift = Shift::find($shiftId);
        if (!$shift) {
            return response()->json(['error' => 'Shift not found'], 404);
        }

        // Handle break start.
        $pause = Pause::create([
            'shift_id' => $shiftId,
            'pause_on' => Carbon::now()->toTimeString(),
            'pause_off' => null
        ]);

        return response()->json([
            'message' => 'Pause started',
            'pause' => $pause
        ]);
    }

    /**
     * Update a pause.
     */
    public function update(Request $request, string $id)
    {
        // Handle break end.
        $pause = Pause::findOrFail($id);
        $pause->update(['pause_off' => Carbon::now()->toTimeString()]);

        return response()->json([
            'message' => 'Pause ended',
            'pause' => $pause
        ]);
    }
}
