<?php
/**
 * Configuración General del Sistema y Funciones de Utilidad
 * OrientaUneti - Plataforma de Bienestar Estudiantil
 */

define('APP_NAME', 'OrientaUneti');
define('APP_VERSION', '1.0.0-MVP');
define('INSTITUTION_NAME', 'Universidad Nacional Experimental de Telecomunicaciones e Informática');
define('INSTITUTION_ACRONYM', 'UNETI');
define('COLOR_PRIMARY', '#287A38'); // Verde UNETI
define('COLOR_SECONDARY', '#19319C'); // Azul UNETI

// Iniciar sesión segura si no está iniciada
function init_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', '1');
        }
        session_start();
    }
}

// Helpers de URL base y Assets compatibles con subdirectorios de Apache / XAMPP y raíz
function base_path(): string {
    static $base = null;
    if ($base === null) {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') {
            $base = '';
        } else {
            $base = rtrim($scriptDir, '/');
        }
    }
    return $base;
}

function url(string $path = ''): string {
    $base = base_path();
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string {
    $base = base_path();
    return $base . '/' . ltrim($path, '/');
}

// Generar o recuperar token CSRF
function csrf_token(): string {
    init_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Campo HTML input oculto para CSRF
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

// Validar token CSRF recibido por POST
function verify_csrf_token(): bool {
    init_session();
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        return false;
    }
    return true;
}

// Sanitizar cadenas para salida HTML
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Limpiar datos de entrada
function sanitize(string $data): string {
    return trim(strip_tags($data));
}

// Redireccionar con soporte para URL base
function redirect(string $path): void {
    if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
        $path = url($path);
    }
    header("Location: {$path}");
    exit;
}

// Mensajes Flash en sesión
function set_flash(string $type, string $message): void {
    init_session();
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message
    ];
}

function get_flash(): ?array {
    init_session();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Helpers de Autenticación
function auth_user(): ?array {
    init_session();
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
    init_session();
    return isset($_SESSION['user']['id']);
}

function is_admin(): bool {
    init_session();
    return isset($_SESSION['user']['rol_id']) && (int)$_SESSION['user']['rol_id'] === 1;
}

function is_student(): bool {
    init_session();
    return isset($_SESSION['user']['rol_id']) && (int)$_SESSION['user']['rol_id'] === 2;
}

// Formatear fechas en formato legible en español
function format_date(?string $date): string {
    if (!$date) return 'Sin fecha';
    $timestamp = strtotime($date);
    if (!$timestamp) return $date;
    
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    
    $diaSemana = $dias[date('w', $timestamp)];
    $dia = date('d', $timestamp);
    $mes = $meses[(int)date('m', $timestamp)];
    $anio = date('Y', $timestamp);
    
    return "{$dia} de {$mes} de {$anio}";
}
