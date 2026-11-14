<?php
/**
 * Modelo Contacto (Atención al Usuario / Reclamos y Propuestas)
 */

require_once __DIR__ . '/../../config/database.php';

class Contacto {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): int {
        $sql = "INSERT INTO contactos (nombre, correo, cedula, tipo, asunto, mensaje, estado) 
                VALUES (:nombre, :correo, :cedula, :tipo, :asunto, :mensaje, 'Pendiente')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'  => trim($data['nombre']),
            ':correo'  => strtolower(trim($data['correo'])),
            ':cedula'  => trim($data['cedula'] ?? ''),
            ':tipo'    => trim($data['tipo'] ?? 'Consulta'),
            ':asunto'  => trim($data['asunto']),
            ':mensaje' => trim($data['mensaje'])
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function getAll(?string $estado = null): array {
        $sql = "SELECT * FROM contactos";
        $params = [];
        if ($estado) {
            $sql .= " WHERE estado = :estado";
            $params[':estado'] = $estado;
        }
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM contactos WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $contacto = $stmt->fetch();
        return $contacto ?: null;
    }

    public function updateEstado(int $id, string $estado, ?string $respuesta = null): bool {
        $sql = "UPDATE contactos SET estado = :estado, respuesta = :respuesta WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'        => $id,
            ':estado'    => $estado,
            ':respuesta' => $respuesta
        ]);
    }

    public function countPendientes(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM contactos WHERE estado = 'Pendiente'");
        return (int)$stmt->fetchColumn();
    }
}
