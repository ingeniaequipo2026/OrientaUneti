<?php
/**
 * Controlador de Perfil de Usuario (Estudiante)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Usuario.php';

class PerfilController {
    private Usuario $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function show(): void {
        $sessionUser = auth_user();
        if (!$sessionUser) {
            redirect('/login');
        }

        $user = $this->usuarioModel->findById((int)$sessionUser['id']);
        if (!$user) {
            redirect('/login');
        }

        require_once __DIR__ . '/../../views/student/perfil.php';
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/estudiante/perfil');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/estudiante/perfil');
        }

        $sessionUser = auth_user();
        if (!$sessionUser) {
            redirect('/login');
        }

        $nombre      = trim($_POST['nombre'] ?? '');
        $apellido    = trim($_POST['apellido'] ?? '');
        $telefono    = trim($_POST['telefono'] ?? '');
        $trayectoria = trim($_POST['trayectoria'] ?? '');
        $password    = $_POST['password'] ?? '';
        $password_c  = $_POST['password_confirm'] ?? '';

        if (empty($nombre) || empty($apellido)) {
            set_flash('danger', 'El nombre y apellido son obligatorios.');
            redirect('/estudiante/perfil');
        }

        $updateData = [
            'nombre'      => $nombre,
            'apellido'    => $apellido,
            'telefono'    => $telefono,
            'trayectoria' => $trayectoria,
        ];

        if (!empty($password)) {
            if (strlen($password) < 6) {
                set_flash('danger', 'La nueva contraseña debe tener al menos 6 caracteres.');
                redirect('/estudiante/perfil');
            }
            if ($password !== $password_c) {
                set_flash('danger', 'Las contraseñas no coinciden.');
                redirect('/estudiante/perfil');
            }
            $updateData['password'] = $password;
        }

        $exito = $this->usuarioModel->updateProfile((int)$sessionUser['id'], $updateData);

        if ($exito) {
            // Actualizar variables de sesión activas
            $_SESSION['user']['nombre'] = $nombre;
            $_SESSION['user']['apellido'] = $apellido;
            $_SESSION['user']['telefono'] = $telefono;
            $_SESSION['user']['trayectoria'] = $trayectoria;

            set_flash('success', '¡Perfil actualizado exitosamente!');
        } else {
            set_flash('danger', 'Error al actualizar el perfil.');
        }

        redirect('/estudiante/perfil');
    }
}
