-- Run once only when upgrading an existing Lab 6 tutoring.db file.
-- This preserves existing students and tutoring requests.
PRAGMA foreign_keys = ON;
CREATE TABLE IF NOT EXISTS users (
    user_id INTEGER PRIMARY KEY,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL CHECK(role IN ('Student','Tutor','Administrator')),
    status TEXT NOT NULL DEFAULT 'Active' CHECK(status IN ('Active','Inactive')),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE students ADD COLUMN user_id INTEGER REFERENCES users(user_id);
CREATE UNIQUE INDEX IF NOT EXISTS idx_students_user_id
    ON students(user_id) WHERE user_id IS NOT NULL;
INSERT OR IGNORE INTO users (full_name,email,password_hash,role,status)
VALUES ('System Administrator','admin@example.edu','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','Administrator','Active');
