<?php
declare(strict_types=1);

// /nucleo/Datos.php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../config.php';

/**
 * Vacía una tabla de forma segura (MySQL).
 * Nota: TRUNCATE hace commit implícito; por eso debe ejecutarse fuera de una transacción.
 */
function resetTabla(PDO $pdo, string $tabla): void
{
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec("TRUNCATE TABLE `{$tabla}`");
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

/**
 * 🌱 Inserta productos de prueba.
 * - Si $reset = true, vacía la tabla antes de insertar.
 * - Devuelve el número aproximado de filas afectadas.
 */
function semillaProductosDatos(bool $reset = false): int
{
    $pdo = Database::getConnection();
    $afectadas = 0;

    $productos = [
        ['producto' => 'Pan de Camas',                   'precio' => 1.20, 'stock' => 50,  'descripcion' => 'Pan artesano de la localidad de Camas'],
        ['producto' => 'Aceitunas aliñadas de Camas',    'precio' => 2.50, 'stock' => 30,  'descripcion' => 'Aceitunas verdes aliñadas con especias tradicionales'],
        ['producto' => 'Tortas de aceite',               'precio' => 3.00, 'stock' => 40,  'descripcion' => 'Tortas crujientes elaboradas con aceite de oliva'],
        ['producto' => 'Aceite Virgen Extra “Aljarafe”', 'precio' => 6.80, 'stock' => 20,  'descripcion' => 'Aceite de oliva virgen extra de la comarca del Aljarafe'],
        ['producto' => 'Jamón ibérico de recebo',        'precio' => 12.50,'stock' => 15,  'descripcion' => 'Jamón ibérico curado de recebo, sabor intenso'],
        ['producto' => 'Queso de cabra payoya',          'precio' => 4.75, 'stock' => 25,  'descripcion' => 'Queso artesanal de cabra payoya'],
        ['producto' => 'Miel de azahar del Aljarafe',    'precio' => 5.20, 'stock' => 35,  'descripcion' => 'Miel pura de azahar recolectada en Aljarafe'],
        ['producto' => 'Almendras fritas estilo barra',  'precio' => 3.40, 'stock' => 45,  'descripcion' => 'Almendras fritas con sal, crujientes'],
        ['producto' => 'Bollos de anís tradicionales',   'precio' => 2.30, 'stock' => 60,  'descripcion' => 'Bollos artesanos de anís, receta tradicional'],
        ['producto' => 'Paté de aceituna verde',         'precio' => 3.10, 'stock' => 40,  'descripcion' => 'Paté untuoso de aceitunas verdes'],
        ['producto' => 'Vino blanco DO “Aljarafe”',      'precio' => 8.50, 'stock' => 25,  'descripcion' => 'Vino blanco con Denominación de Origen Aljarafe'],
        ['producto' => 'Dulce de membrillo artesano',    'precio' => 2.90, 'stock' => 30,  'descripcion' => 'Membrillo artesanal, receta tradicional'],
        ['producto' => 'Anchoas en aceite de oliva',     'precio' => 7.20, 'stock' => 20,  'descripcion' => 'Anchoas de calidad en aceite de oliva'],
        ['producto' => 'Chorizo casero del Aljarafe',    'precio' => 4.60, 'stock' => 35,  'descripcion' => 'Chorizo artesanal con especias típicas'],
        ['producto' => 'Flor de sal del Guadalquivir',   'precio' => 2.70, 'stock' => 50,  'descripcion' => 'Sal gourmet recolectada del río Guadalquivir'],
        ['producto' => 'Mermelada de higo de la zona',   'precio' => 3.30, 'stock' => 40,  'descripcion' => 'Mermelada artesanal de higo local'],
        ['producto' => 'Cervezas artesanas sevillanas',  'precio' => 2.80, 'stock' => 60,  'descripcion' => 'Cerveza artesana producida en Sevilla'],
        ['producto' => 'Tomate seco en aceite',          'precio' => 4.20, 'stock' => 25,  'descripcion' => 'Tomate seco conservado en aceite de oliva'],
        ['producto' => 'Aceite arbequina 250 ml',        'precio' => 5.60, 'stock' => 30,  'descripcion' => 'Aceite de oliva arbequina en botella de 250 ml'],
        ['producto' => 'Picos de pan artesanos',         'precio' => 1.80, 'stock' => 70,  'descripcion' => 'Picos de pan crujientes, perfectos para tapas'],
    ];


    // Si vas a resetear, hazlo SIEMPRE fuera de la transacción
    if ($reset) {
        resetTabla($pdo, 'productos');
    }

    $sql = "INSERT INTO productos (nombre, precio, stock, descripcion) VALUES (:nombre, :precio, :stock, :descripcion)";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        foreach ($productos as $p) {
            $stmt->execute([
                ':nombre' => (string)($p['producto'] ?? ''),
                ':precio' => (float)($p['precio'] ?? 0.0),
                ':stock' => (int)($p['stock'] ?? 0),
                ':descripcion' => (string)($p['descripcion' ?? ''])
            ]);
            $afectadas += $stmt->rowCount();
        }

        $pdo->commit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Seed productos error: ' . $e->getMessage());
        return 0;
    }

    return $afectadas;
}

/**
 * 🌱 Inserta usuarios de prueba.
 * - Si $reset = true, vacía la tabla antes de insertar.
 * - Devuelve el número aproximado de filas afectadas.
 */
function seedUsuariosDatos(bool $reset = false): int
{
    $pdo = Database::getConnection();
    $afectadas = 0;

    $usuarios = [
        ['admin',    'admin123', 'Administrador General', 'admin'],
        ['manager1', 'manager1', 'Laura Gestora',         'manager'],
        ['manager2', 'manager2', 'Carlos Supervisor',     'manager'],
        ['user1',    'user1',    'María Compradora',      'usuario'],
        ['user2',    'user2',    'Pedro Cliente',         'usuario'],
        ['user3',    'user3',    'Lucía Compradora',      'usuario'],
        ['user4',    'user4',    'Manuel Perez',          'usuario'],
        ['user5',    'user5',    'Tess test',          'usuario'],
    ];

    if ($reset) {
        resetTabla($pdo, 'usuarios');
    }

    $sql = "INSERT INTO usuarios (usuario, password, nombre, rol)
            VALUES (:usuario, :password, :nombre, :rol)";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        foreach ($usuarios as [$usuario, $clave, $nombre, $rol]) {
            $stmt->execute([
                ':usuario'  => $usuario,
                ':password' => password_hash($clave, PASSWORD_DEFAULT),
                ':nombre'   => $nombre,
                ':rol'      => $rol,
            ]);
            $afectadas += $stmt->rowCount();
        }

        $pdo->commit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Seed usuarios error: ' . $e->getMessage());
        return 0;
    }

    return $afectadas;
}
