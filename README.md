# Cat Tree API

REST API สำหรับจัดการ Category แบบ Tree Structure

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

## วิธีการใช้งานโปรเจกต์

1.  รัน Seeder เพื่อเตรียมข้อมูลเบื้องต้น

    ```bash
    ./vendor/bin/sail artisan db:seed --class="Database\Seeders\CategorySeeder"
    ```

2.  อธิบาย API LIST
    คุณสามารถดูรายละเอียดทั้งหมดของ API ได้ที่ `url//request-docs/` หรือสามารถยิ่งผ่านวิธีการอื่นๆ ที่กำหนด

        ตั้งค่า headers ตามนี้:
        ```http
          {

    "Content-Type": "application/json",
    "Accept":"application/json"
    }

    ```

    ```

### API เส้นทั้งหมด มีดังนี้:

-   **GET** `/api/categories/standalone/{id}`
-   **GET** `/api/categories/tree/{id}`
-   **GET** `/api/categories/all`
-   **POST** `/api/categories/standalone`
-   **DELETE** `/api/categories/{id}`
