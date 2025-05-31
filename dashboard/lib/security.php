<?php
// lib/security.php
// Ortak güvenlik fonksiyonları: şifreleme, CSRF ve backup kodları

// 1) AES şifreleme için uygulama anahtarını .env veya sabit olarak tanımlayın
if (!defined('APP_KEY')) {
    // Örnek: .env dosyasından alın veya doğrudan buraya 32 byte uzunluğunda rastgele bir string atayın
    define('APP_KEY', getenv('APP_KEY') ?: 'your-32-byte-random-app-key-here');
}
// IV: APP_KEY'in ilk 16 byte'ı
if (!defined('APP_IV')) {
    define('APP_IV', substr(APP_KEY, 0, 16));
}

/**
 * AES-256-CBC ile secret şifreler
 *
 * @param string $secret
 * @return string 
 */
function encryptSecret(string $secret): string
{
    return openssl_encrypt($secret, 'AES-256-CBC', APP_KEY, OPENSSL_RAW_DATA, APP_IV);
}

/**
 * AES-256-CBC ile şifrelenmiş veriyi çözer
 *
 * @param string $enc
 * @return string 
 */
function decryptSecret(string $enc): string
{
    return openssl_decrypt($enc, 'AES-256-CBC', APP_KEY, OPENSSL_RAW_DATA, APP_IV);
}

/**
 * Basit CSRF token üretir ve session'da saklar
 *
 * @return string
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}
/**
 * Gelen CSRF token'ın doğruluğunu kontrol eder
 *
 * @param string $token
 * @return bool
 */
function verifyCsrf(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Tek kullanımlık backup kodları üretir
 *
 * @param int $count
 * @return array<string>
 */
function generateBackupCodes(int $count = 8): array
{
    $codes = [];
    for ($i = 0; $i < $count; $i++) {
        $codes[] = bin2hex(random_bytes(4));
    }
    return $codes;
}

/**
 * Backup kodunun hashlenmiş versiyonlarını oluşturarak saklanmaya hazır hale getirir
 *
 * @param string[] $codes
 * @return string[] hashing yapılmış dizin
 */
function hashBackupCodes(array $codes): array
{
    $hashed = [];
    foreach ($codes as $code) {
        $hashed[] = password_hash($code, PASSWORD_DEFAULT);
    }
    return $hashed;
}

/**
 * Gönderilen backup kodunu doğrular ve eşleşen hash varsa geriye true döner
 *
 * @param string $inputCode
 * @param string[] $hashedCodes
 * @return bool
 */
function verifyBackupCode(string $inputCode, array $hashedCodes): bool
{
    foreach ($hashedCodes as $i => $hash) {
        if (password_verify($inputCode, $hash)) {
            return true;
        }
    }
    return false;
}
