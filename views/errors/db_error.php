<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Base de Datos | OrientaUneti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-uneti text-white p-4 text-center rounded-top-4">
                    <i class="bi bi-database-fill-gear fs-1"></i>
                    <h3 class="fw-bold mt-2 mb-0">Conexión a la Base de Datos</h3>
                    <p class="mb-0 text-white-50">Plataforma de Bienestar Estudiantil OrientaUneti</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="alert alert-warning d-flex align-items-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-warning"></i>
                        <div>
                            <strong>No se pudo establecer conexión con PostgreSQL.</strong><br>
                            El sistema requiere una base de datos PostgreSQL activa para funcionar correctamente.
                        </div>
                    </div>

                    <h5 class="fw-bold text-uneti-blue mb-3">Pasos para conectar:</h5>
                    <ol class="list-group list-group-numbered mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Crear archivo <code>.env</code></div>
                                Copie el archivo <code>.env.example</code> a <code>.env</code> en la raíz del proyecto y configure sus credenciales de PostgreSQL.
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Activar extensión en XAMPP / PHP</div>
                                En su archivo <code>php.ini</code>, asegúrese de descomentar la línea:<br>
                                <code>extension=pdo_pgsql</code>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Ejecutar el instalador / migración</div>
                                Puede inicializar las tablas y datos demo ejecutando <code>setup.php</code> en su navegador o mediante línea de comandos.
                            </div>
                        </li>
                    </ol>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="/setup.php" class="btn btn-uneti">
                            <i class="bi bi-arrow-repeat me-1"></i> Abrir Asistente de Instalación
                        </a>
                        <a href="javascript:location.reload()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reintentar Conexión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
