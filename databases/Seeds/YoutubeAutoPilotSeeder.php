<?php

namespace ConfrariaWeb\User\Databases\Seeds;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class YoutubeAutoPilotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateUserTables();
    }

    private function truncateUserTables()
    {
        //
    }
}
