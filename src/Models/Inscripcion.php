<?php
/**
 * Modelo Inscripcion
 * Maneja la relación muchos a muchos entre Usuarios y Actividades,
 * garantiza atomicidad y control estricto de cupos mediante transacciones SQL.
 */

require_once __DIR__ . '/../../config/database.php';

class Inscripcion {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Inscribir a un estudiante con control transaccional de cupos
     */
    public function inscribir(int $usuario_id, int $actividad_id): array {
        try {
            $this->db->beginTransaction();

            // 1. Verificar si ya está inscrito
            $stmtCheck = $this->db->prepare(
                "SELECT id, estado FROM inscripciones 
                 WHERE usuario_id = :usuario_id AND actividad_id = :actividad_id 
                 LIMIT 1"
            );
            $stmtCheck->execute([
                ':usuario_id'   => $usuario_id,
                ':actividad_id' => $actividad_id
            ]);
            $existente = $stmtCheck->fetch();

            if ($existente) {
                if ($existente['estado'] === 'Cancelado') {
                    // Reactivar si se había cancelado y hay cupo
                    $stmtReactivar = $this->db->prepare(
                        "UPDATE inscripciones SET estado = 'Inscrito', fecha_inscripcion = CURRENT_TIMESTAMP 
                         WHERE id = :id"
                    );
                    $stmtReactivar->execute([':id' => $existente['id']]);
                    $this->db->commit();
                    return ['success' => true, 'message' => '¡Inscripción reactivada con éxito!'];
                }
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Ya estás inscrito en esta actividad.'];
            }

            // 2. Bloquear la fila de la actividad y verificar cupo máximo
            $stmtAct = $this->db->prepare(
                "SELECT id, titulo, cupo_maximo, estado,
                        (SELECT COUNT(*) FROM inscripciones WHERE actividad_id = :act_id AND estado != 'Cancelado') AS inscritos
                 FROM actividades 
                 WHERE id = :act_id_2 FOR UPDATE"
            );
            $stmtAct->execute([
                ':act_id'   => $actividad_id,
                ':act_id_2' => $actividad_id
            ]);
            $actividad = $stmtAct->fetch();

            if (!$actividad) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'La actividad seleccionada no existe.'];
            }

            if ($actividad['estado'] !== 'Activa') {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Esta actividad no se encuentra activa para nuevas inscripciones.'];
            }

            // CRITERIO DE ACEPTACIÓN: Control de cupos
            if ((int)$actividad['inscritos'] >= (int)$actividad['cupo_maximo']) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Cupo lleno. No quedan plazas disponibles para esta actividad.'];
            }

            // 3. Registrar la inscripción
            $stmtInsert = $this->db->prepare(
                "INSERT INTO inscripciones (usuario_id, actividad_id, estado, asistio) 
                 VALUES (:usuario_id, :actividad_id, 'Inscrito', false)"
            );
            $stmtInsert->execute([
                ':usuario_id'   => $usuario_id,
                ':actividad_id' => $actividad_id
            ]);

            $this->db->commit();
            return ['success' => true, 'message' => '¡Inscripción completada exitosamente! Tu cupo ha sido reservado.'];

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error en inscripción: " . $e->getMessage());
            return ['success' => false, 'message' => 'Ocurrió un error al procesar la inscripción. Por favor inténtalo nuevamente.'];
        }
    }

    /**
     * Cancelar inscripción (libera el cupo)
     */
    public function cancelar(int $usuario_id, int $actividad_id): bool {
        $stmt = $this->db->prepare(
            "UPDATE inscripciones SET estado = 'Cancelado' 
             WHERE usuario_id = :usuario_id AND actividad_id = :actividad_id"
        );
        return $stmt->execute([
            ':usuario_id'   => $usuario_id,
            ':actividad_id' => $actividad_id
        ]);
    }

    /**
     * Verifica si un estudiante ya está inscrito
     */
    public function isUserInscrito(int $usuario_id, int $actividad_id): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM inscripciones 
             WHERE usuario_id = :usuario_id AND actividad_id = :actividad_id AND estado != 'Cancelado'"
        );
        $stmt->execute([
            ':usuario_id'   => $usuario_id,
            ':actividad_id' => $actividad_id
        ]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Obtener todas las actividades inscritas por un estudiante
     */
    public function getByUsuario(int $usuario_id): array {
        $sql = "SELECT i.*, 
                       a.titulo, a.categoria, a.descripcion, a.instructor, 
                       a.ubicacion, a.fecha_inicio, a.fecha_fin, a.horario, a.cupo_maximo
                FROM inscripciones i
                JOIN actividades a ON i.actividad_id = a.id
                WHERE i.usuario_id = :usuario_id AND i.estado != 'Cancelado'
                ORDER BY i.fecha_inscripcion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener inscritos para una actividad (para control de asistencia)
     */
    public function getByActividad(int $actividad_id): array {
        $sql = "SELECT i.*, 
                       u.cedula, u.nombre, u.apellido, u.email, u.telefono, u.trayectoria
                FROM inscripciones i
                JOIN usuarios u ON i.usuario_id = u.id
                WHERE i.actividad_id = :actividad_id AND i.estado != 'Cancelado'
                ORDER BY u.apellido ASC, u.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':actividad_id' => $actividad_id]);
        return $stmt->fetchAll();
    }

    /**
     * Listado general para reportes administrativos y control de asistencia
     */
    public function getAllWithDetails(?int $actividad_id = null): array {
        $sql = "SELECT i.*, 
                       u.cedula, u.nombre, u.apellido, u.email, u.telefono, u.trayectoria,
                       a.titulo AS actividad_titulo, a.categoria AS actividad_categoria,
                       a.fecha_inicio, a.horario, a.ubicacion
                FROM inscripciones i
                JOIN usuarios u ON i.usuario_id = u.id
                JOIN actividades a ON i.actividad_id = a.id
                WHERE i.estado != 'Cancelado'";
        $params = [];

        if ($actividad_id) {
            $sql .= " AND i.actividad_id = :actividad_id";
            $params[':actividad_id'] = $actividad_id;
        }

        $sql .= " ORDER BY a.titulo ASC, u.apellido ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Actualizar asistencia y observaciones
     */
    public function marcarAsistencia(int $inscripcion_id, bool $asistio, string $observaciones = ''): bool {
        $estado = $asistio ? 'Asistió' : 'Inscrito';
        $stmt = $this->db->prepare(
            "UPDATE inscripciones SET 
             asistio = :asistio, 
             estado = :estado, 
             observaciones = :observaciones 
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'            => $inscripcion_id,
            ':asistio'       => $asistio ? 1 : 0,
            ':estado'        => $estado,
            ':observaciones' => trim($observaciones)
        ]);
    }

    public function countTotal(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM inscripciones WHERE estado != 'Cancelado'");
        return (int)$stmt->fetchColumn();
    }

    public function countAsistencias(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM inscripciones WHERE asistio = true");
        return (int)$stmt->fetchColumn();
    }
}
