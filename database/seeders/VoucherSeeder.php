<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vouchers = [
            [
                'code'             => 'OWEN50',
                'name'             => 'Giảm ngay 50.000đ cho đơn từ 499.000đ',
                'type'             => 'fixed',
                'value'            => 50000,
                'max_discount'     => null,
                'min_order_amount' => 499000,
                'usage_limit'      => 500,
                'used_count'       => 12,
                'start_date'       => now()->subDays(10),
                'end_date'         => now()->addYear(),
                'is_active'        => true,
            ],
            [
                'code'             => 'LOCAL15',
                'name'             => 'Ưu đãi VIP giảm 15% (tối đa 200.000đ) đơn từ 999.000đ',
                'type'             => 'percent',
                'value'            => 15,
                'max_discount'     => 200000,
                'min_order_amount' => 999000,
                'usage_limit'      => 300,
                'used_count'       => 8,
                'start_date'       => now()->subDays(10),
                'end_date'         => now()->addYear(),
                'is_active'        => true,
            ],
            [
                'code'             => 'FREESHIP',
                'name'             => 'Hỗ trợ phí vận chuyển 30.000đ cho đơn từ 300.000đ',
                'type'             => 'fixed',
                'value'            => 30000,
                'max_discount'     => null,
                'min_order_amount' => 300000,
                'usage_limit'      => 1000,
                'used_count'       => 25,
                'start_date'       => now()->subDays(10),
                'end_date'         => now()->addYear(),
                'is_active'        => true,
            ],
            [
                'code'             => 'WELCOME10',
                'name'             => 'Chào thành viên mới - Giảm 10% tối đa 100.000đ',
                'type'             => 'percent',
                'value'            => 10,
                'max_discount'     => 100000,
                'min_order_amount' => 200000,
                'usage_limit'      => 500,
                'used_count'       => 5,
                'start_date'       => now()->subDays(10),
                'end_date'         => now()->addYear(),
                'is_active'        => true,
            ],
        ];

        foreach ($vouchers as $v) {
            Voucher::updateOrCreate(
                ['code' => $v['code']],
                $v
            );
        }
    }
}
