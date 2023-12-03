<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=0; $i<10000;$i++)
        {
            $category = [];
            array_push($category, [
                'id' => "1",
                'position' => "1",
            ]);      
        
            array_push($category, [
                'id' => "2",
                'position' => "2",
            ]);

            Product::create([
                'name'=>$this->secure_random_string(10),
                'product_code'=>rand(10,1000000),
                'category_ids'=>json_encode($category),
                'purchase_price'=>rand(10,1000),
                'selling_price'=> rand(10,1000),
                'unit_type'=>1,
                'unit_value'=>rand(10,1000),
                'brand'=>1,
                'discount_type'=>'amount',
                'discount'=>10,
                'tax'=>0,
                'quantity'=>20,
                'image'=>'def.png',
                'supplier_id'=>1
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
