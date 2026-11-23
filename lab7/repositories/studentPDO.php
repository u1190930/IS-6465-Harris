<?php
function findStudentByEmail($pdo, $email) {
    $stmt = $pdo->prepare('SELECT student_id, student_name, student_email, user_id FROM students WHERE student_email = ?');
    $stmt->execute([$email]); return $stmt->fetch();
}
function findStudentByUserId($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT student_id, student_name, student_email, user_id FROM students WHERE user_id = ?');
    $stmt->execute([$userId]); return $stmt->fetch();
}
function ensureStudentForUser($pdo, $user) {
    $student = findStudentByUserId($pdo, (int)$user['user_id']);
    if ($student) return $student;
    $stmt = $pdo->prepare('INSERT INTO students(student_name,student_email,user_id) VALUES(?,?,?)');
    $stmt->execute([$user['full_name'], $user['email'], (int)$user['user_id']]);
    return findStudentByUserId($pdo, (int)$user['user_id']);
}
function updateStudentForUser($pdo, $userId, $name, $email) {
    $stmt = $pdo->prepare('UPDATE students SET student_name=?, student_email=? WHERE user_id=?');
    $stmt->execute([$name, $email, $userId]);
}
function insertStudentIfAbsent($pdo, $name, $email) {
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO students(student_name,student_email) VALUES(?,?)');
    $stmt->execute([$name,$email]);
}
