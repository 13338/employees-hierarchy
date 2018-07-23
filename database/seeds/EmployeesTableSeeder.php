<?php

use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	for ($i=0; $i < 50001; $i++) { 
    		factory(App\Employee::class, 1)->create();
    	}
    }
}
