<?php
/**
 * Controlador de Autenticación
 * Maneja login, registro seguro con hash de contraseñas y cierre de sesión
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    private Usuario $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function showLogin(): void {
        init_session();
        if (is_logged_in()) {
            redirect(is_admin() ? '/admin/dashboard' : '/estudiante/dashboard');
        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Error de validación del token de seguridad (CSRF). Por favor recargue la página.');
            redirect('/login');
        }

        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            set_flash('danger', 'Por favor complete todos los campos.');
            redirect('/login');
        }

        $user = $this->usuarioModel->findByEmail($email);

        if (!$user) {
            set_flash('danger', 'Credenciales incorrectas. Verifique su correo o regístrese.');
            redirect('/login');
        }

        if (!password_verify($password, $user['password'])) {
            // Nota de compatibilidad para usuarios de prueba iniciales si la clave se ingresó en texto plano en la migración
            if ($user['password'] === $password) {
                // Actualizar automáticamente al hash seguro de PHP
                $this->usuarioModel->updateProfile((int)$user['id'], [
                    'nombre'      => $user['nombre'],
                    'apellido'    => $user['apellido'],
                    'telefono'    => $user['telefono'] ?? '',
                    'trayectoria' => $user['trayectoria'] ?? '',
                    'password'    => $password
                ]);
            } else {
                set_flash('danger', 'Credenciales incorrectas. Verifique su contraseña.');
                redirect('/login');
            }
        }

        if (isset($user['activo']) && !$user['activo']) {
            set_flash('warning', 'Su cuenta se encuentra inactiva. Contacte a la Dirección de Bienestar Estudiantil.');
            redirect('/login');
        }

        // Establecer sesión segura
        init_session();
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'          => (int)$user['id'],
            'rol_id'      => (int)$user['rol_id'],
            'cedula'      => $user['cedula'],
            'nombre'      => $user['nombre'],
            'apellido'    => $user['apellido'],
            'email'       => $user['email'],
            'telefono'    => $user['telefono'] ?? '',
            'trayectoria' => $user['trayectoria'] ?? ''
        ];

        set_flash('success', '¡Bienvenido(a) a OrientaUneti, ' . e($user['nombre']) . '!');

        if ((int)$user['rol_id'] === 1) {
            redirect('/admin/dashboard');
        } else {
            redirect('/estudiante/dashboard');
        }
    }

    public function showRegister(): void {
        init_session();
        if (is_logged_in()) {
            redirect(is_admin() ? '/admin/dashboard' : '/estudiante/dashboard');
        }
        require_once __DIR__ . '/../../views/auth/register.php';
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/registro');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Error de seguridad en la solicitud. Intente de nuevo.');
            redirect('/registro');
        }

        $cedula      = strtoupper(trim($_POST['cedula'] ?? ''));
        $nombre      = trim($_POST['nombre'] ?? '');
        $apellido    = trim($_POST['apellido'] ?? '');
        $email       = strtolower(trim($_POST['email'] ?? ''));
        $password    = $_POST['password'] ?? '';
        $password_c  = $_POST['password_confirm'] ?? '';
        $telefono    = trim($_POST['telefono'] ?? '');
        $trayectoria = trim($_POST['trayectoria'] ?? '');

        // Validaciones básicas
        if (empty($cedula) || empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
            set_flash('danger', 'Todos los campos obligatorios deben ser completados.');
            redirect('/registro');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('danger', 'El formato del correo electrónico no es válido.');
            redirect('/registro');
        }

        if (strlen($password) < 6) {
            set_flash('danger', 'La contraseña debe contener al menos 6 caracteres.');
            redirect('/registro');
        }

        if ($password !== $password_c) {
            set_flash('danger', 'Las contraseñas ingresadas no coinciden.');
            redirect('/registro');
        }

        // Verificar duplicados
        if ($this->usuarioModel->findByEmail($email)) {
            set_flash('danger', 'El correo ya se encuentra registrado en el sistema.');
            redirect('/registro');
        }

        if ($this->usuarioModel->findByCedula($cedula)) {
            set_flash('danger', 'La cédula de identidad ya se encuentra registrada.');
            redirect('/registro');
        }

        // Crear estudiante (rol_id = 2)
        $nuevoId = $this->usuarioModel->create([
            'rol_id'      => 2,
            'cedula'      => $cedula,
            'nombre'      => $nombre,
            'apellido'    => $apellido,
            'email'       => $email,
            'password'    => $password,
            'telefono'    => $telefono,
            'trayectoria' => $trayectoria
        ]);

        if ($nuevoId > 0) {
            init_session();
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'          => $nuevoId,
                'rol_id'      => 2,
                'cedula'      => $cedula,
                'nombre'      => $nombre,
                'apellido'    => $apellido,
                'email'       => $email,
                'telefono'    => $telefono,
                'trayectoria' => $trayectoria
            ];

            set_flash('success', '¡Registro exitoso! Bienvenido(a) al Portal de Bienestar OrientaUneti.');
            redirect('/estudiante/dashboard');
        } else {
            set_flash('danger', 'Ocurrió un error al registrar el usuario. Inténtelo más tarde.');
            redirect('/registro');
        }
    }

    public function logout(): void {
        init_session();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_start();
        set_flash('info', 'Has cerrado sesión correctamente.');
        redirect('/login');
    }
}
