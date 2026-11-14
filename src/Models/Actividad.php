<?php
/**
 * Modelo Actividad
 */

require_once __DIR__ . '/../../config/database.php';

class Actividad {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(?string $categoria = null, ?string $estado = null): array {
        $sql = "SELECT a.*, 
                (SELECT COUNT(*) FROM inscripciones i WHERE i.actividad_id = a.id AND i.estado != 'Cancelado') AS cupos_ocupados
                FROM actividades a 
                WHERE 1=1";
        $params = [];

        if ($categoria && $categoria !== 'Todas') {
            $sql .= " AND a.categoria = :categoria";
            $params[':categoria'] = $categoria;
        }

        if ($estado) {
            $sql .= " AND a.estado = :estado";
            $params[':estado'] = $estado;
        }

        $sql .= " ORDER BY a.fecha_inicio ASC, a.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $actividades = $stmt->fetchAll();

        foreach ($actividades as &$act) {
            $act['cupos_disponibles'] = max(0, (int)$act['cupo_maximo'] - (int)$act['cupos_ocupados']);
            $act['esta_llena'] = ($act['cupos_ocupados'] >= $act['cupo_maximo']);
        }

        return $actividades;
    }

    public function findById(int $id): ?array {
        $sql = "SELECT a.*, 
                (SELECT COUNT(*) FROM inscripciones i WHERE i.actividad_id = a.id AND i.estado != 'Cancelado') AS cupos_ocupados
                FROM actividades a 
                WHERE a.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $act = $stmt->fetch();

        if ($act) {
            $act['cupos_disponibles'] = max(0, (int)$act['cupo_maximo'] - (int)$act['cupos_ocupados']);
            $act['esta_llena'] = ($act['cupos_ocupados'] >= $act['cupo_maximo']);
            return $act;
        }
        return null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO actividades (titulo, categoria, descripcion, instructor, ubicacion, fecha_inicio, fecha_fin, horario, cupo_maximo, estado, imagen) 
                VALUES (:titulo, :categoria, :descripcion, :instructor, :ubicacion, :fecha_inicio, :fecha_fin, :horario, :cupo_maximo, :estado, :imagen)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titulo'       => trim($data['titulo']),
            ':categoria'    => trim($data['categoria']),
            ':descripcion'  => trim($data['descripcion']),
            ':instructor'   => trim($data['instructor'] ?? ''),
            ':ubicacion'    => trim($data['ubicacion']),
            ':fecha_inicio' => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            ':fecha_fin'    => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null,
            ':horario'      => trim($data['horario'] ?? ''),
            ':cupo_maximo'  => (int)$data['cupo_maximo'],
            ':estado'       => $data['estado'] ?? 'Activa',
            ':imagen'       => $data['imagen'] ?? null
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE actividades SET 
                titulo = :titulo, 
                categoria = :categoria, 
                descripcion = :descripcion, 
                instructor = :instructor, 
                ubicacion = :ubicacion, 
                fecha_inicio = :fecha_inicio, 
                fecha_fin = :fecha_fin, 
                horario = :horario, 
                cupo_maximo = :cupo_maximo, 
                estado = :estado,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':titulo'       => trim($data['titulo']),
            ':categoria'    => trim($data['categoria']),
            ':descripcion'  => trim($data['descripcion']),
            ':instructor'   => trim($data['instructor'] ?? ''),
            ':ubicacion'    => trim($data['ubicacion']),
            ':fecha_inicio' => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            ':fecha_fin'    => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null,
            ':horario'      => trim($data['horario'] ?? ''),
            ':cupo_maximo'  => (int)$data['cupo_maximo'],
            ':estado'       => $data['estado'] ?? 'Activa'
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM actividades WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function countTotal(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM actividades");
        return (int)$stmt->fetchColumn();
    }
}
