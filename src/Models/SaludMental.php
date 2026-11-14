<?php
/**
 * Modelo Salud Mental (Centros e Instituciones de Apoyo Psicológico)
 * Nota Ética: Este módulo solo almacena directorios y servicios de apoyo institucional,
 * protegiendo estrictamente la privacidad y confidencialidad estudiantil.
 */

require_once __DIR__ . '/../../config/database.php';

class SaludMental {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(?bool $soloActivos = false): array {
        $sql = "SELECT * FROM centros_salud";
        if ($soloActivos) {
            $sql .= " WHERE activo = true";
        }
        $sql .= " ORDER BY nombre_institucion ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM centros_salud WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $centro = $stmt->fetch();
        return $centro ?: null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO centros_salud (nombre_institucion, tipo_servicio, direccion, telefono, correo, horario_atencion, modalidad, descripcion, activo) 
                VALUES (:nombre, :tipo, :direccion, :telefono, :correo, :horario, :modalidad, :descripcion, :activo)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'      => trim($data['nombre_institucion']),
            ':tipo'        => trim($data['tipo_servicio']),
            ':direccion'   => trim($data['direccion']),
            ':telefono'    => trim($data['telefono'] ?? ''),
            ':correo'      => trim($data['correo'] ?? ''),
            ':horario'     => trim($data['horario_atencion'] ?? ''),
            ':modalidad'   => $data['modalidad'] ?? 'Presencial y Virtual',
            ':descripcion' => trim($data['descripcion'] ?? ''),
            ':activo'      => isset($data['activo']) ? (bool)$data['activo'] : true
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE centros_salud SET 
                nombre_institucion = :nombre, 
                tipo_servicio = :tipo, 
                direccion = :direccion, 
                telefono = :telefono, 
                correo = :correo, 
                horario_atencion = :horario, 
                modalidad = :modalidad, 
                descripcion = :descripcion, 
                activo = :activo 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':nombre'      => trim($data['nombre_institucion']),
            ':tipo'        => trim($data['tipo_servicio']),
            ':direccion'   => trim($data['direccion']),
            ':telefono'    => trim($data['telefono'] ?? ''),
            ':correo'      => trim($data['correo'] ?? ''),
            ':horario'     => trim($data['horario_atencion'] ?? ''),
            ':modalidad'   => $data['modalidad'] ?? 'Presencial y Virtual',
            ':descripcion' => trim($data['descripcion'] ?? ''),
            ':activo'      => isset($data['activo']) ? (bool)$data['activo'] : true
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM centros_salud WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function countTotal(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM centros_salud WHERE activo = true");
        return (int)$stmt->fetchColumn();
    }
}
