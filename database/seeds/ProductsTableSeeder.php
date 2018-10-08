<?php
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $products = ["Accord", "Civic", "City", "CR-V", "Jazz", "Freed", "Mobilio"];
        $descriptions = ["Tipe manual", "Tipe Otomatis"];
        for ($i=0; $i<10; $i++) {
            DB::insert('insert into products (name, description, price, stock, published) values (:name, :description, :price, :stock, :published)', [
                'name' => $products[rand(0,6)].' '.$faker->firstNameMale,
                'description' => $descriptions[rand(0,1)],
                'price' => rand(100,800) * 1000000,
                'stock' => rand(10,40),
                'published' => rand(0,1)
            ]);
        }

        $this->command->info('Berhasil menambah 100 mobil!');
    }
}
