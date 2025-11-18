<?php

$host = "localhost";
$dbname = "hotel_db";
$db_username = 'root';
$db_password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function create_user_id() {
    $consonants = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'V', 'W', 'Z'];
    $vowels = ['A', 'E', 'I', 'O', 'U'];
    
    // Create 3-letter pronounceable code
    $code = $consonants[array_rand($consonants)] . 
            $vowels[array_rand($vowels)] . 
            $consonants[array_rand($consonants)];
    
    $numbers = rand(100, 999);
    
    return $code . $numbers;
}

function create_booking_id() {
    $consonants = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'V', 'W', 'Z'];
    $vowels = ['A', 'E', 'I', 'O', 'U'];
    
    // Create 3-letter pronounceable code
    $code = $consonants[array_rand($consonants)] . 
            $vowels[array_rand($vowels)] . 
            $consonants[array_rand($consonants)];
    
    $numbers = rand(1000, 9999);
    
    return $code . $numbers;
}

?>