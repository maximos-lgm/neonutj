<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Usuarios
        $user1 = User::firstOrCreate(
            ['email' => 'carlos.mendoza@example.com'],
            [
                'name' => 'Carlos Mendoza',
                'password' => Hash::make('password123'),
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'sofia.valdez@example.com'],
            [
                'name' => 'Sofía Valdez',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Crear Productos
        $laptop = Product::firstOrCreate(
            ['name' => 'Laptop Lenovo ThinkPad T14'],
            [
                'description' => '16GB RAM, 512GB SSD, Intel Core i7 13th Gen',
                'price' => 1250.00,
                'stock' => 15,
                'image_url' => 'https://example.com/images/thinkpad.jpg',
            ]
        );

        $mouse = Product::firstOrCreate(
            ['name' => 'Mouse Inalámbrico Logitech MX Master 3S'],
            [
                'description' => 'Sensor 8K DPI, silencioso, conectividad Bluetooth/Bolt',
                'price' => 99.50,
                'stock' => 50,
                'image_url' => 'https://example.com/images/mouse.jpg',
            ]
        );

        $monitor = Product::firstOrCreate(
            ['name' => 'Monitor Dell UltraSharp 27" 4K'],
            [
                'description' => 'Panel IPS Black, USB-C 90W Hub, 98% DCI-P3',
                'price' => 580.00,
                'stock' => 20,
                'image_url' => 'https://example.com/images/monitor.jpg',
            ]
        );

        // 3. Crear Pedido y sus Detalles (Transaccional)
        if (Order::count() === 0) {
            $order1 = Order::create([
                'user_id' => $user1->id,
                'total_amount' => 1349.50,
                'status' => 'completed',
                'notes' => 'Entregar en horario de oficina 9am a 6pm',
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $laptop->id,
                'quantity' => 1,
                'unit_price' => 1250.00,
                'subtotal' => 1250.00,
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $mouse->id,
                'quantity' => 1,
                'unit_price' => 99.50,
                'subtotal' => 99.50,
            ]);

            $order2 = Order::create([
                'user_id' => $user2->id,
                'total_amount' => 580.00,
                'status' => 'pending',
                'notes' => 'Llamar antes de entregar',
            ]);

            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $monitor->id,
                'quantity' => 1,
                'unit_price' => 580.00,
                'subtotal' => 580.00,
            ]);
        }
    }
}
