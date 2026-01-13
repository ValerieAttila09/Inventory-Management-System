<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class AdminSeeder extends AbstractSeed
{
    public function run(): void
    {
        $users = $this->table('users');

        // Create a default admin user (email: admin@example.com, password: admin123)
        $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);

        $data = [
            [
                'email' => 'admin@example.com',
                'password' => $passwordHash,
                'name' => 'Administrator',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $users->insert($data)->save();

        // Create roles and assign admin role
        $roles = $this->table('roles');
        $roles->insert([
            ['name' => 'admin', 'description' => 'Administrator']
        ])->save();

        $assign = $this->table('user_roles');
        // Get the inserted IDs by querying the tables
        $pdo = $this->getAdapter()->getConnection();
        $userId = $pdo->lastInsertId();

        // Fetch role id for admin
        $stmt = $pdo->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
        $stmt->execute([':name' => 'admin']);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($role) {
            // Find admin user id
            $stmt2 = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
            $stmt2->execute([':email' => 'admin@example.com']);
            $user = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $assign->insert([
                    ['user_id' => $user['id'], 'role_id' => $role['id']]
                ])->save();
            }
        }
    }
}
