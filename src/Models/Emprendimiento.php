<?php
/**
 * Modelo Emprendimiento
 */

require_once __DIR__ . '/../../config/database.php';

class Emprendimiento {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(?string $categoria = null): array {
        $sql = "SELECT * FROM emprendimientos";
        $params = [];
        if ($categoria && $categoria !== 'Todas') {
            $sql .= " WHERE categoria = :categoria";
            $params[':categoria'] = $categoria;
        }
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM emprendimientos WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $emp = $stmt->fetch();
        return $emp ?: null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO emprendimientos (nombre_proyecto, categoria, responsable, contacto, descripcion, enlace_redes, estado) 
                VALUES (:nombre, :categoria, :responsable, :contacto, :descripcion, :enlace_redes, :estado)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'       => trim($data['nombre_proyecto']),
            ':categoria'    => trim($data['categoria']),
            ':responsable'  => trim($data['responsable']),
            ':contacto'     => trim($data['contacto']),
            ':descripcion'  => trim($data['descripcion']),
            ':enlace_redes' => trim($data['enlace_redes'] ?? ''),
            ':estado'       => $data['estado'] ?? 'En Incubación'
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE emprendimientos SET 
                nombre_proyecto = :nombre, 
                categoria = :categoria, 
                responsable = :responsable, 
                contacto = :contacto, 
                descripcion = :descripcion, 
                enlace_redes = :enlace_redes, 
                estado = :estado 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':nombre'       => trim($data['nombre_proyecto']),
            ':categoria'    => trim($data['categoria']),
            ':responsable'  => trim($data['responsable']),
            ':contacto'     => trim($data['contacto']),
            ':descripcion'  => trim($data['descripcion']),
            ':enlace_redes' => trim($data['enlace_redes'] ?? ''),
            ':estado'       => $data['estado'] ?? 'En Incubación'
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM emprendimientos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function countTotal(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM emprendimientos");
        return (int)$stmt->fetchColumn();
    }
}
