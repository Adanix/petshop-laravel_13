<?php

namespace App\Services;

class ShippingService
{
    /**
     * Hitung opsi tarif kurir berdasarkan berat (gram) dan kota tujuan.
     * 
     * // TODO: Integrasi API RajaOngkir / Biteship di tahap akhir
     *
     * @param int $weightGrams
     * @param string $destination
     * @return array
     */
    public function calculateRates(int $weightGrams, string $destination = ''): array
    {
        // TODO: Integrasi API RajaOngkir / Biteship di tahap akhir

        // Kalkulasi pembulatan ke atas per kilogram (minimal 1 kg)
        $weightInKg = (int) ceil(max(1, $weightGrams) / 1000);

        return [
            [
                'courier_code' => 'jne',
                'courier_name' => 'JNE Express',
                'service' => 'REG',
                'description' => 'Layanan Reguler (2-3 hari kerja)',
                'cost' => $weightInKg * 12000,
                'estimated_days' => '2-3 hari',
            ],
            [
                'courier_code' => 'jnt',
                'courier_name' => 'J&T Express',
                'service' => 'EZ',
                'description' => 'Layanan Kilat Cepat (1-2 hari kerja)',
                'cost' => $weightInKg * 14000,
                'estimated_days' => '1-2 hari',
            ],
            [
                'courier_code' => 'instant',
                'courier_name' => 'Kurir Instan Lokal',
                'service' => 'SameDay',
                'description' => 'Pengiriman Cepat Hari Ini (Khusus Area Lokal)',
                'cost' => 25000 + max(0, ($weightInKg - 1) * 5000),
                'estimated_days' => '3-6 jam',
            ],
        ];
    }

    /**
     * Alias untuk konsistensi pemanggilan sesuai spesifikasi.
     */
    public function getAvailableRates(int $weightGrams, string $destination = ''): array
    {
        return $this->calculateRates($weightGrams, $destination);
    }

    /**
     * Ambil biaya untuk kurir & service tertentu.
     */
    public function getRate(string $courierCode, string $service, int $weightGrams, string $destination = ''): ?int
    {
        $rates = $this->calculateRates($weightGrams, $destination);
        foreach ($rates as $rate) {
            if ($rate['courier_code'] === $courierCode && $rate['service'] === $service) {
                return $rate['cost'];
            }
        }
        return null;
    }
}
