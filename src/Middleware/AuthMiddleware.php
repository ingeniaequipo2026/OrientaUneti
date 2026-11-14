<?php
/**
 * Middleware para Control de Acceso y Protección de Rutas por Rol
 */

require_once __DIR__ . '/../../config/config.php';

class AuthMiddleware {
    /**
     * Exige que el usuario haya iniciado sesión
     */
    public static function requireAuth(): void {
        init_session();
        if (!is_logged_in()) {
            set_flash('warning', 'Debe iniciar sesión para acceder a esta sección.');
            redirect('/login');
        }
    }

    /**
     * Exige que el usuario tenga rol de Administrador (rol_id = 1)
     */
    public static function requireAdmin(): void {
        self::requireAuth();
        if (!is_admin()) {
            set_flash('danger', 'Acceso denegado: No cuenta con permisos de administrador.');
            redirect('/estudiante/dashboard');
        }
    }

    /**
     * Exige que el usuario sea Estudiante (rol_id = 2)
     */
    public static function requireStudent(): void {
        self::requireAuth();
        if (!is_student() && !is_admin()) {
            set_flash('danger', 'Acceso restringido a estudiantes.');
            redirect('/login');
        }
    }

    /**
     * Para rutas de Login y Registro: si ya está autenticado, lo envía a su panel
     */
    public static function requireGuest(): void {
        init_session();
        if (is_logged_in()) {
            if (is_admin()) {
                redirect('/admin/dashboard');
            } else {
                redirect('/estudiante/dashboard');
            }
        }
    }
}
