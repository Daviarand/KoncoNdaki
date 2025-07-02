<?php
// Ganti 'password_admin_anda' dengan kata sandi yang Anda inginkan
$passwordToHash = '1234';

// Menghasilkan hash yang kompatibel dengan password_verify()
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

echo "Gunakan hash ini di database Anda: <br>";
echo "<strong>" . htmlspecialchars($hashedPassword) . "</strong>";
?>