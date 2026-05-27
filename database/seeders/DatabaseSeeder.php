<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\Service;
use App\Models\Material;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Полный доступ ко всем функциям системы'],
            ['name' => 'employee', 'description' => 'Доступ к управлению назначенными заказами'],
            ['name' => 'client', 'description' => 'Доступ к личному кабинету и своим заказам'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $employeeRoleId = Role::where('name', 'employee')->first()->id;
        $clientRoleId = Role::where('name', 'client')->first()->id;

        // ========== 2. СТАТУСЫ ЗАКАЗОВ (ПЕРЕИМЕНОВАНА ТАБЛИЦА) ==========
        $orderStatuses = [
            ['name' => 'Новый', 'color' => '#6c757d', 'sort_order' => 1],
            ['name' => 'Принят в работу', 'color' => '#007bff', 'sort_order' => 2],
            ['name' => 'В процессе', 'color' => '#fd7e14', 'sort_order' => 3],
            ['name' => 'Готов к выдаче', 'color' => '#28a745', 'sort_order' => 4],
            ['name' => 'Выдан', 'color' => '#20c997', 'sort_order' => 5],
            ['name' => 'Отменён', 'color' => '#dc3545', 'sort_order' => 6],
        ];
        foreach ($orderStatuses as $status) {
            OrderStatus::create($status);
        }

        // ========== 3. СТАТУСЫ ПЛАТЕЖЕЙ (НОВАЯ ТАБЛИЦА) ==========
        $paymentStatuses = [
            ['name' => 'Ожидает оплаты', 'color' => '#ffc107'],
            ['name' => 'Оплачен', 'color' => '#28a745'],
            ['name' => 'Возвращён', 'color' => '#17a2b8'],
            ['name' => 'Просрочен', 'color' => '#dc3545'],
        ];
        foreach ($paymentStatuses as $status) {
            PaymentStatus::create($status);
        }

        // Получаем ID статусов платежей
        $paymentStatusPaidId = PaymentStatus::where('name', 'Оплачен')->first()->id;
        $paymentStatusPendingId = PaymentStatus::where('name', 'Ожидает оплаты')->first()->id;

        User::create([
            'name' => 'Администратор',
            'email' => 'admin@strochka.ru',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRoleId,
            'phone' => '+7 (999) 111-22-33',
            'address' => 'г. Москва, ул. Центральная, д. 1',
        ]);

        User::create([
            'name' => 'Мария Иванова',
            'email' => 'maria@strochka.ru',
            'password' => Hash::make('employee123'),
            'role_id' => $employeeRoleId,
            'phone' => '+7 (999) 222-33-44',
            'address' => 'г. Москва, ул. Рабочая, д. 5',
            'position' => 'Портной-закройщик',
            'hired_at' => '2023-01-15',
            'salary' => 55000.00,
        ]);

        User::create([
            'name' => 'Анна Смирнова',
            'email' => 'anna@strochka.ru',
            'password' => Hash::make('employee123'),
            'role_id' => $employeeRoleId,
            'phone' => '+7 (999) 333-44-55',
            'position' => 'Мастер по ремонту',
            'hired_at' => '2023-03-10',
            'salary' => 48000.00,
        ]);

        // Клиент 1
        $client1 = User::create([
            'name' => 'Елена Петрова',
            'email' => 'elena@mail.ru',
            'password' => Hash::make('client123'),
            'role_id' => $clientRoleId,
            'phone' => '+7 (916) 123-45-67',
            'address' => 'г. Москва, ул. Тверская, д. 10, кв. 5',
            'birth_date' => '1990-05-20',
            'notes' => 'Постоянный клиент, скидка 5%',
        ]);

        // Клиент 2
        $client2 = User::create([
            'name' => 'Иван Соколов',
            'email' => 'ivan@mail.ru',
            'password' => Hash::make('client123'),
            'role_id' => $clientRoleId,
            'phone' => '+7 (915) 234-56-78',
            'address' => 'г. Москва, ул. Арбат, д. 15',
            'birth_date' => '1985-11-02',
            'notes' => '',
        ]);

        // Клиент 3
        $client3 = User::create([
            'name' => 'Ольга Новикова',
            'email' => 'olga@mail.ru',
            'password' => Hash::make('client123'),
            'role_id' => $clientRoleId,
            'phone' => '+7 (917) 345-67-89',
            'address' => 'г. Москва, ул. Ленина, д. 25',
            'birth_date' => '1995-08-15',
            'notes' => 'Любит нестандартные решения',
        ]);

        // Получаем ID сотрудников и статусов заказов
        $employee1 = User::where('email', 'maria@strochka.ru')->first();
        $employee2 = User::where('email', 'anna@strochka.ru')->first();
        $statusNew = OrderStatus::where('name', 'Новый')->first();
        $statusInProgress = OrderStatus::where('name', 'В процессе')->first();
        $statusReady = OrderStatus::where('name', 'Готов к выдаче')->first();

        // ========== 5. УСЛУГИ ==========
        $services = [
            ['name' => 'Подшив брюк', 'description' => 'Укорачивание брюк до нужной длины с сохранением фабричного шва', 'price' => 500],
            ['name' => 'Ушив/распуск по талии', 'description' => 'Корректировка размера по талии', 'price' => 700],
            ['name' => 'Замена молнии', 'description' => 'Замена молнии в куртке, джинсах или платье', 'price' => 800],
            ['name' => 'Ремонт шва', 'description' => 'Зашивание разошедшегося шва', 'price' => 350],
            ['name' => 'Утяжка платья', 'description' => 'Уменьшение платья по фигуре', 'price' => 1200],
            ['name' => 'Пошив юбки', 'description' => 'Индивидуальный пошив юбки по меркам клиента', 'price' => 3500],
            ['name' => 'Пошив блузы', 'description' => 'Индивидуальный пошив блузы из ткани клиента', 'price' => 4500],
            ['name' => 'Глажка и отпаривание', 'description' => 'Профессиональная глажка любых изделий', 'price' => 300],
            ['name' => 'Замена пуговиц', 'description' => 'Замена пуговиц с подбором по цвету и размеру', 'price' => 150],
            ['name' => 'Ремонт джинсов', 'description' => 'Зашивание дыр, укрепление карманов', 'price' => 600],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Получаем ID услуг
        $service1 = Service::where('name', 'Подшив брюк')->first();
        $service2 = Service::where('name', 'Замена молнии')->first();
        $service3 = Service::where('name', 'Утяжка платья')->first();
        $serviceUshiv = Service::where('name', 'Ушив/распуск по талии')->first();

        // ========== 6. МАТЕРИАЛЫ ==========
        $materials = [
            ['name' => 'Молния металлическая 50 см', 'unit' => 'шт', 'price_per_unit' => 180, 'stock_quantity' => 30],
            ['name' => 'Молния потайная 60 см', 'unit' => 'шт', 'price_per_unit' => 150, 'stock_quantity' => 25],
            ['name' => 'Нитки х/б (белые)', 'unit' => 'катушка', 'price_per_unit' => 45, 'stock_quantity' => 100],
            ['name' => 'Нитки х/б (чёрные)', 'unit' => 'катушка', 'price_per_unit' => 45, 'stock_quantity' => 100],
            ['name' => 'Нитки армированные', 'unit' => 'катушка', 'price_per_unit' => 70, 'stock_quantity' => 50],
            ['name' => 'Пуговицы деревянные', 'unit' => 'шт', 'price_per_unit' => 25, 'stock_quantity' => 200],
            ['name' => 'Пуговицы металлические', 'unit' => 'шт', 'price_per_unit' => 35, 'stock_quantity' => 150],
            ['name' => 'Ткань хлопок', 'unit' => 'метр', 'price_per_unit' => 850, 'stock_quantity' => 40],
            ['name' => 'Ткань лён', 'unit' => 'метр', 'price_per_unit' => 1200, 'stock_quantity' => 25],
            ['name' => 'Ткань джинсовая', 'unit' => 'метр', 'price_per_unit' => 950, 'stock_quantity' => 30],
            ['name' => 'Косая бейка', 'unit' => 'метр', 'price_per_unit' => 30, 'stock_quantity' => 100],
            ['name' => 'Крючки и кнопки', 'unit' => 'комплект', 'price_per_unit' => 40, 'stock_quantity' => 80],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }

        // ========== 7. ЗАКАЗЫ ==========

        // Заказ 1 (в работе, назначен сотруднику)
        $order1 = DB::table('orders')->insertGetId([
            'client_id' => $client1->id,
            'employee_id' => $employee1->id,
            'order_status_id' => $statusInProgress->id,
            'order_date' => '2024-05-10',
            'deadline' => '2024-05-17',
            'description' => 'Подшить двое брюк и заменить молнию на куртке',
            'total_price' => 0, // временно 0, пересчитается ниже
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Привязываем услуги к заказу 1
        DB::table('order_services')->insert([
            ['order_id' => $order1, 'service_id' => $service1->id, 'quantity' => 2, 'price' => 500],
            ['order_id' => $order1, 'service_id' => $service2->id, 'quantity' => 1, 'price' => 800],
        ]);

        // Заказ 2 (новый, ещё не назначен сотруднику)
        $order2 = DB::table('orders')->insertGetId([
            'client_id' => $client2->id,
            'employee_id' => null,
            'order_status_id' => $statusNew->id,
            'order_date' => '2024-05-12',
            'deadline' => '2024-05-20',
            'description' => 'Ушить пиджак по фигуре',
            'total_price' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('order_services')->insert([
            ['order_id' => $order2, 'service_id' => $serviceUshiv->id, 'quantity' => 1, 'price' => 700],
        ]);

        // Заказ 3 (готов к выдаче)
        $order3 = DB::table('orders')->insertGetId([
            'client_id' => $client3->id,
            'employee_id' => $employee2->id,
            'order_status_id' => $statusReady->id,
            'order_date' => '2024-05-01',
            'deadline' => '2024-05-08',
            'completed_at' => '2024-05-07',
            'description' => 'Утяжка платья и замена молнии',
            'total_price' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('order_services')->insert([
            ['order_id' => $order3, 'service_id' => $service3->id, 'quantity' => 1, 'price' => 1200],
            ['order_id' => $order3, 'service_id' => $service2->id, 'quantity' => 1, 'price' => 800],
        ]);

        // Пересчитываем total_price для всех заказов
        $orders = [$order1, $order2, $order3];
        foreach ($orders as $orderId) {
            $total = DB::table('order_services')
                ->where('order_id', $orderId)
                ->sum(DB::raw('quantity * price'));
            DB::table('orders')->where('id', $orderId)->update(['total_price' => $total]);
        }

        // ========== 8. ПЛАТЕЖИ ==========
        DB::table('payments')->insert([
            [
                'order_id' => $order1,
                'amount' => 1300,
                'payment_method' => 'cash',
                'payment_status_id' => $paymentStatusPaidId,
                'payment_date' => '2024-05-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $order3,
                'amount' => 2000,
                'payment_method' => 'card',
                'payment_status_id' => $paymentStatusPaidId,
                'payment_date' => '2024-05-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== 9. ВЫВОД ИНФОРМАЦИИ ==========
        $this->command->info('База данных успешно заполнена!');
        $this->command->info('');
        $this->command->info('Тестовые аккаунты:');
        $this->command->info('Админ: admin@strochka.ru / admin123');
        $this->command->info('Сотрудник: maria@strochka.ru / employee123');
        $this->command->info('Клиент: elena@mail.ru / client123');
    }
}