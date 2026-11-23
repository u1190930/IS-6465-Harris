<?php
function openDatabase($path = null) {
    if ($path === null) {
        $path = dirname(__DIR__) . '/database/tutoring.db';
    }
    $pdo = new PDO('sqlite:' . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    // Makes the supplied Lab 6 SQLite file compatible with Lab 7 on first use.
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (user_id INTEGER PRIMARY KEY, full_name TEXT NOT NULL, email TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL, role TEXT NOT NULL CHECK(role IN ('Student','Tutor','Administrator')), status TEXT NOT NULL DEFAULT 'Active' CHECK(status IN ('Active','Inactive')), created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP)");
    $columns = $pdo->query('PRAGMA table_info(students)')->fetchAll();
    $hasUserId = false;
    foreach ($columns as $column) { if ($column['name'] === 'user_id') { $hasUserId = true; } }
    if (!$hasUserId) { $pdo->exec('ALTER TABLE students ADD COLUMN user_id INTEGER REFERENCES users(user_id)'); }
    $pdo->exec('CREATE UNIQUE INDEX IF NOT EXISTS idx_students_user_id ON students(user_id) WHERE user_id IS NOT NULL');
    $pdo->exec("INSERT OR IGNORE INTO users (full_name,email,password_hash,role,status) VALUES ('System Administrator','admin@example.edu','\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','Administrator','Active')");
    return $pdo;
}
