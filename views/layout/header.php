<?php
require_once __DIR__ . '/../../config/config.php';
$currentUser = auth_user();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?><?= APP_NAME ?> - UNETI</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= asset('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<!-- Barra de Navegación Principal -->
<nav class="navbar navbar-expand-xl navbar-uneti sticky-top">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center" href="<?= url('/') ?>">
            <span class="p-2 rounded-3 me-2 text-white bg-gradient-uneti shadow-sm" style="font-size: 1.1rem; line-height: 1;">
                <i class="bi bi-compass-fill"></i>
            </span>
            <span>Orienta<span style="color: var(--uneti-blue);">Uneti</span></span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav navbar-main-links flex-grow-1 mb-1 mb-xl-0 ms-xl-3">
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/') ?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/actividades') ?>">Actividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/salud-mental') ?>">Salud Mental</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/emprendimientos') ?>">Emprendimientos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/contacto') ?>">Atención al Usuario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/#sobre-nosotros') ?>">Sobre Nosotros</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if ($currentUser): ?>
                    <?php if (is_admin()): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-uneti-blue btn-sm" href="<?= url('/admin/dashboard') ?>">
                                <i class="bi bi-speedometer2 me-1"></i> Panel Admin
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-2">
                            <a class="nav-link text-uneti-green fw-bold" href="<?= url('/estudiante/mis-actividades') ?>">
                                Mis Actividades
                            </a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="nav-link" href="<?= url('/estudiante/perfil') ?>">
                                Mi Perfil
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="badge bg-secondary me-2"><?= is_admin() ? 'Admin' : 'Estudiante' ?></span>
                            <?= e($currentUser['nombre']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><span class="dropdown-item-text small text-muted"><?= e($currentUser['email']) ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (is_student()): ?>
                                <li><a class="dropdown-item" href="<?= url('/estudiante/dashboard') ?>"><i class="bi bi-grid me-2"></i> Mi Tablero</a></li>
                                <li><a class="dropdown-item" href="<?= url('/estudiante/perfil') ?>"><i class="bi bi-gear me-2"></i> Editar Perfil</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?= url('/admin/reportes') ?>"><i class="bi bi-file-earmark-spreadsheet me-2"></i> Reportes y Asistencia</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="<?= url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="<?= url('/login') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-uneti btn-sm shadow-sm" href="<?= url('/registro') ?>">
                            <i class="bi bi-person-plus me-1"></i> Registrarse
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenedor de Alertas Flash -->
<?php require __DIR__ . '/alerts.php'; ?>

<main>
