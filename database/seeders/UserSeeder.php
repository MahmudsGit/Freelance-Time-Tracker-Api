<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    User::factory()->count(2)->create()->each(function ($user) {
        $clients = $user->clients()->saveMany(\App\Models\Client::factory()->count(2)->make());

        foreach ($clients as $client) {
            $projects = $client->projects()->saveMany(\App\Models\Project::factory()->count(2)->make());

            foreach ($projects as $project) {
                $project->timeLogs()->saveMany(\App\Models\TimeLog::factory()->count(10)->make());
            }
        }
    });
}
}
