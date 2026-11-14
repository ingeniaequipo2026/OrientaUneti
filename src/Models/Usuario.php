<?php
/**
 * Modelo Usuario
 */

require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => strtolower(trim($email))]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByCedula(string $cedula): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE cedula = :cedula LIMIT 1");
        $stmt->execute([':cedula' => strtoupper(trim($cedula))]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre AS rol_nombre 
                                    FROM usuarios u 
                                    JOIN roles r ON u.rol_id = r.id 
                                    WHERE u.id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria) 
                VALUES (:rol_id, :cedula, :nombre, :apellido, :email, :password, :telefono, :trayectoria)";
        
        $hashed = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':rol_id'      => $data['rol_id'] ?? 2, // 2: Estudiante por defecto
            ':cedula'      => strtoupper(trim($data['cedula'])),
            ':nombre'      => trim($data['nombre']),
            ':apellido'    => trim($data['apellido']),
            ':email'       => strtolower(trim($data['email'])),
            ':password'    => $hashed,
            ':telefono'    => trim($data['telefono'] ?? ''),
            ':trayectoria' => trim($data['trayectoria'] ?? '')
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool {
        $fields = [
            'nombre = :nombre',
            'apellido = :apellido',
            'telefono = :telefono',
            'trayectoria = :trayectoria'
        ];
        $params = [
            ':id'          => $id,
            ':nombre'      => trim($data['nombre']),
            ':apellido'    => trim($data['apellido']),
            ':telefono'    => trim($data['telefono'] ?? ''),
            ':trayectoria' => trim($data['trayectoria'] ?? '')
        ];

        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $sql = "UPDATE usuarios SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getAllStudents(): array {
        $stmt = $this->db->query("SELECT u.*, 
                                  (SELECT COUNT(*) FROM inscripciones i WHERE i.usuario_id = u.id) AS total_inscripciones
                                  FROM usuarios u 
                                  WHERE u.rol_id = 2 
                                  ORDER BY u.apellido ASC, u.nombre ASC");
        return $stmt->fetchAll();
    }

    public function countTotalStudents(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM usuarios WHERE rol_id = 2");
        return (int)$stmt->fetchColumn();
    }
}
