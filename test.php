<?php
// User's password input
$password = '1234';

// Hash the password using bcrypt (default algorithm)
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Output the hashed password
echo "Hashed Password: " . $hashedPassword;
?>
