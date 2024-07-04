# Category API

REST API สำหรับจัดการ Category แบบ Tree Structure และ standalone

## การติดตั้ง (Installation)

1. Clone the repo

    ```bash
    git clone https://github.com/ramath-x/cat-tree-api.git
    cd cat-tree-api
    ```

2. Copy .env.example to .env

    ```bash
    cp .env.example .env
    ```

3. Install dependencies

    ```bash
    docker run --rm --interactive --tty \
    --volume $PWD:/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts
    ```

4. Create horizon.log

    ```bash
    touch storage/logs/horizon.log
    ```

5. Run docker

    ```bash
    ./vendor/bin/sail up -d
    ```

6. Generate app key

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

7. Run database migrations
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

## วิธีการใช้งานโปรเจกต์

1.  รัน Seeder เพื่อเตรียมข้อมูลเบื้องต้น

    ```bash
    ./vendor/bin/sail artisan db:seed --class="Database\Seeders\CategorySeeder"
    ```

2.  คุณสามารถดูรายละเอียดทั้งหมดของ API ได้ที่ `http://localhost/request-docs/`

สามารถยิ่งผ่านวิธีการอื่นๆ

    การตั้งค่า headers ตามนี้:

    ```http

    "Content-Type": "application/json",
    "Accept":"application/json"


    ```

### API เส้นทั้งหมด มีดังนี้:

-   **GET** `/api/categories/all`
-   **GET** `/api/categories/standalone/{id}`
-   **GET** `/api/categories/tree/{id}`
-   **POST** `/api/categories/standalone`
-   **DELETE** `/api/categories/{id}`

## รายละเอียด API เส้นทั้งหมด

-   **GET** `/api/categories/all`

    -   รับข้อมูล Category ทั้งหมด

-   **GET** `/api/categories/standalone/{id}`

    -   ใช้ parameter `id` (required) เพื่อรับข้อมูล Category ที่เป็น standalone node
    -   `per_page` (optional): จำนวนข้อมูลต่อหน้า ต้องเป็นค่า `int` ระหว่าง 1 ถึง 100 (default: 10)
    -   `page` (optional): เลขหน้าที่ต้องการ ต้องเป็นค่า `int` มากกว่า 0 (default: 1)

-   **GET** `/api/categories/tree/{id}`

    -   ใช้ parameter `id` (required) เพื่อรับข้อมูล Category แบบ Tree ภายใต้ node ที่ระบุ
    -   `per_page` (optional): จำนวนข้อมูลต่อหน้า ต้องเป็นค่า `int` ระหว่าง 1 ถึง 100 (default: 10)
    -   `page` (optional): เลขหน้าที่ต้องการ ต้องเป็นค่า `int` มากกว่า 0 (default: 1)

-   **POST** `/api/categories/standalone`

    -   สร้าง Category ใหม่ที่เป็น standalone node
    -   Parameters:
        -   `category_name` (required): ต้องเป็น `string` และไม่เกิน 255 ตัวอักษร
    -   ตัวอย่าง JSON:
        ```json
        {
            "category_name": "category standalone"
        }
        ```

-   **POST** `/api/categories/leaf`

    -   สร้าง Category ใหม่ที่เป็น leaf node
    -   Parameters:
        -   `category_name` (required): ต้องเป็น `string` และไม่เกิน 255 ตัวอักษร
        -   `parent_id` (required): ต้องเป็น `exists:categories,id`
    -   ตัวอย่าง JSON:
        ```json
        {
            "category_name": "category leaf node",
            "parent_id": "1"
        }
        ```

-   **DELETE** `/api/categories/{id}` - ลบ Category ตาม `id` ที่ระบุ

        ### การทดสอบ
        - เพื่อทดสอบสร้าง Tree ที่มีความลึก 10,000 Node และสามารถ เรียกดู tree จาก root node ได้

    โดยใช้ Response Time ไม่เกิน 2000ms - 3000ms (2-3 Seconds), ใช้คำสั่งนี้:

    ```bash
    ./vendor/bin/sail artisan test --filter=CategoryTreePerformanceTest

    ```

    # ตัวอย่างผลลัพธ์

-   เมื่อทดสอบสำเร็จ, ผลลัพธ์จะแสดงดังนี้:

```bash
    PASS  Tests\Feature\CategoryTreePerformanceTest
✓ fetch deep tree performance                                                                                 21.36s

Tests:    1 passed (3 assertions)
Duration: 21.54s

```

-นี้ยืนยันว่าการดึงต้นไม้ลึกสามารถทำได้ตามเงื่อนไขการใช้งานที่กำหนดไว้
