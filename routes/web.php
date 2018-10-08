<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/list-stock', function(){
    $begin = memory_get_usage();
        foreach (DB::table('products')->get() as $product) {
        if ( $product->stock > 20 ) {
            echo $product->name . ' : ' . $product->stock . '<br>';
        }
    }
    echo 'Total memory usage : ' . (memory_get_usage() - $begin);
});
Route::get('/list-stock-chunk', function(){
    $begin = memory_get_usage();
    DB::table('products')->orderBy('id')->chunk(100, function($products)
    {
        foreach ($products as $product)
        {
            if ( $product->stock > 20 ) {
            echo $product->name . ' : ' . $product->stock . '<br>';
            }
        }
    });
    echo 'Total memory usage : ' . (memory_get_usage() - $begin);
});

Route::get('/order-product', function() {
    // memulai transaksi
    DB::transaction(function() {
        // membuat record di table 'orders'
        $order_id = DB::table('orders')->insertGetId(['customer_id'=>1]);

        // Menambah record baru di 'orders_products'
        DB::table('orders_products')->insert(['order_id'=>$order_id, 'product_id'=>5]);

        // Membayar order (mengisi field 'paid_at' di table 'orders')
        DB::table('orders')->where('id',$order_id)->update(['paid_at'=>new DateTime('now')]);

        // Ada error
        throw new Exception("Oooppssss.. ada error!");

        // Mengurangi stock product
        DB::table('products')->where('id',5)->decrement('stock');
    });
    echo "Berhasil menjual " . DB::table('products')->find(5)->name . '. <br>';
    echo "Stock terkini : " . DB::table('products')->find(5)->stock;
});

Route::get('/customers', function() {
    DB::connection()->enableQueryLog();
    $products = DB::table('products')->get();
    $products = DB::table('customers')->whereIn('id', [1,4,5])->select(['name','phone'])->get();
    $customers = DB::table('customers')->leftJoin('membership_types','customers.membership_type_id', '=', 'membership_types.id')->get();
    dd(DB::getQueryLog());
});

Route::get('/product', function() {
    // DB::connection()->enableQueryLog();
    // $product = Cache::remember('product.lowest-price', 1, function() {
    //     return DB::table('products')
    //     ->where('price', DB::table('products')->min('price'))
    //     ->get();
    // });
    // var_dump($product);
    // var_dump(DB::getQueryLog());

    return App\Product::All();
});

Route::get('/customer/{id}', function($id) {
    try {
        $customer = App\Customer::findOrFail($id);
        return $customer;
    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return "Oppsss.. Customer tidak ditemukan";
    }
});

// Route::get('/product/{id}', function($id) {
//     return App\Product::find($id);
// });


Route::get('product/{product}', function(App\Product $product) {
   return $product; 
});