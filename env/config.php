<?php
// Fungsi untuk memuat file env
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("File environment tidak ditemukan di $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            // Abaikan baris komentar
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
    return $env;
}

// Path ke file env
$envFilePath = __DIR__ . '/safera.env'; // Lokasi file di folder env

// Memuat isi file env
try {
    $env = loadEnv($envFilePath);

    // Simpan ke $_ENV agar bisa digunakan di seluruh aplikasi
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
} catch (Exception $e) {
    die("Error memuat file environment: " . $e->getMessage());
}
?>
