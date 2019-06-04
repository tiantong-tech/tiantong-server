<?php

use App\Facades\Series;
use Illuminate\Database\Seeder;

class SeriesSeeder extends Seeder
{
	public function run()
	{
		Series::create('root_id', 0, 99);
		Series::create('admin_id', 100, 999);
		Series::create('sale_id', 10000, 19999);
	}
}
