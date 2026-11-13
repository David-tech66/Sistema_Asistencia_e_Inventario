<?php

namespace Database\Seeders;

use App\Models\Asistencia;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Configuracion;
use App\Models\Empleado;
use App\Models\MovimientoStock;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Simulación completa: llena todos los módulos del panel con datos de prueba
 * realistas (categorías, marcas, proveedores, clientes, productos con imágenes
 * descargadas de internet, empleados, asistencias, ventas y movimientos de stock).
 *
 * Uso:  php artisan db:seed --class=SimulacionSeeder
 * Idempotente: se puede ejecutar varias veces sin duplicar registros.
 */
class SimulacionSeeder extends Seeder
{
    private const DIAS_HISTORIAL = 45;

    private int $contadorBoleta = 0;

    private int $contadorFactura = 0;

    /** @var array<string, int> stock vigente por producto_id */
    private array $stock = [];

    public function run(): void
    {
        // Guard: si ya se ejecutó, no duplicamos ventas ni asistencias
        if (Configuracion::obtener('simulacion_ejecutada', '') === '1') {
            $this->command?->info('La simulación ya se ejecutó antes. No se duplican registros.');

            return;
        }

        // RNG determinista: la simulación sale igual en cada ejecución
        mt_srand(20261111);

        $this->command?->info('→ Categorías, marcas, proveedores y clientes...');
        $categorias = $this->seedCategorias();
        $marcas = $this->seedMarcas();
        $proveedores = $this->seedProveedores();
        $this->seedClientes();

        $this->command?->info('→ Usuarios y empleados...');
        $admin = $this->seedUsuarios();
        $this->seedEmpleados();

        $this->command?->info('→ Productos (descargando imágenes de internet)...');
        $productos = $this->seedProductos($categorias, $marcas, $proveedores);

        $this->command?->info('→ Asistencias del último ' . self::DIAS_HISTORIAL . ' días...');
        $this->seedAsistencias();

        $this->command?->info('→ Ventas, detalles y movimientos de stock...');
        $this->seedVentas($admin, $productos);

        $this->seedConfiguracion();

        $this->command?->info('✓ Simulación completada.');
    }

    // =========================================================
    //  CATEGORÍAS / MARCAS / PROVEEDORES / CLIENTES
    // =========================================================

    private function seedCategorias()
    {
        $datos = [
            ['Laptops', 'Notebooks y ultrabooks de trabajo y estudio'],
            ['Monitores', 'Pantallas de escritorio para oficina y gaming'],
            ['Teclados', 'Teclados mecánicos e inalámbricos'],
            ['Mouse', 'Mouse ópticos, inalámbricos y gaming'],
            ['Audífonos', 'Headsets, audífonos con y sin cable'],
            ['Telefonía', 'Smartphones y teléfonos celulares'],
            ['Tablets', 'Tabletas y dispositivos convertibles'],
            ['Impresoras', 'Impresoras láser, tinta continua y multifuncionales'],
            ['Redes', 'Routers, switches y puntos de acceso'],
            ['Almacenamiento', 'SSD, discos duros y memororias externas'],
            ['Componentes', 'RAM, fuentes, estabilizadores y periféricos internos'],
            ['Oficina', 'Mobiliario y accesorios de escritorio'],
        ];

        $coleccion = collect();
        foreach ($datos as [$nombre, $descripcion]) {
            $coleccion->push(Categoria::firstOrCreate(
                ['nombre_categoria' => $nombre],
                ['descripcion' => $descripcion]
            ));
        }

        return $coleccion;
    }

    private function seedMarcas()
    {
        $datos = [
            ['Lenovo', 'Equipos y noteboooks'],
            ['HP', 'Hewlett-Packard: impresoras y equipos'],
            ['Dell', 'Notebooks y monitores'],
            ['ASUS', 'Componentes y gaming'],
            ['Acer', 'Notebooks de gama media'],
            ['Apple', 'MacBook y accesorios'],
            ['Samsung', 'Pantallas, móviles y tablets'],
            ['LG', 'Monitores y televisores'],
            ['Logitech', 'Periféricos y accesorios'],
            ['Redragon', 'Periféricos gamer'],
            ['HyperX', 'Audífonos y teclados gamer'],
            ['Sony', 'Audio y video'],
            ['JBL', 'Audio portátil'],
            ['Xiaomi', 'Móviles, tablets y accesorios'],
            ['Epson', 'Impresoras y escáneres'],
            ['Canon', 'Impresoras y cámaras'],
            ['TP-Link', 'Equipos de red'],
            ['Kingston', 'Memorias y almacenamiento'],
            ['Western Digital', 'Discos duros y SSD'],
            ['APC', 'Protección de energía'],
        ];

        $coleccion = collect();
        foreach ($datos as [$nombre, $descripcion]) {
            $coleccion->push(Marca::firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $descripcion, 'estado' => true]
            ));
        }

        return $coleccion;
    }

    private function seedProveedores()
    {
        $datos = [
            ['Distribuidora Andina S.A.C.', '20512345678', '01 456 7890', 'ventas@andina.pe', 'Av. Arequipa 1245, Lince'],
            ['Importadora Tech Perú E.I.R.L.', '20601234567', '01 555 2211', 'contacto@techperu.com', 'Jr. Camaná 480, Cercado de Lima'],
            ['Soluciones Ofimáticas del Sur S.A.C.', '20459876543', '054 234 567', 'pedidos@ofisur.pe', 'Av. San Martín 910, Arequipa'],
            ['Redes y Conectividad Andina S.A.C.', '20554433221', '01 333 8877', 'soporte@redesandina.pe', 'Av. Brasil 450, Jesús María'],
            ['Insumos Computación Global E.I.R.L.', '20112233445', '01 222 4455', 'compras@globalcomp.pe', 'Av. Argentina 2150, Lince'],
        ];

        $coleccion = collect();
        foreach ($datos as [$razon, $ruc, $tel, $correo, $direccion]) {
            $coleccion->push(Proveedor::firstOrCreate(
                ['ruc' => $ruc],
                [
                    'razon_social' => $razon,
                    'telefono' => $tel,
                    'correo' => $correo,
                    'direccion' => $direccion,
                ]
            ));
        }

        return $coleccion;
    }

    private function seedClientes(): void
    {
        $datos = [
            ['00000000', 'Cliente General', ''],
            ['70451238', 'María Fernanda Rojas Quispe', '987654321'],
            ['41882937', 'Carlos Alberto Méndez Salas', '912345678'],
            ['45678912', 'Rosa Elena Paredes Linares', '934567890'],
            ['10456789', 'Jorge Luis Castillo Vega', '956789012'],
            ['20512345678', 'Comercial El Sol S.A.C.', '01 444 5566'],
            ['20609876543', 'Servicios Integrales Nova S.A.C.', '01 777 8899'],
            ['72345618', 'Lucía Camila Herrera Soto', '998877665'],
            ['46789123', 'Roberto Daniel Aguilar Fuentes', '965432109'],
            ['20456789012', 'Distribuciones del Pacífico E.I.R.L.', '054 778 899'],
            ['48912345', 'Ana Lucía Torres Béjar', '923456781'],
            ['73456129', 'Miguel Ángel Ramos Cordero', '945612378'],
        ];

        foreach ($datos as [$documento, $nombre, $telefono]) {
            Cliente::firstOrCreate(
                ['documento' => $documento],
                ['nombre' => $nombre, 'telefono' => $telefono]
            );
        }
    }

    // =========================================================
    //  USUARIOS Y EMPLEADOS
    // =========================================================

    private function seedUsuarios(): User
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@mdl.com'],
            [
                'nombre_usuario' => 'Administrador',
                'password' => Hash::make('password123'),
                'rol' => 'admin',
                'estado' => true,
            ]
        );

        $usuarios = [
            ['vendedor@mdl.com', 'Diego Ramírez', 'empleado'],
            ['cajero@mdl.com', 'Valentina Torres', 'empleado'],
            ['almacen@mdl.com', 'Andrés Molina', 'empleado'],
        ];

        foreach ($usuarios as [$email, $nombre, $rol]) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'nombre_usuario' => $nombre,
                    'password' => Hash::make('password123'),
                    'rol' => $rol,
                    'estado' => true,
                ]
            );
        }

        return $admin;
    }

    private function seedEmpleados(): void
    {
        $datos = [
            ['Juan Carlos Quispe Mamani', '70451238', 'Vendedor', '987111222', true],
            ['María Elena Torres Ramos', '41882937', 'Cajera', '987333444', true],
            ['Luis Alberto Fernández Gil', '45678912', 'Almacenista', '987555666', true],
            ['Rosa Milagros Chuquimayta', '10456789', 'Administrativa', '987777888', true],
            ['José Antonio Ríos Condori', '72345618', 'Técnico', '987999000', true],
            ['Carmen Gloria Espinoza Ruiz', '46789123', 'Supervisora', '987222333', true],
            ['Pedro Nelson Auccahuailla', '48912345', 'Repartidor', '987444555', true],
            ['Ana Lucía Vilca Ccahuana', '73456129', 'Vendedora', '987666777', true],
            ['Roberto Carlos Sánchez Luna', '74561238', 'Almacenista', '987888999', false],
        ];

        foreach ($datos as [$nombre, $dni, $cargo, $telefono, $estado]) {
            Empleado::firstOrCreate(
                ['dni' => $dni],
                [
                    'nombre_completo' => $nombre,
                    'cargo' => $cargo,
                    'telefono' => $telefono,
                    'estado' => $estado,
                ]
            );
        }
    }

    // =========================================================
    //  PRODUCTOS (con imágenes descargadas de internet)
    // =========================================================

    /**
     * @return \Illuminate\Support\Collection<int, Producto>
     */
    private function seedProductos($categorias, $marcas, $proveedores)
    {
        // [nombre, categoría, marca, proveedor, id Unsplash, garantía, stock objetivo, compra, venta, specs]
        $catalogo = [
            ['Lenovo IdeaPad 3 15ITL6', 'Laptops', 'Lenovo', 'Importadora Tech Perú E.I.R.L.', '1496181133206-80ce9b88a853', 12, 18, 1850.00, 2490.00, ['CPU' => 'Intel Core i5-1135G7', 'RAM' => '8 GB DDR4', 'SSD' => '512 GB NVMe', 'Pantalla' => '15.6" FHD']],
            ['HP Pavilion 14-dv2000', 'Laptops', 'HP', 'Importadora Tech Perú E.I.R.L.', '1517336714731-489689fd1ca8', 12, 12, 2150.00, 2890.00, ['CPU' => 'Intel Core i5-1235U', 'RAM' => '16 GB DDR4', 'SSD' => '512 GB NVMe', 'Pantalla' => '14" FHD IPS']],
            ['Dell Inspiron 15 3520', 'Laptops', 'Dell', 'Distribuidora Andina S.A.C.', '1541807084-5c52b6b3adef', 12, 15, 1780.00, 2390.00, ['CPU' => 'Intel Core i3-1215U', 'RAM' => '8 GB DDR4', 'SSD' => '256 GB NVMe', 'Pantalla' => '15.6" FHD']],
            ['ASUS VivoBook 15 X1504', 'Laptops', 'ASUS', 'Importadora Tech Perú E.I.R.L.', '1531297484001-80022131f5a1', 12, 10, 1990.00, 2690.00, ['CPU' => 'Intel Core i5-1135G7', 'RAM' => '8 GB DDR4', 'SSD' => '512 GB NVMe', 'Pantalla' => '15.6" FHD']],
            ['Acer Aspire 5 A515-57', 'Laptops', 'Acer', 'Distribuidora Andina S.A.C.', '1588872657578-7efd1f1555ed', 12, 9, 1720.00, 2350.00, ['CPU' => 'Intel Core i5-1335U', 'RAM' => '16 GB DDR4', 'SSD' => '512 GB NVMe', 'Pantalla' => '15.6" FHD']],
            ['Apple MacBook Air M2 13"', 'Laptops', 'Apple', 'Importadora Tech Perú E.I.R.L.', '1611186871348-b1ce696e52c9', 12, 6, 5200.00, 6790.00, ['CPU' => 'Apple M2', 'RAM' => '8 GB unificada', 'SSD' => '256 GB', 'Pantalla' => '13.6" Liquid Retina']],

            ['Samsung Odyssey G5 27"', 'Monitores', 'Samsung', 'Redes y Conectividad Andina S.A.C.', '1527443224154-c4a3942d3acf', 24, 14, 890.00, 1250.00, ['Tamaño' => '27" QHD', 'Refresh' => '165 Hz', 'Panel' => 'VA', 'Tiempo' => '1 ms']],
            ['LG UltraGear 24GN60R', 'Monitores', 'LG', 'Redes y Conectividad Andina S.A.C.', '1547394765-185e1e68f34e', 24, 11, 620.00, 890.00, ['Tamaño' => '24" FHD', 'Refresh' => '180 Hz', 'Panel' => 'IPS', 'Tiempo' => '1 ms']],
            ['Dell SE2422H 23.6"', 'Monitores', 'Dell', 'Distribuidora Andina S.A.C.', '1593642632823-8f785ba67e45', 36, 16, 430.00, 640.00, ['Tamaño' => '23.6" FHD', 'Refresh' => '75 Hz', 'Panel' => 'VA', 'Conexión' => 'HDMI / VGA']],
            ['HP M24h FHD', 'Monitores', 'HP', 'Distribuidora Andina S.A.C.', '1478720568477-152d9b164e26', 36, 13, 470.00, 690.00, ['Tamaño' => '23.8" FHD', 'Refresh' => '75 Hz', 'Panel' => 'IPS', 'Altura' => 'Ajustable']],

            ['Logitech MK270 Inalámbrico', 'Teclados', 'Logitech', 'Insumos Computación Global E.I.R.L.', '1541140532154-b024d705b90a', 12, 35, 95.00, 159.00, ['Tipo' => 'Inalámbrico 2.4 GHz', 'Batería' => '12 meses', 'Teclas' => 'Full size']],
            ['Redragon Kumara K552 RGB', 'Teclados', 'Redragon', 'Insumos Computación Global E.I.R.L.', '1618384887929-16ec33fab9ef', 12, 22, 145.00, 229.00, ['Tipo' => 'Mecánico', 'Switch' => 'Blue', 'RGB' => 'Sí']],
            ['HyperX Alloy Core RGB', 'Teclados', 'HyperX', 'Insumos Computación Global E.I.R.L.', '1587829741301-dc798b83add3', 12, 18, 175.00, 259.00, ['Tipo' => 'Membrana gamer', 'RGB' => 'Sí', 'USB' => 'Passthrough']],

            ['Logitech G203 Lightsync', 'Mouse', 'Logitech', 'Insumos Computación Global E.I.R.L.', '1527864550417-7fd91fc51a46', 12, 40, 78.00, 129.00, ['Sensor' => '8000 DPI', 'RGB' => 'Sí', 'Botones' => '6']],

            ['HyperX Cloud II', 'Audífonos', 'HyperX', 'Insumos Computación Global E.I.R.L.', '1505740420928-5e560c06d30e', 12, 20, 290.00, 429.00, ['Audio' => '7.1 virtual', 'Micrófono' => 'Desmontable', 'Driver' => '53 mm']],
            ['Sony WH-CH520', 'Audífonos', 'Sony', 'Distribuidora Andina S.A.C.', '1484704849700-f032a568e944', 12, 25, 210.00, 319.00, ['Tipo' => 'Inalámbrico', 'Batería' => '50 h', 'Carga' => 'USB-C']],
            ['JBL Tune 520BT', 'Audífonos', 'JBL', 'Distribuidora Andina S.A.C.', '1583394838336-acd977736f90', 12, 28, 165.00, 249.00, ['Tipo' => 'Inalámbrico', 'Batería' => '57 h', 'Pure Bass' => 'Sí']],

            ['Xiaomi Redmi Note 13', 'Telefonía', 'Xiaomi', 'Importadora Tech Perú E.I.R.L.', '1511707171634-5f897ff02aa9', 12, 24, 780.00, 999.00, ['Pantalla' => '6.67" AMOLED', 'Cámara' => '108 MP', 'RAM' => '8 GB', 'Batería' => '5000 mAh']],
            ['Samsung Galaxy A15', 'Telefonía', 'Samsung', 'Importadora Tech Perú E.I.R.L.', '1546054454-aa26e2b734c7', 12, 21, 690.00, 899.00, ['Pantalla' => '6.5" Super AMOLED', 'Cámara' => '50 MP', 'RAM' => '6 GB', 'Batería' => '5000 mAh']],

            ['Samsung Galaxy Tab A9', 'Tablets', 'Samsung', 'Importadora Tech Perú E.I.R.L.', '1544244015-0df4b3ffc6b0', 12, 12, 720.00, 990.00, ['Pantalla' => '8.7" TFT', 'RAM' => '4 GB', 'Almacenamiento' => '64 GB']],
            ['Lenovo Tab M11', 'Tablets', 'Lenovo', 'Importadora Tech Perú E.I.R.L.', '1561154464-82e9adf32764', 12, 10, 640.00, 879.00, ['Pantalla' => '11" IPS', 'RAM' => '8 GB', 'Almacenamiento' => '128 GB']],

            ['Epson L3250 WiFi', 'Impresoras', 'Epson', 'Soluciones Ofimáticas del Sur S.A.C.', '1612815154858-60aa4c59eaa6', 12, 14, 780.00, 1090.00, ['Tipo' => 'Tinta continua', 'Función' => 'Impresora / Escáner / Copia', 'Conexión' => 'WiFi']],
            ['HP LaserJet M110we', 'Impresoras', 'HP', 'Soluciones Ofimáticas del Sur S.A.C.', '1585771724684-38269d6639fd', 12, 11, 640.00, 899.00, ['Tipo' => 'Láser', 'Función' => 'Impresora', 'Conexión' => 'USB / WiFi']],
            ['Canon PIXMA G2411', 'Impresoras', 'Canon', 'Soluciones Ofimáticas del Sur S.A.C.', '1563770660941-20978e870e26', 12, 9, 690.00, 949.00, ['Tipo' => 'Tinta continua', 'Función' => 'Impresora / Escáner / Copia', 'Papel' => 'A4']],

            ['Logitech C920 HD Pro', 'Oficina', 'Logitech', 'Insumos Computación Global E.I.R.L.', '1587826080692-f439cd0b70da', 12, 17, 245.00, 359.00, ['Resolución' => '1080p 30 fps', 'Micrófono' => 'Estéreo', 'Enfoque' => 'Automático']],
            ['Xiaomi Mi Webcam 2K', 'Oficina', 'Xiaomi', 'Insumos Computación Global E.I.R.L.', '1625948515291-69613efd103f', 12, 19, 130.00, 199.00, ['Resolución' => '2K', 'Micrófono' => 'Dual', 'Enfoque' => 'Manual']],

            ['TP-Link Archer C6', 'Redes', 'TP-Link', 'Redes y Conectividad Andina S.A.C.', '1544197150-b99a580bb7a8', 24, 26, 145.00, 219.00, ['Velocidad' => 'AC1200', 'Bandas' => 'Doble banda', 'Puertos' => '4 LAN + 1 WAN']],
            ['TP-Link TL-WR840N', 'Redes', 'TP-Link', 'Redes y Conectividad Andina S.A.C.', '1606904825846-647eb07f5be2', 24, 30, 78.00, 129.00, ['Velocidad' => 'N300', 'Bandas' => 'Simple', 'Puertos' => '4 LAN + 1 WAN']],

            ['Kingston A400 SSD 480GB', 'Almacenamiento', 'Kingston', 'Insumos Computación Global E.I.R.L.', '1597872200969-2b65d56bd16b', 60, 45, 148.00, 219.00, ['Capacidad' => '480 GB', 'Interfaz' => 'SATA III', 'Lectura' => '500 MB/s']],
            ['WD Green SN350 1TB', 'Almacenamiento', 'Western Digital', 'Insumos Computación Global E.I.R.L.', '1591488320449-011701bb6704', 60, 23, 285.00, 419.00, ['Capacidad' => '1 TB', 'Interfaz' => 'NVMe', 'Lectura' => '3200 MB/s']],

            ['Kingston Fury Beast 16GB DDR4', 'Componentes', 'Kingston', 'Insumos Computación Global E.I.R.L.', '1562976540-1502c2145186', 60, 38, 135.00, 199.00, ['Capacidad' => '16 GB', 'Frecuencia' => '3200 MHz', 'Latencia' => 'CL16']],

            ['Silla ejecutiva ergonómica', 'Oficina', 'HP', 'Soluciones Ofimáticas del Sur S.A.C.', '1598550476439-6847785fcea6', 12, 15, 380.00, 590.00, ['Material' => 'Malla transpirable', 'Apoyabrazos' => 'Regulables', 'Respaldo' => 'Reclinable']],
            ['Escritorio 120 cm MDF', 'Oficina', 'HP', 'Soluciones Ofimáticas del Sur S.A.C.', '1580480055273-228ff5388ef8', 12, 12, 310.00, 480.00, ['Medidas' => '120 x 60 cm', 'Material' => 'MDF 15 mm', 'Soporte' => 'CPU']],

            ['No-break APC 1200VA', 'Componentes', 'APC', 'Redes y Conectividad Andina S.A.C.', '1558618666-fcd25c85cd64', 24, 17, 340.00, 490.00, ['Potencia' => '1200 VA', 'Tomas' => '4', 'Autonomía' => '~25 min']],
            ['Estabilizador 1500VA', 'Componentes', 'APC', 'Redes y Conectividad Andina S.A.C.', '1583863788434-e58a36330cf0', 12, 20, 155.00, 239.00, ['Potencia' => '1500 VA', 'Tomas' => '6', 'Protección' => 'Sobretensión']],

            ['Smart TV LG 50" 4K', 'Monitores', 'LG', 'Distribuidora Andina S.A.C.', '1593784991095-a205069470b6', 12, 8, 1450.00, 1990.00, ['Tamaño' => '50" UHD', 'Sistema' => 'webOS', 'Resolución' => '3840 x 2160']],
        ];

        $mapCat = $categorias->keyBy('nombre_categoria');
        $mapMarca = $marcas->keyBy('nombre');
        $mapProv = $proveedores->keyBy('razon_social');

        $coleccion = collect();

        foreach ($catalogo as $i => $item) {
            [$nombre, $cat, $marca, $prov, $unsplashId, $garantia, $stockObjetivo, $compra, $venta, $specs] = $item;

            $codigo = sprintf('MDL-%04d', $i + 1);
            $imagen = $this->descargarImagen($unsplashId, $this->slug($nombre));

            // El controlador guarda {"detalles": "texto libre"}; replicamos ese formato
            $textoSpecs = collect($specs)
                ->map(fn ($valor, $clave) => $clave . ': ' . $valor)
                ->implode(', ');

            $producto = Producto::firstOrCreate(
                ['codigo_qr' => $codigo],
                [
                    'categoria_id' => $mapCat[$cat]->id,
                    'marca_id' => $mapMarca[$marca]->id,
                    'proveedor_id' => $mapProv[$prov]->id,
                    'nombre' => $nombre,
                    'imagen' => $imagen,
                    'especificaciones' => json_encode(['detalles' => $textoSpecs], JSON_UNESCAPED_UNICODE),
                    'meses_garantia' => $garantia,
                    // Se ajusta después de generar las ventas
                    'stock_actual' => $stockObjetivo,
                    'stock_minimo' => 5,
                    'precio_compra' => $compra,
                    'precio_venta' => $venta,
                ]
            );

            $this->stock[$producto->id] = $stockObjetivo;
            $coleccion->push($producto);
        }

        return $coleccion;
    }

    /**
     * Descarga una imagen desde Unsplash y la guarda en storage/app/public/productos.
     * Devuelve la ruta relativa o null si no hay conexión.
     */
    private function descargarImagen(string $unsplashId, string $slug): ?string
    {
        $ruta = 'productos/' . $slug . '.jpg';
        $disco = Storage::disk('public');

        if ($disco->exists($ruta)) {
            return $ruta;
        }

        $url = "https://images.unsplash.com/photo-{$unsplashId}?w=700&h=700&fit=crop";
        $contexto = stream_context_create([
            'http' => ['timeout' => 20, 'user_agent' => 'Mozilla/5.0 (compatible; MDL-Seeder)'],
        ]);

        $datos = @file_get_contents($url, false, $contexto);

        if ($datos === false || strlen($datos) < 2000) {
            $this->command?->warn('  No se pudo descargar la imagen de ' . $slug);
            return null;
        }

        $disco->put($ruta, $datos);

        return $ruta;
    }

    private function slug(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
        return trim($texto, '-');
    }

    // =========================================================
    //  ASISTENCIAS
    // =========================================================

    private function seedAsistencias(): void
    {
        $empleados = Empleado::where('estado', true)->get();
        $horaEntrada = Configuracion::obtener('hora_entrada', '08:10');
        $horaSalida = Configuracion::obtener('hora_salida', '18:00');

        $creados = 0;

        for ($dia = self::DIAS_HISTORIAL; $dia >= 0; $dia--) {
            $fecha = Carbon::today()->subDays($dia);

            // Fin de semana sin asistencia
            if ($fecha->isWeekend()) {
                continue;
            }

            foreach ($empleados as $empleado) {
                $existe = Asistencia::where('empleado_id', $empleado->id)
                    ->where('fecha', $fecha->toDateString())
                    ->exists();

                if ($existe) {
                    continue;
                }

                // 6% de ausencias (no se crea registro)
                if (mt_rand(1, 100) <= 6) {
                    continue;
                }

                $tardanza = mt_rand(1, 100) <= 14;
                $entrada = $tardanza
                    ? $this->sumarMinutos($horaEntrada, mt_rand(12, 55))
                    : $this->sumarMinutos($horaEntrada, -mt_rand(0, 25));

                // Hoy solo registran quienes ya marcaron salida
                $esHoy = $fecha->isToday();
                $sinSalida = $esHoy && mt_rand(1, 100) <= 55;

                $salida = null;
                $salidaAnticipada = false;

                if (! $sinSalida) {
                    $anticipada = mt_rand(1, 100) <= 15;
                    $salidaAnticipada = $anticipada;
                    $salida = $anticipada
                        ? $this->restarMinutos($horaSalida, mt_rand(20, 90))
                        : $this->sumarMinutos($horaSalida, mt_rand(0, 30));
                }

                Asistencia::create([
                    'empleado_id' => $empleado->id,
                    'fecha' => $fecha->toDateString(),
                    'hora_entrada' => $entrada . ':00',
                    'hora_salida' => $salida !== null ? $salida . ':00' : null,
                    'estado' => $tardanza ? 'tardanza' : 'presente',
                    'salida_anticipada' => $salidaAnticipada,
                ]);

                $creados++;
            }
        }

        $this->command?->info("  {$creados} asistencias creadas.");
    }

    private function sumarMinutos(string $hora, int $minutos): string
    {
        return Carbon::parse($hora)->addMinutes($minutos)->format('H:i');
    }

    private function restarMinutos(string $hora, int $minutos): string
    {
        return Carbon::parse($hora)->subMinutes($minutos)->format('H:i');
    }

    // =========================================================
    //  VENTAS + MOVIMIENTOS DE STOCK
    // =========================================================

    private function seedVentas(User $admin, $productos): void
    {
        $clientes = Cliente::all();
        $vendedores = User::whereIn('email', ['admin@mdl.com', 'vendedor@mdl.com', 'cajero@mdl.com'])->get();

        $this->contadorBoleta = (int) Venta::where('tipo_comprobante', 'Boleta')->count();
        $this->contadorFactura = (int) Venta::where('tipo_comprobante', 'Factura')->count();

        // Cargar stock actual desde la BD (por si ya hay ventas previas)
        foreach ($productos as $producto) {
            $this->stock[$producto->id] = (int) $producto->stock_actual;
        }

        $creadas = 0;

        for ($dia = self::DIAS_HISTORIAL; $dia >= 0; $dia--) {
            $fecha = Carbon::today()->subDays($dia);

            if ($fecha->isWeekend()) {
                continue;
            }

            // Entre 2 y 7 ventas por día
            $cantidadVentas = mt_rand(2, 7);

            for ($v = 0; $v < $cantidadVentas; $v++) {
                $venta = $this->crearVenta($fecha, $clientes, $vendedores, $productos, $admin);

                if ($venta !== null) {
                    $creadas++;
                }
            }
        }

        $this->command?->info("  {$creadas} ventas creadas.");

        // Persistir el stock resultante de todas las ventas
        foreach ($this->stock as $productoId => $stockFinal) {
            Producto::where('id', $productoId)->update(['stock_actual' => $stockFinal]);
        }
    }

    private function crearVenta(Carbon $fecha, $clientes, $vendedores, $productos, User $admin): ?Venta
    {
        // Entre 1 y 3 productos por venta
        $lineas = mt_rand(1, 3);
        $elegidos = $productos->random($lineas);

        if ($elegidos->count() === 0) {
            return null;
        }

        // Calcular cantidades respetando el stock disponible
        $detalles = [];
        $total = 0;

        foreach ($elegidos as $producto) {
            $disponible = $this->stock[$producto->id] ?? 0;

            if ($disponible <= 0) {
                continue;
            }

            $cantidad = min(mt_rand(1, 3), $disponible);

            if ($cantidad <= 0) {
                continue;
            }

            $subtotal = round($producto->precio_venta * $cantidad, 2);
            $total += $subtotal;

            $detalles[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'precio' => $producto->precio_venta,
                'subtotal' => $subtotal,
            ];
        }

        if ($detalles === []) {
            return null;
        }

        $esFactura = mt_rand(1, 100) <= 30;

        if ($esFactura) {
            $this->contadorFactura++;
            $numero = 'F001-' . str_pad($this->contadorFactura, 6, '0', STR_PAD_LEFT);
            $tipo = 'Factura';
        } else {
            $this->contadorBoleta++;
            $numero = 'B001-' . str_pad($this->contadorBoleta, 6, '0', STR_PAD_LEFT);
            $tipo = 'Boleta';
        }

        $venta = self::crearConFecha(Venta::class, [
            'cliente_id' => $clientes->random()->id,
            'user_id' => ($vendedores->isNotEmpty() ? $vendedores->random() : $admin)->id,
            'tipo_comprobante' => $tipo,
            'numero_comprobante' => $numero,
            'total' => round($total, 2),
        ], $fecha->copy()->setTime(mt_rand(9, 19), mt_rand(0, 59)));

        foreach ($detalles as $detalle) {
            $producto = $detalle['producto'];

            $this->stock[$producto->id] -= $detalle['cantidad'];

            $venta->detalles()->create([
                'producto_id' => $producto->id,
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio'],
                'subtotal' => $detalle['subtotal'],
            ]);

            // Trazabilidad: cada venta genera una salida de stock
            self::crearConFecha(MovimientoStock::class, [
                'producto_id' => $producto->id,
                'user_id' => $venta->user_id,
                'tipo' => 'salida',
                'cantidad' => $detalle['cantidad'],
                'motivo' => 'Venta ' . $numero,
                'stock_resultante' => $this->stock[$producto->id],
            ], $venta->created_at);
        }

        return $venta;
    }

    /**
     * Crea un modelo con una fecha de registro personalizada.
     * created_at/updated_at no son mass-assignable, por eso se asignan a mano
     * y se desactivan los timestamps automáticos durante el guardado.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $clase
     */
    private static function crearConFecha(string $clase, array $atributos, Carbon $fecha): \Illuminate\Database\Eloquent\Model
    {
        return $clase::withoutTimestamps(function () use ($clase, $atributos, $fecha) {
            /** @var \Illuminate\Database\Eloquent\Model $modelo */
            $modelo = new $clase($atributos);
            $modelo->created_at = $fecha;
            $modelo->updated_at = $fecha;
            $modelo->save();

            return $modelo;
        });
    }

    // =========================================================
    //  CONFIGURACIÓN
    // =========================================================

    private function seedConfiguracion(): void
    {
        Configuracion::guardar('hora_entrada', Configuracion::obtener('hora_entrada', '08:10'));
        Configuracion::guardar('hora_salida', Configuracion::obtener('hora_salida', '18:00'));

        // Solo los si no existen aún, no pisar lo que el admin ya configuró
        if (Configuracion::obtener('tolerancia_tardanza', '') === '') {
            Configuracion::guardar('tolerancia_tardanza', '5');
        }

        if (Configuracion::obtener('stock_minimo_defecto', '') === '') {
            Configuracion::guardar('stock_minimo_defecto', '5');
        }

        Configuracion::guardar('simulacion_ejecutada', '1');
    }
}
