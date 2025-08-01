<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\OrganizationActivity;
use App\Models\OrganizationPhone;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->call(
          [
              ActivitySeeder::class,
              BuildingSeeder::class,
              OrganizationSeeder::class,
              OrganizationPhoneSeeder::class,
              OrganizationActivitySeeder::class,
          ]
      );
    }
}
