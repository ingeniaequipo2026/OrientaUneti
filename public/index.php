<?php
/**
 * Punto de Entrada Principal y Enrutador
 * OrientaUneti - Plataforma de Bienestar Estudiantil
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';

// Inicializar sesión segura
init_session();

$router = new Router();

// ==========================================
// RUTAS PÚBLICAS Y DE AUTENTICACIÓN
// ==========================================
$router->get('/', 'DashboardController@home');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/registro', 'AuthController@showRegister');
$router->post('/registro', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Módulos públicos informativos
$router->get('/actividades', 'ActividadesController@studentCatalogo');
$router->get('/salud-mental', 'SaludMentalController@directorio');
$router->get('/emprendimientos', 'EmprendimientosController@vitrina');
$router->get('/contacto', 'ContactoController@showForm');
$router->post('/contacto', 'ContactoController@enviar');

// ==========================================
// RUTAS PROTEGIDAS - ESTUDIANTES
// ==========================================
$router->get('/estudiante/dashboard', 'DashboardController@studentDashboard', [
    fn() => AuthMiddleware::requireStudent()
]);
$router->post('/estudiante/inscribir', 'InscripcionesController@inscribir', [
    fn() => AuthMiddleware::requireStudent()
]);
$router->post('/estudiante/cancelar-inscripcion', 'InscripcionesController@cancelar', [
    fn() => AuthMiddleware::requireStudent()
]);
$router->get('/estudiante/mis-actividades', 'InscripcionesController@misActividades', [
    fn() => AuthMiddleware::requireStudent()
]);
$router->get('/estudiante/descargar-constancia', 'InscripcionesController@descargarConstancia', [
    fn() => AuthMiddleware::requireStudent()
]);
$router->get('/estudiante/perfil', 'PerfilController@show', [
    fn() => AuthMiddleware::requireStudent()
]);
$router->post('/estudiante/perfil', 'PerfilController@update', [
    fn() => AuthMiddleware::requireStudent()
]);

// ==========================================
// RUTAS PROTEGIDAS - ADMINISTRADOR
// ==========================================
$router->get('/admin/dashboard', 'DashboardController@adminDashboard', [
    fn() => AuthMiddleware::requireAdmin()
]);

// CRUD Actividades
$router->get('/admin/actividades', 'ActividadesController@adminIndex', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/actividades/crear', 'ActividadesController@adminCreate', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/actividades/crear', 'ActividadesController@adminStore', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/actividades/editar/{id}', 'ActividadesController@adminEdit', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/actividades/editar/{id}', 'ActividadesController@adminUpdate', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/actividades/eliminar/{id}', 'ActividadesController@adminDelete', [
    fn() => AuthMiddleware::requireAdmin()
]);

// CRUD Salud Mental
$router->get('/admin/salud-mental', 'SaludMentalController@adminIndex', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/salud-mental/crear', 'SaludMentalController@adminCreate', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/salud-mental/crear', 'SaludMentalController@adminStore', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/salud-mental/editar/{id}', 'SaludMentalController@adminEdit', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/salud-mental/editar/{id}', 'SaludMentalController@adminUpdate', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/salud-mental/eliminar/{id}', 'SaludMentalController@adminDelete', [
    fn() => AuthMiddleware::requireAdmin()
]);

// CRUD Emprendimientos
$router->get('/admin/emprendimientos', 'EmprendimientosController@adminIndex', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/emprendimientos/crear', 'EmprendimientosController@adminCreate', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/emprendimientos/crear', 'EmprendimientosController@adminStore', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/emprendimientos/editar/{id}', 'EmprendimientosController@adminEdit', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/emprendimientos/editar/{id}', 'EmprendimientosController@adminUpdate', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/emprendimientos/eliminar/{id}', 'EmprendimientosController@adminDelete', [
    fn() => AuthMiddleware::requireAdmin()
]);

// Control de Inscripciones y Asistencia
$router->get('/admin/inscripciones', 'InscripcionesController@adminIndex', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/asistencia/marcar', 'InscripcionesController@marcarAsistencia', [
    fn() => AuthMiddleware::requireAdmin()
]);

// Reportes (Planillas en Excel y PDF)
$router->get('/admin/reportes', 'ReportesController@index', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/reportes/excel', 'ReportesController@descargarExcel', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->get('/admin/reportes/pdf', 'ReportesController@descargarPDF', [
    fn() => AuthMiddleware::requireAdmin()
]);

// Atención al Usuario (Reclamos / Propuestas)
$router->get('/admin/contactos', 'ContactoController@adminIndex', [
    fn() => AuthMiddleware::requireAdmin()
]);
$router->post('/admin/contactos/actualizar', 'ContactoController@adminActualizar', [
    fn() => AuthMiddleware::requireAdmin()
]);

// Despachar la petición
$router->dispatch();
