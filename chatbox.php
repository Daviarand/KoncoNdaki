<?php
// Handle API requests ONLY when it's a POST request with message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // Set JSON header only for API responses
    header('Content-Type: application/json');
    
    // Fungsi untuk mengambil respons dari GROQ API
    function getGroqResponse($message, $category = '') {
        // API key GROQ - ganti dengan API key yang sebenarnya
        $api_key = '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';
        
        // URL endpoint GROQ
        $url = 'https://api.groq.com/openai/v1/chat/completions';
        
        // Instruksi sistem berdasarkan kategori
        $system_prompts = [
            'rekomendasi' => "Kamu adalah asisten AI yang ahli dalam memberikan rekomendasi. 
                Berikan saran dan rekomendasi terbaik berdasarkan pertanyaan pengguna. 
                Fokus pada memberikan pilihan yang praktis, berguna, dan sesuai kebutuhan. 
                Berikan respons dalam bahasa Indonesia yang jelas dan mudah dipahami.",
            'prediksi' => "Kamu adalah asisten AI yang ahli dalam analisis dan prediksi. 
                Berikan analisis mendalam dan prediksi berdasarkan data atau informasi yang tersedia. 
                Jelaskan dasar pemikiran dan faktor-faktor yang mempengaruhi prediksi. 
                Berikan respons dalam bahasa Indonesia yang jelas dan mudah dipahami."
        ];
        
        $system_prompt = isset($system_prompts[$category]) ? $system_prompts[$category] : 
            "Kamu adalah asisten AI yang membantu pengguna dengan berbagai pertanyaan. 
            Berikan respons yang informatif dan berguna dalam bahasa Indonesia.";
        
        // Data untuk dikirim ke API
        $data = [
            'model' => 'llama3-8b-8192',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system_prompt
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 800
        ];    
        
        // Set up cURL request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ]);
        
        // Execute the request
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        
        if ($err) {
            return "Maaf, terjadi kesalahan teknis. Silakan coba lagi nanti.";
        }
        
        // Parse response
        $response_data = json_decode($response, true);
        
        // Extract the assistant's message
        if (isset($response_data['choices'][0]['message']['content'])) {
            return $response_data['choices'][0]['message']['content'];
        } else {
            return "Maaf, saya tidak dapat memproses pesan Anda saat ini.";
        }
    }

    $user_message = isset($_POST['message']) ? $_POST['message'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    
    if (!empty($user_message)) {
        $bot_response = getGroqResponse($user_message, $category);
        echo json_encode(['response' => $bot_response]);
    } else {
        echo json_encode(['response' => 'Maaf, saya tidak mengerti pesan Anda.']);
    }
    exit; // Stop execution after handling API request
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbox AI - Rekomendasi & Prediksi</title>
    <link rel="stylesheet" href="styles/chatbox.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Chatbox AI Assistant</h1>
            <p>Pilih kategori untuk memulai percakapan</p>
        </header>
        
        <div class="chat-container">
            <!-- Category Selection -->
            <div class="category-selection" id="categorySelection">
                <div class="welcome-message">
                    <h3>Selamat datang! 👋</h3>
                    <p>Silakan pilih kategori yang ingin Anda diskusikan:</p>
                </div>
                <div class="category-buttons">
                    <button class="category-btn" data-category="rekomendasi">
                        <div class="category-icon">💡</div>
                        <div class="category-text">
                            <h4>Rekomendasi</h4>
                            <p>Dapatkan saran dan rekomendasi terbaik</p>
                        </div>
                    </button>
                    <button class="category-btn" data-category="prediksi">
                        <div class="category-icon">🔮</div>
                        <div class="category-text">
                            <h4>Prediksi</h4>
                            <p>Analisis dan prediksi masa depan</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Chat Box -->
            <div class="chat-box" id="chatBox" style="display: none;">
                <div class="category-header" id="categoryHeader">
                    <span class="category-badge" id="categoryBadge"></span>
                    <button class="change-category-btn" id="changeCategoryBtn">Ganti Kategori</button>
                </div>
                <div class="messages-container" id="messagesContainer">
                    <!-- Messages will be added here -->
                </div>
            </div>
            
            <!-- Input Area -->
            <div class="input-area" id="inputArea" style="display: none;">
                <input type="text" id="userInput" placeholder="Ketik pesan Anda di sini..." disabled>
                <button id="sendButton" disabled>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22,2 15,22 11,13 2,9"></polygon>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script src="scripts/chatbox.js"></script>
</body>
</html>
