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
    <title>KoncoNdaki AI Assistant - Rekomendasi & Prediksi</title>
    <link rel="stylesheet" href="styles/chatbox.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background-pattern"></div>
    <div class="container">
        <header class="header-enhanced">
            <div class="header-content">
                <div class="logo-section">
                    <div class="logo-icon">🏔️</div>
                    <div class="logo-text">
                        <h1>KoncoNdaki AI Assistant</h1>
                        <p>Asisten pintar untuk petualangan Anda</p>
                    </div>
                </div>
                <div class="header-decoration">
                    <div class="mountain-silhouette"></div>
                </div>
            </div>
        </header>
        
        <div class="chat-container">
            <!-- Category Selection -->
            <div class="category-selection" id="categorySelection">
                <div class="welcome-message">
                    <div class="welcome-icon">👋</div>
                    <h3>Selamat datang di KoncoNdaki AI!</h3>
                    <p>Pilih kategori untuk memulai percakapan dengan asisten AI kami</p>
                </div>
                <div class="category-buttons">
                    <button class="category-btn" data-category="rekomendasi">
                        <div class="category-icon">💡</div>
                        <div class="category-content">
                            <h4>Rekomendasi</h4>
                            <p>Dapatkan saran terbaik untuk perjalanan dan aktivitas outdoor</p>
                            <div class="category-features">
                                <span>• Rekomendasi gunung</span>
                                <span>• Tips perjalanan</span>
                                <span>• Saran peralatan</span>
                            </div>
                        </div>
                        <div class="category-arrow">→</div>
                    </button>
                    <button class="category-btn" data-category="prediksi">
                        <div class="category-icon">🔮</div>
                        <div class="category-content">
                            <h4>Prediksi</h4>
                            <p>Analisis dan prediksi untuk perencanaan yang lebih baik</p>
                            <div class="category-features">
                                <span>• Prediksi cuaca</span>
                                <span>• Analisis kondisi</span>
                                <span>• Estimasi waktu</span>
                            </div>
                        </div>
                        <div class="category-arrow">→</div>
                    </button>
                </div>
            </div>

            <!-- Chat Box -->
            <div class="chat-box" id="chatBox" style="display: none;">
                <div class="category-header" id="categoryHeader">
                    <div class="category-info">
                        <span class="category-badge" id="categoryBadge"></span>
                        <span class="online-status">
                            <div class="status-dot"></div>
                            AI Online
                        </span>
                    </div>
                    <button class="change-category-btn" id="changeCategoryBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18m-9-9l9 9-9 9"/>
                        </svg>
                        Ganti Kategori
                    </button>
                </div>
                <div class="messages-container" id="messagesContainer">
                    <!-- Messages will be added here -->
                </div>
            </div>
            
            <!-- Input Area -->
            <div class="input-area" id="inputArea" style="display: none;">
                <div class="input-wrapper">
                    <input type="text" id="userInput" placeholder="Ketik pesan Anda di sini..." disabled>
                    <button id="sendButton" disabled>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22,2 15,22 11,13 2,9"></polygon>
                        </svg>
                    </button>
                </div>
                <div class="input-suggestions">
                    <span class="suggestion-chip" data-text="Rekomendasi gunung untuk pemula">Gunung untuk pemula</span>
                    <span class="suggestion-chip" data-text="Tips persiapan mendaki">Tips persiapan</span>
                    <span class="suggestion-chip" data-text="Peralatan wajib pendakian">Peralatan wajib</span>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts/chatbox.js"></script>
</body>
</html>
