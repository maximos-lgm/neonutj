# Neon Cloud PostgreSQL + Laravel REST API

Esta API REST cumple con todos los requerimientos de la actividad técnica:
- Conexión a base de datos relacional remota en **Neon Postgres** con SSL habilitado.
- 4 tablas relacionales (`users`, `products`, `orders`, `order_items`) con claves foráneas, llaves primarias e integridad referencial (`1:N` y `N:M`).
- Endpoints CRUD completos con transacciones de base de datos (`DB::transaction`).
- Manejo de restricciones de integridad referencial (ej: bloqueo de eliminación de productos con pedidos asociados, cascada controlada en pedidos e ítems).
- Colección de Postman / Thunder Client lista para importar.

---

## 🛠️ Configuración de Conexión a Neon Cloud

Ubicada en `.env`:
```dotenv
DB_CONNECTION=pgsql
DB_HOST=ep-autumn-brook-aek74hwl.c-2.us-east-2.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=npg_xnNAJrcsa26B
DB_SSLMODE=require

# Neon S3 Compatible Object Storage
AWS_ENDPOINT=https://br-aged-scene-aewh2viu.storage.c-2.us-east-2.aws.neon.tech
AWS_ACCESS_KEY_ID="nak_live_b069feb5b9404d748bda524c3591c96f"
AWS_SECRET_ACCESS_KEY="nsk_live_c7c9dce15db71df2c6aa090c6ffe8f3eb2d0b708222942886ba3dc254a69c222"
AWS_DEFAULT_REGION="us-east-2"
AWS_BUCKET="uploads"
AWS_USE_PATH_STYLE_ENDPOINT=true
```

---

## 🚀 Cómo Iniciar el Servidor Local

```bash
cd /home/maximo/.gemini/antigravity/scratch/neon-laravel-api
php artisan serve --port=8080
```

---

## 📋 Endpoints de la API

Base URL: `http://127.0.0.1:8080`

### 1. Healthcheck / Raíz
- `GET /api`: Comprueba el estado de la API y la conexión a Neon.

### 2. Pedidos (`/api/orders`) - Cumplimiento CRUD Requerido
- **`POST /api/orders` (CREATE)**:
  - Crea un pedido asociando un usuario y múltiples ítems/productos de forma transaccional.
  - Body:
    ```json
    {
      "user_id": 1,
      "notes": "Entrega en piso 3",
      "items": [
        {"product_id": 2, "quantity": 2},
        {"product_id": 3, "quantity": 1}
      ]
    }
    ```
- **`GET /api/orders` (READ ALL)**:
  - Consulta general de pedidos con relaciones cargadas (`user`, `order_items.product`).
- **`GET /api/orders/{id}` (READ BY ID)**:
  - Consulta detallada por ID incluyendo datos completos del usuario y productos asociados.
- **`PATCH /api/orders/{id}` o `PUT /api/orders/{id}` (UPDATE)**:
  - Actualiza el estado (`status`: `pending`, `processing`, `completed`, `cancelled`) y notas.
  - Body:
    ```json
    {
      "status": "completed",
      "notes": "Entregado satisfactoriamente"
    }
    ```
- **`DELETE /api/orders/{id}` (DELETE)**:
  - Elimina el pedido manteniendo la integridad referencial (elimina sus registros asociados en `order_items`).

### 3. Usuarios (`/api/users`)
- `GET /api/users`: Lista de usuarios registrados en Neon.
- `POST /api/users`: Registra un nuevo usuario con validaciones de email único.
- `GET /api/users/{id}`: Detalle del usuario con historial de pedidos.
- `PUT/PATCH /api/users/{id}`: Actualización de datos de usuario.
- `DELETE /api/users/{id}`: Eliminación de usuario.

### 4. Productos (`/api/products`)
- `GET /api/products`: Catálogo de productos disponibles.
- `POST /api/products`: Creación de productos con precio y stock.
- `GET /api/products/{id}`: Detalle de producto.
- `PUT/PATCH /api/products/{id}`: Modificación de producto.
- `DELETE /api/products/{id}`: Eliminación protegida (retorna `409 Conflict` si tiene pedidos existentes para proteger la integridad referencial).

---

## 📦 Pruebas con Postman o Thunder Client
El archivo [`postman_collection.json`](file:///home/maximo/.gemini/antigravity/scratch/neon-laravel-api/postman_collection.json) se encuentra en la raíz del proyecto. Solo debes importarlo en tu cliente favorito (Postman, Thunder Client en VS Code o Insomnia) y todas las peticiones estarán listas para ser ejecutadas con un clic.
# neonutj
