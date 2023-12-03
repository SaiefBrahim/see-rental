<?php

namespace App\CPU;

class ProductLogic
{

    public static function format_export_products($products)
    {
        $storage = [];
        foreach ($products as $item) {
            $category_id = 0;
            $sub_category_id = 0;
            foreach (json_decode($item->category_ids, true) as $category) {
                if ($category['position'] == 1) {
                    $category_id = $category['id'];
                } else if ($category['position'] == 2) {
                    $sub_category_id = $category['id'];
                }
            }
            array_push($storage, [
                'name' => $item['name'],
                'product_code' => $item['product_code'],
                'unit_type' => $item['unit_type'],
                'unit_value' => $item['unit_value'],
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'brand' => $item['brand'],
                'purchase_price' => $item['purchase_price'],
                'selling_price' => $item['selling_price'],
                'discount_type' => $item['discount_type'],
                'discount' => $item['discount'],
                'tax' => $item['tax'],
                'quantity' => $item['quantity'],
                'supplier_id' => $item['supplier_id'],
            ]);
        }
        return $storage;
    }
}
