<?php
// CLI-only administrator bootstrap. Run after setup-database.sql.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('This script must be run from the command line.');
}
if ($argc !== 4) {
    fwrite(STDERR, "Usage: php database/create-admin.php \"Full Name\" email@example.edu \"Password\"\n");
    exit(1);
}
require_once dirname(__DIR__).'/config/db.php';
require_once dirname(__DIR__).'/repositories/userPDO.php';
$name = trim($argv[1]);
$email = strtolower(trim($argv[2]));
$password = $argv[3];
if (mb_strlen($name) < 2 || mb_strlen($name) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10 || strlen($password) > 128) {
    fwrite(STDERR, "Name, email, or password is invalid. Passwords must be 10 to 128 characters.\n");
    exit(1);
}
try {
    $pdo = openDatabase();
    if (findUserByEmail($pdo, $email)) {
        fwrite(STDERR, "An account already uses that email address.\n");
        exit(1);
    }
    $id = insertUser($pdo, $name, $email, password_hash($password, PASSWORD_DEFAULT), 'Administrator');
    echo "Administrator account created with user ID $id.\n";
} catch (Throwable $e) {
    fwrite(STDERR, "Unable to create administrator: " . $e->getMessage() . "\n");
    exit(1);
}
