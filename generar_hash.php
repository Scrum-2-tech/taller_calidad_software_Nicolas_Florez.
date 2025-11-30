<?php
// Contraseña que deseas usar para el administrador (Ej: 'admin123')
$password_plano = 'admin123'; 

// Generar el hash seguro
$hash = password_hash($password_plano, PASSWORD_DEFAULT);

echo "Contraseña en texto plano: " . $password_plano . "<br>";
echo "El hash generado es: " . $hash . "<br>";
echo "Usa este hash para actualizar la contraseña en tu BD.";

// Asegúrate de que el hash tenga al menos 60 caracteres.
?>