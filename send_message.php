<?php
// --- YE SENSITIVE INFORMATION HAI, YE SERVER PAR HI RAHEGI ---

// Apna actual Bot Token yahan daaliye
 $botToken = "8045449350:AAHQChcqTeTB3fcxE55kMMffKLUafSNVTG0"; 

// Apna actual Chat ID yahan daaliye
 $chatId = "7291244998"; 

// ---------------------------------------------------------

// Check karte hain ki form se data aaya ya nahi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Form se data safely le rahe hain
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $user_message = trim($_POST['message']);

    // Ek acchi message format banate hain
    $text = "<b>New Message from Portfolio!</b>\n\n";
    $text .= "<b>Name:</b> " . htmlspecialchars($name) . "\n";
    $text .= "<b>Email:</b> " . htmlspecialchars($email) . "\n";
    $text .= "<b>Message:</b>\n" . htmlspecialchars($user_message);

    // Telegram API ko bhejne ke liye URL banate hain
    $apiUrl = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

    // Data jo hum API ko bhejenge
    $postData = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    // cURL use karke API call karte hain
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Security ke liye
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // API response check karke user ko success/error page par bhejte hain
    if ($httpcode == 200) {
        // Success: User ko wapas contact page par bhej do, ek success query ke saath
        header("Location: index.html?status=success");
        exit();
    } else {
        // Error: User ko wapas contact page par bhej do, ek error query ke saath
        header("Location: index.html?status=error");
        exit();
    }

} else {
    // Agar koi direct URL hit kar raha hai toh use home page par bhej do
    header("Location: index.html");
    exit();
}

?>
