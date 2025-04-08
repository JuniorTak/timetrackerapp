<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{ Team, User, Shift, Pause, Snooze };
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a specific admin user.
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);
        
        // Create three random non-admin users.
        User::factory(3)->create();

        $arraydt = [];

        for ($i = 1; $i <= 30; $i++) {
            $arraydt[] = Carbon::today()->subDays($i)->toDateString();
            // Array of last 30 days from 1 day ago.
            $arraydt = array_reverse($arraydt);
        }

        $non_admin_users = User::where('is_admin', false)->get();

        foreach($arraydt as $dt)
        {
            foreach($non_admin_users as $user)
            {
                // Use the factory to make a Shift model.
                $shift = Shift::factory()->make(['the_date' => $dt, 'user_id' => $user->id]);

                // Combine date + time for created_at and updated_at
                $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $dt . ' ' . $shift->time_in);
                $updatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $dt . ' ' . $shift->time_out);

                // Save manually with timestamps
                $shift->created_at = $createdAt;
                $shift->updated_at = $updatedAt;
                $shift->save();
            }
        }

        $total_shifts = Shift::all()->count();

        $j = 1;
        for ($i = 1; $i <= $total_shifts; $i++)
        {
            // Get date of the shift.
            $the_shift = Shift::whereId($i)->select('the_date')->first();

            // Create a pause with realistic timestamps for the corresponding shift.
            $pause = Pause::factory()->make(['shift_id' => $i]);
            $pauseCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $the_shift->the_date . ' ' . $pause->pause_on);
            $pauseUpdatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $the_shift->the_date . ' ' . $pause->pause_off);
            $pause->created_at = $pauseCreatedAt;
            $pause->updated_at = $pauseUpdatedAt;
            $pause->save();

            // Randomly create or not a snooze with realistic timestamps for the corresponding shift.
            if (rand(0,1) == 1) {
                $snooze = Snooze::factory()->make(['shift_id' => $i]);
                $snoozeCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $the_shift->the_date . ' ' . $snooze->snooze_on);
                $snoozeUpdatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $the_shift->the_date . ' ' . $snooze->snooze_off);
                $snooze->created_at = $snoozeCreatedAt;
                $snooze->updated_at = $snoozeUpdatedAt;
                $snooze->save();
            }

            // Create extra pauses on a basis of specific series.
            if ($j == $i)
            {
                $break = Pause::factory()->make([
                    'pause_on' => '14:00:59',
                    'pause_off' => Carbon::createFromTimeString('14:00:59')->addMinutes(rand(0,44))->addSeconds(rand(0,59))->format('H:i:s'),
                    'shift_id' => $i
                ]);
                $breakCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $the_shift->the_date . ' ' . $break->pause_on);
                $breakUpdatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $the_shift->the_date . ' ' . $break->pause_off);
                $break->created_at = $breakCreatedAt;
                $break->updated_at = $breakUpdatedAt;
                $break->save();

                if (($i % 3) != 0) $j += 4;
                else $j++;
            }
        }
    }
}
