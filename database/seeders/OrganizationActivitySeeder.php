<?php

namespace Database\Seeders;

use App\Models\OrganizationActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationActivity::factory(10)->create();
    }
}
