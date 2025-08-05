<?php
$hashedPassword = password_hash('admin123', PASSWORD_DEFAULT); // Change 'admin123' to your actual password
echo "Your hashed password is: " . $hashedPassword;
