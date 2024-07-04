# Cat Tree API

Cat Tree API เป็น REST API สำหรับจัดการ Category แบบ Tree Structure

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

### สร้าง Category ใหม่

```http
POST /api/categories/
Content-Type: application/json

{
    "name": "หมวดหมู่ใหม่",
    "parent_id": null
}
```
