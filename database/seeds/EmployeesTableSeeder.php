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
        for ($i=0; $i < 2000; $i++) { 
        	factory(App\Employee::class, 1)->states('directors')->create();
        }
        for ($i=0; $i < 50000; $i++) { 
        	factory(App\Employee::class, 1)->states('employees')->create();
        }
    }
}
