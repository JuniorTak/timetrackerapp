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
        // Create an admin user.
        User::factory()->create(['is_admin' => true]);
        
        // Create three non-admin users.
        User::factory(3)->create();

        $arraydt = [];

        for ($i = 1; $i <= 31; $i++)
            array_push($arraydt, Carbon::createFromDate(2023, 8, $i));

        $non_admin_users = User::where('is_admin', false)->get();

        foreach($arraydt as $dt)
        {
            foreach($non_admin_users as $user)
            {
                Shift::factory()->create(['the_date' => $dt, 'user_id' => $user->id]);
            }
        }

        $total_shifts = Shift::all()->count();

        $j = 1;
        for ($i = 1; $i <= $total_shifts; $i++)
        {
            // Create a pause for the corresponding shift.
            Pause::factory()->create(['shift_id' => $i]);

            // Randomly create a snooze or not for the corresponding shift.
            if (rand(0,1) == 1) Snooze::factory()->create(['shift_id' => $i]);

            // Create extra pauses on a basis of specific series.
            if ($j == $i)
            {
                Pause::factory()->create([
                    'pause_on' => '14:00:59',
                    'pause_off' => Carbon::createFromTimeString('14:00:59')->addMinutes(rand(0,44))->addSeconds(rand(0,59)),
                    'shift_id' => $i
                ]);

                if (($i % 3) != 0) $j += 4;
                else $j++;
            }
        }
    }
}
