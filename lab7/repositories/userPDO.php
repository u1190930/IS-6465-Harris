<?php
function findUserById($pdo, $id) { $s=$pdo->prepare('SELECT user_id,full_name,email,password_hash,role,status,created_at FROM users WHERE user_id=?'); $s->execute([$id]); return $s->fetch(); }
function findUserByEmail($pdo, $email) { $s=$pdo->prepare('SELECT user_id,full_name,email,password_hash,role,status,created_at FROM users WHERE email=?'); $s->execute([strtolower(trim($email))]); return $s->fetch(); }
function insertUser($pdo, $name, $email, $hash, $role) { $s=$pdo->prepare("INSERT INTO users(full_name,email,password_hash,role,status) VALUES(?,?,?,?, 'Active')"); $s->execute([$name,strtolower(trim($email)),$hash,$role]); return (int)$pdo->lastInsertId(); }
function updateOwnUser($pdo,$id,$name,$email) { $s=$pdo->prepare('UPDATE users SET full_name=?,email=? WHERE user_id=?'); $s->execute([$name,strtolower(trim($email)),$id]); return $s->rowCount()===1; }
function updateUserPassword($pdo,$id,$hash) { $s=$pdo->prepare('UPDATE users SET password_hash=? WHERE user_id=?'); $s->execute([$hash,$id]); return $s->rowCount()===1; }
function updateUserRole($pdo,$id,$role) { $s=$pdo->prepare('UPDATE users SET role=? WHERE user_id=?'); $s->execute([$role,$id]); return $s->rowCount()===1; }
function updateUserStatus($pdo,$id,$status) { $s=$pdo->prepare('UPDATE users SET status=? WHERE user_id=?'); $s->execute([$status,$id]); return $s->rowCount()===1; }
function deleteUserRow($pdo,$id) { $s=$pdo->prepare('DELETE FROM users WHERE user_id=?'); $s->execute([$id]); return $s->rowCount()===1; }
function countActiveAdministrators($pdo) { return (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='Administrator' AND status='Active'")->fetchColumn(); }
function searchUsers($pdo,$search='',$role='',$status='') { $sql='SELECT user_id,full_name,email,role,status,created_at FROM users WHERE 1=1'; $a=[]; if($search!==''){ $sql.=' AND (full_name LIKE ? OR email LIKE ?)';$a[]='%'.$search.'%';$a[]='%'.$search.'%'; } if($role!==''){$sql.=' AND role=?';$a[]=$role;} if($status!==''){$sql.=' AND status=?';$a[]=$status;} $sql.=' ORDER BY user_id DESC';$s=$pdo->prepare($sql);$s->execute($a);return $s->fetchAll(); }
function userHasRelatedRecords($pdo,$id) { $s=$pdo->prepare('SELECT COUNT(*) FROM students s LEFT JOIN tutoring_requests r ON r.student_id=s.student_id WHERE s.user_id=?');$s->execute([$id]);return (int)$s->fetchColumn()>0; }
