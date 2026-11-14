<?php
/**
 * Instalador y Migrador de Base de Datos
 * OrientaUneti - Compatible con PostgreSQL y MySQL (XAMPP)
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$mensaje = '';
$error = '';
$instalado = false;
$driverUsado = 'mysql';

$isCli = (php_sapi_name() === 'cli');
$isPost = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';

if ($isPost || $isCli) {
    try {
        // Si el usuario eligió un motor específico en el formulario o CLI
        if (isset($_POST['driver'])) {
            putenv("DB_DRIVER=" . $_POST['driver']);
            $_ENV['DB_DRIVER'] = $_POST['driver'];
        }

        // Obtener conexión
        $db = Database::getConnection();
        $driverUsado = Database::getActiveDriver();

        if ($driverUsado === 'mysql') {
            // Asegurar que la base de datos existe
            $db->exec("CREATE DATABASE IF NOT EXISTS orientauneti CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $db->exec("USE orientauneti;");

            $schemaFile = __DIR__ . '/../database/schema_mysql.sql';
            $seedFile   = __DIR__ . '/../database/seed_mysql.sql';
        } else {
            $schemaFile = __DIR__ . '/../database/schema.sql';
            $seedFile   = __DIR__ . '/../database/seed.sql';
        }

        if (!file_exists($schemaFile)) {
            throw new Exception("No se encontró el archivo de esquema: " . basename($schemaFile));
        }

        // 1. Ejecutar DDL
        $schemaSql = file_get_contents($schemaFile);
        $db->exec($schemaSql);

        // 2. Insertar usuarios con Hash Bcrypt verificado
        $adminPass = password_hash('Admin123*', PASSWORD_BCRYPT);
        $studentPass = password_hash('Estudiante123*', PASSWORD_BCRYPT);

        if ($driverUsado === 'mysql') {
            $stmtAdmin = $db->prepare("
                INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria)
                VALUES (1, 'V-10234567', 'Administrador', 'OrientaUneti', 'admin@uneti.edu.ve', :pass, '0414-1234567', 'Dirección de Bienestar Estudiantil')
                ON DUPLICATE KEY UPDATE password = :pass_up
            ");
            $stmtAdmin->execute([':pass' => $adminPass, ':pass_up' => $adminPass]);

            $stmtEst1 = $db->prepare("
                INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria)
                VALUES (2, 'V-28123456', 'Carlos', 'Mendoza', 'estudiante@uneti.edu.ve', :pass, '0412-9876543', 'PNF Informática - Trayecto II')
                ON DUPLICATE KEY UPDATE password = :pass_up
            ");
            $stmtEst1->execute([':pass' => $studentPass, ':pass_up' => $studentPass]);

            $stmtEst2 = $db->prepare("
                INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria)
                VALUES (2, 'V-27891234', 'Mariana', 'Silva', 'mariana.silva@uneti.edu.ve', :pass, '0424-5551234', 'PNF Telecomunicaciones - Trayecto III')
                ON DUPLICATE KEY UPDATE password = :pass_up
            ");
            $stmtEst2->execute([':pass' => $studentPass, ':pass_up' => $studentPass]);
        } else {
            $stmtAdmin = $db->prepare("
                INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria)
                VALUES (1, 'V-10234567', 'Administrador', 'OrientaUneti', 'admin@uneti.edu.ve', :pass, '0414-1234567', 'Dirección de Bienestar Estudiantil')
                ON CONFLICT (email) DO UPDATE SET password = :pass_up
            ");
            $stmtAdmin->execute([':pass' => $adminPass, ':pass_up' => $adminPass]);

            $stmtEst1 = $db->prepare("
                INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria)
                VALUES (2, 'V-28123456', 'Carlos', 'Mendoza', 'estudiante@uneti.edu.ve', :pass, '0412-9876543', 'PNF Informática - Trayecto II')
                ON CONFLICT (email) DO UPDATE SET password = :pass_up
            ");
            $stmtEst1->execute([':pass' => $studentPass, ':pass_up' => $studentPass]);

            $stmtEst2 = $db->prepare("
                INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria)
                VALUES (2, 'V-27891234', 'Mariana', 'Silva', 'mariana.silva@uneti.edu.ve', :pass, '0424-5551234', 'PNF Telecomunicaciones - Trayecto III')
                ON CONFLICT (email) DO UPDATE SET password = :pass_up
            ");
            $stmtEst2->execute([':pass' => $studentPass, ':pass_up' => $studentPass]);
        }

        // 3. Ejecutar Semilla (Catálogo inicial)
        if (file_exists($seedFile)) {
            $seedSql = file_get_contents($seedFile);
            try {
                $db->exec($seedSql);
            } catch (Exception $eSeed) {
                // Continuar si ya existían registros
            }
        }

        $mensaje = "¡Base de datos instalada y poblada exitosamente en " . strtoupper($driverUsado) . "!";
        $instalado = true;

        if ($isCli) {
            echo "OK: Base de datos instalada correctamente usando motor " . strtoupper($driverUsado) . ".\n";
            exit(0);
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
        if ($isCli) {
            echo "ERROR: " . $error . "\n";
            exit(1);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador de Base de Datos | OrientaUneti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-uneti text-white p-4 text-center rounded-top-4">
                    <i class="bi bi-cpu fs-1"></i>
                    <h3 class="fw-bold mt-2 mb-0">Instalador del Sistema OrientaUneti</h3>
                    <p class="mb-0 text-white-50">Configuración automática de tablas y datos semilla</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if ($mensaje): ?>
                        <div class="alert alert-success d-flex align-items-center mb-4">
                            <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                            <div>
                                <strong><?= e($mensaje) ?></strong><br>
                                Las tablas, roles, usuarios de prueba y catálogo han sido inicializados.
                            </div>
                        </div>

                        <div class="card bg-light border p-3 mb-4">
                            <h6 class="fw-bold text-dark mb-2">Credenciales listas para usar:</h6>
                            <div class="small mb-1">
                                <strong>Administrador:</strong> <code>admin@uneti.edu.ve</code> | Clave: <code>Admin123*</code>
                            </div>
                            <div class="small">
                                <strong>Estudiante:</strong> <code>estudiante@uneti.edu.ve</code> | Clave: <code>Estudiante123*</code>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="/login" class="btn btn-uneti btn-lg">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Ir al Inicio de Sesión
                            </a>
                        </div>

                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                                <div>
                                    <strong>Error al conectar o ejecutar:</strong><br>
                                    <?= e($error) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <p class="text-muted">
                            Este asistente creará automáticamente las tablas del sistema (roles, usuarios, actividades, inscripciones, salud mental, emprendimientos y contactos) e insertará los datos iniciales con control de cupos.
                        </p>

                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Seleccione el motor de base de datos a usar:</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="card p-3 border">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="driver" id="driverMysql" value="mysql" checked>
                                                <label class="form-check-label fw-semibold" for="driverMysql">
                                                    <i class="bi bi-hdd-network text-success me-1"></i> MySQL / XAMPP (Local)
                                                </label>
                                                <small class="text-muted d-block mt-1">Activo en puerto 3306. Listo sin descargas extras.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card p-3 border">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="driver" id="driverPgsql" value="pgsql">
                                                <label class="form-check-label fw-semibold" for="driverPgsql">
                                                    <i class="bi bi-database text-primary me-1"></i> PostgreSQL / Render
                                                </label>
                                                <small class="text-muted d-block mt-1">Puerto 5432 o Render DATABASE_URL.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-uneti w-100 py-3 fw-bold shadow-sm">
                                <i class="bi bi-play-circle-fill me-1"></i> Iniciar Instalación / Migración
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
