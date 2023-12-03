<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<5000;$i++)
        {
            Customer::create([
                'name'=>$this->secure_random_string(10),
                'mobile'=>rand(10,1000000),
            ]);
        }
    }
    function secure_random_string($length) {
        $rand_string = '';
        for($i = 0; $i < $length; $i++) {
            $number = random_int(0, 36);
            $character = base_convert($number, 10, 36);
            $rand_string .= $character;
        }
    
        return $rand_string;
    }
}
