<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
     public function run(): void
     {
         DB::table('employees')->insert([
             ['employee_code' => 'EMP001', 'employee_name' => 'Atul Sachan'],
             ['employee_code' => 'EMP002', 'employee_name' => 'Ankit Sharma'],
         ]);
     }
}
