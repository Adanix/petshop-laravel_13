<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin (User)
        User::updateOrCreate(
            ['email' => 'admin@petshop.test'],
            [
                'name' => 'Admin Petshop',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Data Kategori (Categories)
        $categoriesData = [
            [
                'name' => 'Makanan Kucing',
                'slug' => 'makanan-kucing',
                'description' => 'Nutrisi lengkap dan lezat untuk anabul kucing kesayangan segala usia.',
                'is_active' => true,
            ],
            [
                'name' => 'Makanan Anjing',
                'slug' => 'makanan-anjing',
                'description' => 'Makanan kering dan basah bergizi tinggi untuk anjing aktif dan sehat.',
                'is_active' => true,
            ],
            [
                'name' => 'Pasir & Kebersihan',
                'slug' => 'pasir-kebersihan',
                'description' => 'Pasir gumpal wangi, deodorizer, dan perlengkapan sanitasi peliharaan.',
                'is_active' => true,
            ],
            [
                'name' => 'Vitamin & Perawatan',
                'slug' => 'vitamin-perawatan',
                'description' => 'Suplemen daya tahan tubuh, vitamin bulu, dan shampoo obat berkhasiat.',
                'is_active' => true,
            ],
            [
                'name' => 'Aksesoris & Mainan',
                'slug' => 'aksesoris-mainan',
                'description' => 'Kandang, tas gendong, kalung, tali kekang, dan mainan interaktif.',
                'is_active' => true,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // 3. Data Produk (Products)
        $productsData = [
            [
                'category_slug' => 'makanan-kucing',
                'name' => 'Royal Canin Hair & Skin Care 2Kg',
                'slug' => 'royal-canin-hair-skin-care-2kg',
                'sku' => 'RC-HS-2000',
                'description' => 'Formula nutrisi seimbang yang diformulasikan khusus untuk menjaga kesehatan kulit dan kilau bulu kucing dewasa. Terbukti klinis membantu regenerasi bulu dalam 21 hari.',
                'price' => 285000,
                'stock' => 25,
                'weight_grams' => 2000,
                'image_url' => 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-kucing',
                'name' => 'Whiskas Pouch Adult Tuna 80gr',
                'slug' => 'whiskas-pouch-adult-tuna-80gr',
                'sku' => 'WHK-TUNA-80',
                'description' => 'Makanan basah bergizi dengan potongan ikan tuna segar yang kaya akan Omega 3 & 6, zinc, dan protein berkualitas untuk vitalitas kucing harian.',
                'price' => 8500,
                'stock' => 120,
                'weight_grams' => 85,
                'image_url' => 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-kucing',
                'name' => 'Me-O Creamy Treats Salmon (Isi 4x15gr)',
                'slug' => 'me-o-creamy-treats-salmon-isi-4x15gr',
                'sku' => 'MEO-CT-60',
                'description' => 'Snack kucing cair lezat dengan rasa salmon asli yang disukai anabul. Sangat cocok sebagai camilan bonding, reward pelatihan, atau perangsang nafsu makan.',
                'price' => 16000,
                'stock' => 50,
                'weight_grams' => 70,
                'image_url' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'category_slug' => 'makanan-anjing',
                'name' => 'Pedigree Adult Beef & Veg 1.5Kg',
                'slug' => 'pedigree-adult-beef-veg-1-5kg',
                'sku' => 'PDG-BV-1500',
                'description' => 'Makanan kering anjing dewasa dengan paduan rasa daging sapi lezat dan sayuran segar, diperkaya dengan kalsium untuk kekuatan tulang dan gigi.',
                'price' => 95000,
                'stock' => 18,
                'weight_grams' => 1500,
                'image_url' => 'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'category_slug' => 'pasir-kebersihan',
                'name' => 'Pasir Kucing Gumpal Wangi Kopi 10 Liter (8Kg)',
                'slug' => 'pasir-kucing-gumpal-wangi-kopi-10-liter-8kg',
                'sku' => 'LITTER-CF-8000',
                'description' => 'Bentonite cat litter kualitas premium dengan daya gumpal super cepat, minim debu, dan aroma kopi hangat yang mengikat bau tak sedap secara optimal.',
                'price' => 65000,
                'stock' => 4, // Stok <= 5 untuk menguji fitur alert stok menipis di dashboard
                'weight_grams' => 8000,
                'image_url' => 'https://images.unsplash.com/photo-1545249390-6bdfa286032f?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'category_slug' => 'vitamin-perawatan',
                'name' => 'Virbac Nutri-Plus Gel Suplemen Hewan 120gr',
                'slug' => 'virbac-nutri-plus-gel-suplemen-hewan-120gr',
                'sku' => 'VRB-NPG-120',
                'description' => 'Gel suplemen nutrisi berenergi tinggi yang mengandung multivitamin dan mineral penting. Sangat dianjurkan untuk hewan dalam masa pemulihan, hamil, atau lemas.',
                'price' => 135000,
                'stock' => 15,
                'weight_grams' => 150,
                'image_url' => 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'category_slug' => 'vitamin-perawatan',
                'name' => 'Shampoo Anti Kutu & Jamur Kucing Anjing 250ml',
                'slug' => 'shampoo-anti-kutu-jamur-kucing-anjing-250ml',
                'sku' => 'SHP-MED-250',
                'description' => 'Shampoo terapeutik berformula lembut untuk membasmi kutu, telur kutu, serta meredakan gatal akibat jamur dan iritasi kulit pada anjing maupun kucing.',
                'price' => 45000,
                'stock' => 30,
                'weight_grams' => 300,
                'image_url' => 'https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
            [
                'category_slug' => 'aksesoris-mainan',
                'name' => 'Tas Ransel Astronot Kucing Anjing Transparan',
                'slug' => 'tas-ransel-astronot-kucing-anjing-transparan',
                'sku' => 'BAG-ASTRO-01',
                'description' => 'Pet cargo model backpack dengan jendela kubah transparan dan 9 lubang sirkulasi udara besar. Sangat nyaman dan aman untuk membawa anabul bepergian.',
                'price' => 145000,
                'stock' => 0, // Stok 0 untuk menguji tampilan badge Stok Habis di katalog
                'weight_grams' => 1200,
                'image_url' => 'https://images.unsplash.com/photo-1574158622682-e40e69881006?w=600&auto=format&fit=crop&q=80',
                'is_active' => true,
            ],
        ];

        $products = [];
        foreach ($productsData as $prod) {
            $catSlug = $prod['category_slug'];
            unset($prod['category_slug']);
            $prod['category_id'] = $categories[$catSlug]->id;

            $products[$prod['sku']] = Product::updateOrCreate(
                ['sku' => $prod['sku']],
                $prod
            );
        }

        // 4. Data Contoh Transaksi Guest (Orders & OrderItems) - 3 Skenario

        // Skenario 1: Order 1 (Status: unpaid / pending)
        // Pembeli: "Budi Santoso" (081234567890), 2x Whiskas Pouch, 1x Me-O Creamy Treats, JNE REG, unpaid
        $order1Number = 'PET-20261014-B001';
        $order1 = Order::updateOrCreate(
            ['order_number' => $order1Number],
            [
                'tracking_token' => 'a1b2c3d4-e5f6-4a1b-8c2d-3e4f5a6b7c81',
                'customer_name' => 'Budi Santoso',
                'customer_email' => 'budi.santoso@example.com',
                'customer_phone' => '081234567890',
                'shipping_address' => 'Jl. Melati No. 12 RT 03/RW 05, Kel. Menteng',
                'city_or_district' => 'Jakarta Pusat',
                'postal_code' => '10310',
                'total_weight_grams' => 240, // (2*85) + 70 = 240g
                'courier_code' => 'jne',
                'courier_service' => 'REG',
                'shipping_cost' => 12000,
                'tracking_number' => null,
                'subtotal' => 33000, // (2*8500) + 16000
                'grand_total' => 45000,
                'payment_method' => 'mock_gateway',
                'payment_status' => 'unpaid',
                'payment_reference' => null,
                'paid_at' => null,
                'order_status' => 'pending',
                'notes' => 'Tolong dibungkus bubble wrap yang tebal ya min.',
            ]
        );

        $order1->items()->delete();
        $whiskas = $products['WHK-TUNA-80'];
        $meo = $products['MEO-CT-60'];

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $whiskas->id,
            'product_name_snapshot' => $whiskas->name,
            'price_snapshot' => $whiskas->price,
            'weight_grams_snapshot' => $whiskas->weight_grams,
            'quantity' => 2,
            'subtotal' => $whiskas->price * 2,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $meo->id,
            'product_name_snapshot' => $meo->name,
            'price_snapshot' => $meo->price,
            'weight_grams_snapshot' => $meo->weight_grams,
            'quantity' => 1,
            'subtotal' => $meo->price * 1,
        ]);

        // Skenario 2: Order 2 (Status: paid / processing)
        // Pembeli: "Siti Aminah" (081298765432), 1x Royal Canin 2Kg, J&T Express, paid (paid_at terisi), menunggu admin input resi
        $order2Number = 'PET-20261014-S002';
        $order2 = Order::updateOrCreate(
            ['order_number' => $order2Number],
            [
                'tracking_token' => 'b2c3d4e5-f6a1-4b2c-9d3e-4f5a6b7c8d92',
                'customer_name' => 'Siti Aminah',
                'customer_email' => 'siti.aminah@example.com',
                'customer_phone' => '081298765432',
                'shipping_address' => 'Komplek Graha Permata Indah Blok C2 No. 15',
                'city_or_district' => 'Bandung',
                'postal_code' => '40123',
                'total_weight_grams' => 2000,
                'courier_code' => 'jnt',
                'courier_service' => 'EZ',
                'shipping_cost' => 28000, // 2kg * 14.000
                'tracking_number' => null, // Menunggu admin input resi
                'subtotal' => 285000,
                'grand_total' => 313000,
                'payment_method' => 'mock_gateway',
                'payment_status' => 'paid',
                'payment_reference' => 'MOCK-PAY-SITI98765',
                'paid_at' => now()->subHours(2),
                'order_status' => 'processing',
                'notes' => 'Kucing saya sedang perawatan bulu, mohon dikirim segera.',
            ]
        );

        $order2->items()->delete();
        $rc = $products['RC-HS-2000'];
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $rc->id,
            'product_name_snapshot' => $rc->name,
            'price_snapshot' => $rc->price,
            'weight_grams_snapshot' => $rc->weight_grams,
            'quantity' => 1,
            'subtotal' => $rc->price,
        ]);

        // Skenario 3: Order 3 (Status: paid / shipped)
        // Pembeli: "Andi Wijaya" (081355556666), 1x Pedigree 1.5Kg dan 1x Virbac Nutri-Plus Gel, JNE REG, sudah lunas, resi JNE9988776655ID
        $order3Number = 'PET-20261014-A003';
        $order3 = Order::updateOrCreate(
            ['order_number' => $order3Number],
            [
                'tracking_token' => 'c3d4e5f6-a1b2-4c3d-ae4f-5a6b7c8d9e03',
                'customer_name' => 'Andi Wijaya',
                'customer_email' => 'andi.wijaya@example.com',
                'customer_phone' => '081355556666',
                'shipping_address' => 'Jl. Panglima Sudirman No. 88, Gubeng',
                'city_or_district' => 'Surabaya',
                'postal_code' => '60241',
                'total_weight_grams' => 1650, // 1500 + 150 = 1650g (ceil = 2kg)
                'courier_code' => 'jne',
                'courier_service' => 'REG',
                'shipping_cost' => 24000, // 2kg * 12.000
                'tracking_number' => 'JNE9988776655ID',
                'subtotal' => 230000, // 95000 + 135000
                'grand_total' => 254000,
                'payment_method' => 'bank_transfer',
                'payment_status' => 'paid',
                'payment_reference' => 'BT-BCA-20261013-0981',
                'paid_at' => now()->subDay(),
                'order_status' => 'shipped',
                'notes' => 'Jika rumah kosong, titipkan ke satpam pos depan ya pak kurir.',
            ]
        );

        $order3->items()->delete();
        $pedigree = $products['PDG-BV-1500'];
        $virbac = $products['VRB-NPG-120'];

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $pedigree->id,
            'product_name_snapshot' => $pedigree->name,
            'price_snapshot' => $pedigree->price,
            'weight_grams_snapshot' => $pedigree->weight_grams,
            'quantity' => 1,
            'subtotal' => $pedigree->price,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $virbac->id,
            'product_name_snapshot' => $virbac->name,
            'price_snapshot' => $virbac->price,
            'weight_grams_snapshot' => $virbac->weight_grams,
            'quantity' => 1,
            'subtotal' => $virbac->price,
        ]);
    }
}
