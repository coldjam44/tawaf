<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentPlans = [
            [
                'name_ar' => 'دفعة واحدة',
                'name_en' => 'One Time Payment',
                'description_ar' => 'دفع كامل المبلغ دفعة واحدة',
                'description_en' => 'Pay the full amount in one payment',
            ],
            [
                'name_ar' => 'دفعة أولى 20%',
                'name_en' => '20% Down Payment',
                'description_ar' => 'دفع 20% كدفعة أولى والباقي عند التسليم',
                'description_en' => 'Pay 20% as down payment and the rest upon handover',
            ],
            [
                'name_ar' => 'دفعة أولى 30%',
                'name_en' => '30% Down Payment',
                'description_ar' => 'دفع 30% كدفعة أولى والباقي عند التسليم',
                'description_en' => 'Pay 30% as down payment and the rest upon handover',
            ],
            [
                'name_ar' => 'دفعة أولى 40%',
                'name_en' => '40% Down Payment',
                'description_ar' => 'دفع 40% كدفعة أولى والباقي عند التسليم',
                'description_en' => 'Pay 40% as down payment and the rest upon handover',
            ],
            [
                'name_ar' => 'دفعة أولى 50%',
                'name_en' => '50% Down Payment',
                'description_ar' => 'دفع 50% كدفعة أولى والباقي عند التسليم',
                'description_en' => 'Pay 50% as down payment and the rest upon handover',
            ],
            [
                'name_ar' => 'أقساط شهرية 12 شهر',
                'name_en' => '12 Months Installments',
                'description_ar' => 'دفع المبلغ على 12 قسط شهري',
                'description_en' => 'Pay the amount over 12 monthly installments',
            ],
            [
                'name_ar' => 'أقساط شهرية 24 شهر',
                'name_en' => '24 Months Installments',
                'description_ar' => 'دفع المبلغ على 24 قسط شهري',
                'description_en' => 'Pay the amount over 24 monthly installments',
            ],
            [
                'name_ar' => 'أقساط شهرية 36 شهر',
                'name_en' => '36 Months Installments',
                'description_ar' => 'دفع المبلغ على 36 قسط شهري',
                'description_en' => 'Pay the amount over 36 monthly installments',
            ],
            [
                'name_ar' => 'أقساط سنوية 5 سنوات',
                'name_en' => '5 Years Annual Installments',
                'description_ar' => 'دفع المبلغ على 5 أقساط سنوية',
                'description_en' => 'Pay the amount over 5 annual installments',
            ],
            [
                'name_ar' => 'أقساط سنوية 10 سنوات',
                'name_en' => '10 Years Annual Installments',
                'description_ar' => 'دفع المبلغ على 10 أقساط سنوية',
                'description_en' => 'Pay the amount over 10 annual installments',
            ],
        ];

        foreach ($paymentPlans as $plan) {
            DB::table('payment_plans')->insert([
                'name_ar' => $plan['name_ar'],
                'name_en' => $plan['name_en'],
                'description_ar' => $plan['description_ar'],
                'description_en' => $plan['description_en'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
