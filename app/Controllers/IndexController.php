<?php

namespace App\Controllers;

use App\Core\View;

class IndexController
{
    public function index()
    {
        $products = \DB::table('products')
            ->select([
                'products.*',
                \DB::raw('SUM(store.quantity) as qty'),
                \DB::raw('GROUP_CONCAT(DISTINCT warehouses.name ORDER BY warehouses.name ASC SEPARATOR ", ") as warehouses'),
            ])
            ->join('store', 'store.product_id', '=', 'products.id')
            ->join('warehouses', 'store.warehouse_id', '=', 'warehouses.id')
            ->where('store.quantity', '>', 0)
            ->groupBy('products.id')
            ->get();

        return View::render('index', [
            'products' => $products
        ]);
    }

    public function loadCSV()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if (!is_dir(__DIR__ . ' /../../resources/tmp/')) {
                mkdir(__DIR__ . ' /../../resources/tmp/');
            }
            $target_file = __DIR__ . ' /../../resources/tmp/' . basename($_FILES["file"]["name"]);

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $list = array_map('str_getcsv', file($target_file));
                foreach ($list as $row) {
                    $product = \DB::table('products')
                        ->where('name', '=', $row[0])
                        ->first();
                    if (!$product) {
                        $product_id = \DB::table('products')
                            ->insert([
                                'name' => $row[0]
                            ]);
                    } else {
                        $product_id = $product->id;
                    }

                    $warehouse = \DB::table('warehouses')
                        ->where('name', '=', $row[2])
                        ->first();
                    if (!$warehouse) {
                        $warehouse_id = \DB::table('warehouses')
                            ->insert([
                                'name' => $row[2]
                            ]);
                    } else {
                        $warehouse_id = $warehouse->id;
                    }
                    $store = \DB::table('store')
                        ->where('product_id', '=', $product_id)
                        ->where('warehouse_id', '=', $warehouse_id)
                        ->first();
                    if ($store) {
                        \DB::table('store')
                            ->where('id', '=', $store->id)
                            ->update([
                                'quantity' => $store->quantity + $row[1]
                            ]);
                    } else {
                        \DB::table('store')
                            ->insert([
                                'product_id'   => $product_id,
                                'quantity'     => $row[1],
                                'warehouse_id' => $warehouse_id,
                            ]);
                    }

                }
                unlink($target_file);
                return exit(json_encode([
                    'success' => true
                ]));
            } else {
                return exit(json_encode([
                    'success' => false,
                    'error'   => "Sorry, there was an error uploading your file."
                ]));
            }
        }
        throw new \Exception('Method Not Allowed', 405);
    }
}