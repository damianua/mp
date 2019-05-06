<?php

use App\Models\ProductProperty;
use Illuminate\Database\Seeder;

class ProductPropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    /**
	     * @var \App\Services\Stateless\ProductPropertyService $service
	     */
    	$service = app('ProductPropertyService');
    	for($i = 1; $i <= 50; $i++){
    		$service->createProductProperty([
			    'name' => 'Свойство '.$i,
		    ]);
	    }
    }
}
