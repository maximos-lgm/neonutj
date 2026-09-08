<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Postgres Cloud API & Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-base: #090d16;
            --bg-card: rgba(16, 24, 40, 0.75);
            --bg-card-hover: rgba(22, 34, 58, 0.85);
            --bg-glass: rgba(255, 255, 255, 0.03);
            --border-glass: rgba(255, 255, 255, 0.08);
            --border-glow: rgba(0, 230, 153, 0.3);
            --primary: #00e699;
            --primary-glow: #00e69940;
            --accent: #3b82f6;
            --accent-glow: #3b82f640;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --danger: #ef4444;
            --danger-glow: #ef444430;
            --warning: #f59e0b;
            --info: #06b6d4;
            --font-main: 'Outfit', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --radius: 14px;
            --shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: var(--font-main);
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: var(--bg-base);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(0, 230, 153, 0.07) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(59, 130, 246, 0.08) 0%, transparent 45%);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(9, 13, 22, 0.8);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-glass);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #00e699, #0284c7);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 1.2rem;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .brand-badge {
            font-size: 0.72rem;
            background: rgba(0, 230, 153, 0.15);
            color: var(--primary);
            padding: 2px 8px;
            border-radius: 20px;
            border: 1px solid var(--border-glow);
            font-weight: 600;
            text-transform: uppercase;
        }

        .cloud-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            padding: 6px 14px;
            border-radius: 30px;
            border: 1px solid var(--border-glass);
            font-size: 0.85rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--primary);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        /* Container & Tabs */
        .container {
            max-width: 1350px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            width: 100%;
            flex: 1;
        }

        .hero-banner {
            background: linear-gradient(135deg, rgba(22, 36, 60, 0.5), rgba(13, 20, 35, 0.7));
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .hero-text h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, #fff, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .hero-stats {
            display: flex;
            gap: 1.2rem;
        }

        .stat-box {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-glass);
            padding: 0.75rem 1.2rem;
            border-radius: 10px;
            text-align: center;
            min-width: 100px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            font-family: var(--font-mono);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        /* Navigation Tabs */
        .tabs {
            display: flex;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-glass);
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 600;
            padding: 0.75rem 1.4rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #fff;
            background: var(--bg-glass);
        }

        .tab-btn.active {
            color: #000;
            background: var(--primary);
            box-shadow: 0 0 20px var(--primary-glow);
        }

        /* Content Sections */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Grid Layouts */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 1024px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Card Component */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            padding: 1.5rem;
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-glass);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary);
        }

        /* Forms & Inputs */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-glass);
            border-radius: 8px;
            padding: 0.7rem 1rem;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary-glow);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.65rem 1.2rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #000;
        }

        .btn-primary:hover {
            background: #00ffaa;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: var(--danger);
            color: #fff;
        }

        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }

        /* Table Design */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(0, 0, 0, 0.25);
            color: var(--text-muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-glass);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-glass);
            font-size: 0.9rem;
            vertical-align: middle;
        }

        tr:hover td {
            background: var(--bg-glass);
        }

        /* Badges */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background: rgba(0, 230, 153, 0.15);
            color: var(--primary);
            border: 1px solid var(--border-glow);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-cancelled {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .price-tag {
            font-family: var(--font-mono);
            font-weight: 600;
            color: var(--primary);
        }

        /* Console / JSON response viewer */
        .json-viewer {
            background: #060910;
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            padding: 1rem;
            font-family: var(--font-mono);
            font-size: 0.82rem;
            color: #38bdf8;
            max-height: 420px;
            overflow: auto;
            white-space: pre-wrap;
            line-height: 1.4;
        }

        /* Notification Toast */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #111827;
            border: 1px solid var(--border-glass);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 100;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        #toast.success { border-color: var(--primary); }
        #toast.error { border-color: var(--danger); }

        /* Items Selector in Orders */
        .order-item-row {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            align-items: center;
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            border-top: 1px solid var(--border-glass);
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <header class="navbar">
        <a href="/" class="brand">
            <div class="brand-logo">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
                <div class="brand-title">Neon Orders API</div>
                <span class="brand-badge">Laravel 12 + PostgreSQL Cloud</span>
            </div>
        </a>

        <div class="cloud-badge">
            <div class="status-dot"></div>
            <span>Neon Serverless AWS (us-east-2)</span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container">
        <!-- Banner Info -->
        <section class="hero-banner">
            <div class="hero-text">
                <h1>Panel de Consumo de API REST</h1>
                <p>Consola interactiva para probar operaciones <strong>CRUD</strong> en tiempo real con relaciones de claves foráneas sobre <strong>Neon Cloud DB</strong>.</p>
            </div>
            <div class="hero-stats">
                <div class="stat-box">
                    <div class="stat-value" id="count-orders">0</div>
                    <div class="stat-label">Pedidos</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value" id="count-users">0</div>
                    <div class="stat-label">Usuarios</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value" id="count-products">0</div>
                    <div class="stat-label">Productos</div>
                </div>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('orders')">
                <i class="fa-solid fa-receipt"></i> Pedidos (CRUD Completo)
            </button>
            <button class="tab-btn" onclick="switchTab('products')">
                <i class="fa-solid fa-box-open"></i> Productos
            </button>
            <button class="tab-btn" onclick="switchTab('users')">
                <i class="fa-solid fa-users"></i> Usuarios
            </button>
            <button class="tab-btn" onclick="switchTab('console')">
                <i class="fa-solid fa-terminal"></i> Consola JSON en Vivo
            </button>
        </div>

        <!-- ================= TAB: PEDIDOS ================= -->
        <section id="tab-orders" class="tab-content active">
            <div class="grid-2">
                <!-- Formulario Crear Pedido (POST) -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-plus-circle"></i> Crear Nuevo Pedido (POST)
                        </div>
                        <span class="status-badge status-completed">Relación 1:N & N:M</span>
                    </div>
                    <form id="form-create-order" onsubmit="createOrder(event)">
                        <div class="form-group">
                            <label class="form-label">Cliente (Usuario):</label>
                            <select id="order-user-id" class="form-control" required>
                                <option value="">Cargando usuarios...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Notas del Pedido:</label>
                            <input type="text" id="order-notes" class="form-control" placeholder="Ej. Entregar en la puerta principal">
                        </div>

                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <label class="form-label" style="margin-bottom: 0;">Productos a Incluir:</label>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="addOrderItemRow()">
                                    <i class="fa-solid fa-plus"></i> Añadir ítem
                                </button>
                            </div>
                            <div id="order-items-container">
                                <!-- Dynamic item row -->
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                            <i class="fa-solid fa-paper-plane"></i> Enviar Pedido a Neon Cloud
                        </button>
                    </form>
                </div>

                <!-- Detalle y Actualización (GET by ID & PATCH) -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-magnifying-glass"></i> Detalle y Edición de Pedido
                        </div>
                        <span class="status-badge status-pending" id="order-detail-badge">Seleccione uno</span>
                    </div>

                    <div id="order-detail-placeholder" style="color: var(--text-muted); text-align: center; padding: 3rem 1rem;">
                        <i class="fa-solid fa-arrow-left" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                        Haz clic en <strong>"Ver Detalle"</strong> en la tabla inferior para cargar los datos anidados del cliente y productos asociados desde Neon PostgreSQL.
                    </div>

                    <div id="order-detail-card" style="display: none;">
                        <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                <span style="color: var(--text-muted);">Pedido ID:</span>
                                <strong id="det-order-id">#</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                <span style="color: var(--text-muted);">Cliente:</span>
                                <span id="det-user-name" style="color: #fff; font-weight: 500;">-</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                <span style="color: var(--text-muted);">Correo:</span>
                                <span id="det-user-email" style="font-family: var(--font-mono); font-size: 0.85rem;">-</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-muted);">Total:</span>
                                <span class="price-tag" id="det-total">$0.00</span>
                            </div>
                        </div>

                        <h4 style="font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-muted);">Ítems Relacionados:</h4>
                        <ul id="det-items-list" style="margin-bottom: 1.5rem; font-size: 0.85rem; padding-left: 1.2rem;">
                        </ul>

                        <!-- Formulario PATCH rápido -->
                        <form id="form-update-order" onsubmit="updateOrder(event)">
                            <input type="hidden" id="edit-order-id">
                            <div class="form-group">
                                <label class="form-label">Actualizar Estado (PATCH /api/orders/{id}):</label>
                                <select id="edit-order-status" class="form-control">
                                    <option value="pending">Pendiente (pending)</option>
                                    <option value="processing">En Proceso (processing)</option>
                                    <option value="completed">Completado (completed)</option>
                                    <option value="cancelled">Cancelado (cancelled)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Notas Adicionales:</label>
                                <input type="text" id="edit-order-notes" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%;">
                                <i class="fa-solid fa-check"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabla General de Pedidos (GET ALL & DELETE) -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-list"></i> Lista de Pedidos en Neon DB (GET /api/orders)
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="loadOrders()">
                        <i class="fa-solid fa-rotate"></i> Refrescar
                    </button>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Ítems</th>
                                <th>Monto Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th style="text-align: right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body">
                            <tr><td colspan="7" style="text-align: center; color: var(--text-muted);">Cargando pedidos...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ================= TAB: PRODUCTOS ================= -->
        <section id="tab-products" class="tab-content">
            <div class="grid-2">
                <!-- Crear Producto -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-box"></i> Nuevo Producto (POST)
                        </div>
                    </div>
                    <form id="form-create-product" onsubmit="createProduct(event)">
                        <div class="form-group">
                            <label class="form-label">Nombre del Producto:</label>
                            <input type="text" id="prod-name" class="form-control" required placeholder="Ej. Audífonos Sony WH-1000XM5">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción:</label>
                            <input type="text" id="prod-desc" class="form-control" placeholder="Cancelación de ruido, 30h batería">
                        </div>
                        <div style="display: flex; gap: 1rem;">
                            <div class="form-group" style="flex: 1;">
                                <label class="form-label">Precio ($ USD):</label>
                                <input type="number" step="0.01" id="prod-price" class="form-control" required placeholder="349.99">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label class="form-label">Stock:</label>
                                <input type="number" id="prod-stock" class="form-control" required placeholder="10">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fa-solid fa-floppy-disk"></i> Registrar Producto
                        </button>
                    </form>
                </div>

                <!-- Lista de Productos -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-cubes"></i> Catálogo de Productos (GET /api/products)
                        </div>
                        <button class="btn btn-secondary btn-sm" onclick="loadProducts()">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body">
                                <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= TAB: USUARIOS ================= -->
        <section id="tab-users" class="tab-content">
            <div class="grid-2">
                <!-- Crear Usuario -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-user-plus"></i> Registrar Usuario (POST)
                        </div>
                    </div>
                    <form id="form-create-user" onsubmit="createUser(event)">
                        <div class="form-group">
                            <label class="form-label">Nombre Completo:</label>
                            <input type="text" id="user-name" class="form-control" required placeholder="Ej. Ana Morales">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Correo Electrónico:</label>
                            <input type="email" id="user-email" class="form-control" required placeholder="ana@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" id="user-password" class="form-control" required placeholder="******">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fa-solid fa-user-check"></i> Crear Usuario
                        </button>
                    </form>
                </div>

                <!-- Lista Usuarios -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-users-viewfinder"></i> Usuarios Registrados (GET /api/users)
                        </div>
                        <button class="btn btn-secondary btn-sm" onclick="loadUsers()">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Pedidos</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= TAB: CONSOLA ================= -->
        <section id="tab-console" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-terminal"></i> Última Respuesta HTTP del Servidor
                    </div>
                    <button class="btn btn-secondary btn-sm" onclick="clearConsole()">
                        <i class="fa-solid fa-eraser"></i> Limpiar
                    </button>
                </div>
                <div class="json-viewer" id="api-console">
// Realice cualquier acción en la interfaz para inspeccionar el JSON devuelto por Neon Cloud PostgreSQL.
                </div>
            </div>
        </section>
    </main>

    <!-- Notification Toast -->
    <div id="toast">
        <i id="toast-icon" class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
        <span id="toast-msg">Mensaje</span>
    </div>

    <!-- Footer -->
    <footer>
        Neon Serverless Postgres Cloud &bull; Laravel 12 REST API &bull; Frontend CRUD Interactivo
    </footer>

    <!-- Frontend Logic -->
    <script>
        const API_BASE = '/api';
        let cachedUsers = [];
        let cachedProducts = [];

        // Tab Switcher
        function switchTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            event.currentTarget.classList.add('active');
            document.getElementById(`tab-${tabId}`).classList.add('active');
        }

        // Notification Toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-msg');
            const toastIcon = document.getElementById('toast-icon');

            toast.className = `show ${type}`;
            toastMsg.textContent = message;
            toastIcon.className = type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation';

            setTimeout(() => {
                toast.className = '';
            }, 3500);
        }

        // Log to JSON Console
        function logResponse(data) {
            const consoleBox = document.getElementById('api-console');
            consoleBox.textContent = JSON.stringify(data, null, 2);
        }

        function clearConsole() {
            document.getElementById('api-console').textContent = '// Consola limpia.';
        }

        // ================= USERS =================
        async function loadUsers() {
            try {
                const res = await fetch(`${API_BASE}/users`);
                const json = await res.json();
                logResponse(json);

                if (json.success) {
                    cachedUsers = json.data;
                    document.getElementById('count-users').textContent = json.count;

                    // Poblar selector de crear pedido
                    const userSelect = document.getElementById('order-user-id');
                    userSelect.innerHTML = '<option value="">Selecciona un usuario...</option>';
                    cachedUsers.forEach(u => {
                        userSelect.innerHTML += `<option value="${u.id}">${u.name} (${u.email})</option>`;
                    });

                    // Poblar tabla de usuarios
                    const tbody = document.getElementById('users-table-body');
                    tbody.innerHTML = '';
                    cachedUsers.forEach(u => {
                        tbody.innerHTML += `
                            <tr>
                                <td>#${u.id}</td>
                                <td><strong>${u.name}</strong></td>
                                <td style="font-family: var(--font-mono); font-size: 0.85rem;">${u.email}</td>
                                <td><span class="status-badge status-completed">${u.orders_count || 0} pedidos</span></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${u.id})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (err) {
                showToast('Error al conectar con la API de usuarios', 'error');
            }
        }

        async function createUser(e) {
            e.preventDefault();
            const body = {
                name: document.getElementById('user-name').value,
                email: document.getElementById('user-email').value,
                password: document.getElementById('user-password').value,
            };

            try {
                const res = await fetch(`${API_BASE}/users`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });
                const json = await res.json();
                logResponse(json);

                if (res.ok && json.success) {
                    showToast('Usuario registrado exitosamente');
                    document.getElementById('form-create-user').reset();
                    loadUsers();
                } else {
                    showToast(json.message || 'Error de validación', 'error');
                }
            } catch (err) {
                showToast('Error en la petición POST', 'error');
            }
        }

        async function deleteUser(id) {
            if (!confirm(`¿Deseas eliminar al usuario #${id}? Se eliminarán sus pedidos en cascada.`)) return;

            try {
                const res = await fetch(`${API_BASE}/users/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                logResponse(json);

                if (res.ok) {
                    showToast('Usuario eliminado');
                    loadUsers();
                    loadOrders();
                } else {
                    showToast(json.message || 'No se pudo eliminar', 'error');
                }
            } catch (err) {
                showToast('Error al eliminar usuario', 'error');
            }
        }

        // ================= PRODUCTOS =================
        async function loadProducts() {
            try {
                const res = await fetch(`${API_BASE}/products`);
                const json = await res.json();
                logResponse(json);

                if (json.success) {
                    cachedProducts = json.data;
                    document.getElementById('count-products').textContent = json.count;

                    const tbody = document.getElementById('products-table-body');
                    tbody.innerHTML = '';
                    cachedProducts.forEach(p => {
                        tbody.innerHTML += `
                            <tr>
                                <td>#${p.id}</td>
                                <td><strong>${p.name}</strong></td>
                                <td class="price-tag">$${parseFloat(p.price).toFixed(2)}</td>
                                <td>${p.stock} unids.</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${p.id})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    // Actualizar las filas de ítems del pedido
                    refreshOrderItemSelects();
                }
            } catch (err) {
                showToast('Error al cargar catálogo de productos', 'error');
            }
        }

        async function createProduct(e) {
            e.preventDefault();
            const body = {
                name: document.getElementById('prod-name').value,
                description: document.getElementById('prod-desc').value,
                price: parseFloat(document.getElementById('prod-price').value),
                stock: parseInt(document.getElementById('prod-stock').value),
            };

            try {
                const res = await fetch(`${API_BASE}/products`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });
                const json = await res.json();
                logResponse(json);

                if (res.ok && json.success) {
                    showToast('Producto guardado');
                    document.getElementById('form-create-product').reset();
                    loadProducts();
                } else {
                    showToast(json.message || 'Error al crear producto', 'error');
                }
            } catch (err) {
                showToast('Error en la petición POST', 'error');
            }
        }

        async function deleteProduct(id) {
            if (!confirm(`¿Eliminar producto #${id}?`)) return;

            try {
                const res = await fetch(`${API_BASE}/products/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                logResponse(json);

                if (res.ok) {
                    showToast('Producto eliminado');
                    loadProducts();
                } else {
                    // Integridad referencial atrapada (409 Conflict)
                    showToast(json.message || 'Error de integridad referencial', 'error');
                }
            } catch (err) {
                showToast('Error al eliminar producto', 'error');
            }
        }

        // ================= PEDIDOS (ORDERS) =================
        function addOrderItemRow() {
            const container = document.getElementById('order-items-container');
            const row = document.createElement('div');
            row.className = 'order-item-row';
            
            let options = '<option value="">Elegir producto...</option>';
            cachedProducts.forEach(p => {
                options += `<option value="${p.id}">${p.name} ($${p.price})</option>`;
            });

            row.innerHTML = `
                <select class="form-control item-product-select" style="flex: 2;" required>
                    ${options}
                </select>
                <input type="number" class="form-control item-quantity-input" placeholder="Cant." min="1" value="1" style="flex: 1;" required>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            container.appendChild(row);
        }

        function refreshOrderItemSelects() {
            const container = document.getElementById('order-items-container');
            if (container.children.length === 0) {
                addOrderItemRow();
            }
        }

        async function loadOrders() {
            try {
                const res = await fetch(`${API_BASE}/orders`);
                const json = await res.json();
                logResponse(json);

                if (json.success) {
                    document.getElementById('count-orders').textContent = json.count;
                    const tbody = document.getElementById('orders-table-body');
                    tbody.innerHTML = '';

                    if (json.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-muted);">No hay pedidos en la base de datos.</td></tr>`;
                        return;
                    }

                    json.data.forEach(ord => {
                        const date = new Date(ord.created_at).toLocaleDateString();
                        const itemsCount = ord.order_items ? ord.order_items.length : 0;
                        const userLabel = ord.user ? ord.user.name : 'Usuario Desconocido';
                        
                        tbody.innerHTML += `
                            <tr>
                                <td><strong>#${ord.id}</strong></td>
                                <td>${userLabel}</td>
                                <td><span class="status-badge status-completed">${itemsCount} producto(s)</span></td>
                                <td class="price-tag">$${parseFloat(ord.total_amount).toFixed(2)}</td>
                                <td><span class="status-badge status-${ord.status}">${ord.status}</span></td>
                                <td style="color: var(--text-muted); font-size: 0.8rem;">${date}</td>
                                <td style="text-align: right;">
                                    <button class="btn btn-secondary btn-sm" onclick="viewOrderDetail(${ord.id})">
                                        <i class="fa-solid fa-eye"></i> Detalle
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteOrder(${ord.id})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (err) {
                showToast('Error al cargar pedidos de Neon DB', 'error');
            }
        }

        async function createOrder(e) {
            e.preventDefault();
            const userId = document.getElementById('order-user-id').value;
            const notes = document.getElementById('order-notes').value;

            const itemRows = document.querySelectorAll('.order-item-row');
            const items = [];
            itemRows.forEach(r => {
                const prodId = r.querySelector('.item-product-select').value;
                const qty = r.querySelector('.item-quantity-input').value;
                if (prodId && qty) {
                    items.push({ product_id: parseInt(prodId), quantity: parseInt(qty) });
                }
            });

            if (items.length === 0) {
                showToast('Debes seleccionar al menos un producto', 'error');
                return;
            }

            const body = {
                user_id: parseInt(userId),
                notes: notes,
                items: items
            };

            try {
                const res = await fetch(`${API_BASE}/orders`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });
                const json = await res.json();
                logResponse(json);

                if (res.ok && json.success) {
                    showToast('¡Pedido creado exitosamente en Neon Cloud!');
                    document.getElementById('form-create-order').reset();
                    document.getElementById('order-items-container').innerHTML = '';
                    addOrderItemRow();
                    loadOrders();
                    loadUsers();
                    viewOrderDetail(json.data.id);
                } else {
                    showToast(json.message || 'Error al procesar el pedido', 'error');
                }
            } catch (err) {
                showToast('Fallo de conexión al enviar el pedido', 'error');
            }
        }

        async function viewOrderDetail(id) {
            try {
                const res = await fetch(`${API_BASE}/orders/${id}`);
                const json = await res.json();
                logResponse(json);

                if (json.success) {
                    const order = json.data;
                    document.getElementById('order-detail-placeholder').style.display = 'none';
                    document.getElementById('order-detail-card').style.display = 'block';

                    document.getElementById('order-detail-badge').className = `status-badge status-${order.status}`;
                    document.getElementById('order-detail-badge').textContent = order.status;

                    document.getElementById('det-order-id').textContent = `#${order.id}`;
                    document.getElementById('det-user-name').textContent = order.user ? order.user.name : 'N/A';
                    document.getElementById('det-user-email').textContent = order.user ? order.user.email : 'N/A';
                    document.getElementById('det-total').textContent = `$${parseFloat(order.total_amount).toFixed(2)}`;

                    // Poblar formulario de edición PATCH
                    document.getElementById('edit-order-id').value = order.id;
                    document.getElementById('edit-order-status').value = order.status;
                    document.getElementById('edit-order-notes').value = order.notes || '';

                    // Lista de items
                    const itemsList = document.getElementById('det-items-list');
                    itemsList.innerHTML = '';
                    order.order_items.forEach(it => {
                        const prodName = it.product ? it.product.name : 'Producto';
                        itemsList.innerHTML += `
                            <li style="margin-bottom: 6px;">
                                <strong>${it.quantity}x</strong> ${prodName} 
                                &mdash; <span class="price-tag">$${parseFloat(it.subtotal).toFixed(2)}</span> 
                                <span style="color: var(--text-muted);">($${parseFloat(it.unit_price).toFixed(2)} c/u)</span>
                            </li>
                        `;
                    });

                    showToast(`Pedido #${order.id} cargado`);
                }
            } catch (err) {
                showToast('Error al obtener detalle del pedido', 'error');
            }
        }

        async function updateOrder(e) {
            e.preventDefault();
            const id = document.getElementById('edit-order-id').value;
            const body = {
                status: document.getElementById('edit-order-status').value,
                notes: document.getElementById('edit-order-notes').value,
            };

            try {
                const res = await fetch(`${API_BASE}/orders/${id}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });
                const json = await res.json();
                logResponse(json);

                if (res.ok && json.success) {
                    showToast('Estado de pedido actualizado');
                    loadOrders();
                    viewOrderDetail(id);
                } else {
                    showToast(json.message || 'Error al actualizar', 'error');
                }
            } catch (err) {
                showToast('Error en la petición PATCH', 'error');
            }
        }

        async function deleteOrder(id) {
            if (!confirm(`¿Deseas eliminar el pedido #${id}? Se eliminarán sus ítems en cascada manteniendo la integridad.`)) return;

            try {
                const res = await fetch(`${API_BASE}/orders/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                logResponse(json);

                if (res.ok) {
                    showToast('Pedido eliminado correctamente');
                    document.getElementById('order-detail-placeholder').style.display = 'block';
                    document.getElementById('order-detail-card').style.display = 'none';
                    document.getElementById('order-detail-badge').textContent = 'Seleccione uno';
                    loadOrders();
                    loadUsers();
                } else {
                    showToast(json.message || 'Error al eliminar', 'error');
                }
            } catch (err) {
                showToast('Error al eliminar el pedido', 'error');
            }
        }

        // Inicializar datos al cargar la página
        window.addEventListener('DOMContentLoaded', () => {
            loadUsers();
            loadProducts();
            loadOrders();
        });
    </script>
</body>
</html>
