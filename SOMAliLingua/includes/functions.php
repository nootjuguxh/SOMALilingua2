<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

function url(string $path = ''): string {
    return rtrim(BASE_URL, '/') . ($path !== '' ? '/' . ltrim($path, '/') : '/');
}
function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419); exit('Your form session expired. Please go back, refresh the page and try again.');
    }
}
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type'=>$type, 'message'=>$message]; }
function get_flash(): ?array { $item = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $item; }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function is_logged_in(): bool { return current_user() !== null; }
function require_login(): void { if (!is_logged_in()) { flash('warning', 'Please sign in to access your learning dashboard.'); redirect('login.php'); } }
function require_admin(): void {
    require_login();
    if ((current_user()['role'] ?? 'student') !== 'admin') { http_response_code(403); exit('Access denied. Administrator permission is required.'); }
}
function mark_progress(PDO $pdo, int $userId, string $type, int $itemId): void {
    $stmt = $pdo->prepare('INSERT INTO user_progress (user_id, item_type, item_id) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE completed_at = CURRENT_TIMESTAMP');
    $stmt->execute([$userId, $type, $itemId]);
}
function is_completed(PDO $pdo, int $userId, string $type, int $itemId): bool {
    $stmt = $pdo->prepare('SELECT 1 FROM user_progress WHERE user_id = ? AND item_type = ? AND item_id = ?');
    $stmt->execute([$userId, $type, $itemId]); return (bool)$stmt->fetchColumn();
}
function upload_audio(string $field, string $folder): ?string {
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Audio upload failed. Please select the file again.');
    if ($file['size'] > UPLOAD_MAX_BYTES) throw new RuntimeException('Audio files must be 10 MB or smaller.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['audio/mpeg'=>'mp3', 'audio/wav'=>'wav', 'audio/x-wav'=>'wav', 'audio/ogg'=>'ogg', 'audio/mp4'=>'m4a', 'audio/aac'=>'aac', 'audio/x-m4a'=>'m4a'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Unsupported audio format. Use MP3, WAV, OGG or M4A.');
    $relativeDir = 'assets/audio/' . trim($folder, '/');
    $absoluteDir = APP_ROOT . '/' . $relativeDir;
    if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) throw new RuntimeException('Could not create the audio directory.');
    $name = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $absoluteDir . '/' . $name)) throw new RuntimeException('Could not save the uploaded audio file.');
    return $relativeDir . '/' . $name;
}
function level_label(string $level): string { return ucfirst(str_replace('_', ' ', $level)); }
