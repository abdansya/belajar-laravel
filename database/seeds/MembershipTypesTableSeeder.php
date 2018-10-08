<?php

use Illuminate\Database\Seeder;

class MembershipTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ["Silver", "Gold", "Platinum"];
        $discounts = [5, 10, 15];
        $fees = [100000, 300000, 600000];
        foreach (range(0,2) as $index) {
            DB::table('membership_types')->insert([
                'type' => $types[$index],
                'discount' => $discounts[$index],
                'yearly_fee' => $fees[$index]
            ]);
        }
    }
}
