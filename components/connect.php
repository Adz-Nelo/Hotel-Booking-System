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

function create_user_id($conn) {
    $consonants = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'V', 'W', 'Z'];
    $vowels = ['A', 'E', 'I', 'O', 'U'];
    
    do {
        $code = $consonants[array_rand($consonants)] . 
                $vowels[array_rand($vowels)] . 
                $consonants[array_rand($consonants)];
        
        $numbers = rand(100, 999);
        $user_id = $code . $numbers;
        
        // Check if this ID already exists in database
        $check_id = $conn->prepare("SELECT user_id FROM bookings WHERE user_id = ?");
        $check_id->execute([$user_id]);
        
    } while($check_id->rowCount() > 0);
    
    return $user_id;
}

function create_booking_id($conn) {
    $consonants = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'V', 'W', 'Z'];
    $vowels = ['A', 'E', 'I', 'O', 'U'];
    
    do {
        $code = $consonants[array_rand($consonants)] . 
                $vowels[array_rand($vowels)] . 
                $consonants[array_rand($consonants)];
        
        $numbers = rand(1000, 9999);
        $booking_id = $code . $numbers;
        
        // Check if this ID already exists in database
        $check_id = $conn->prepare("SELECT booking_id FROM bookings WHERE booking_id = ?");
        $check_id->execute([$booking_id]);
        
    } while($check_id->rowCount() > 0);
    
    return $booking_id;
}

?>