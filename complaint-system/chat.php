<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_problem'])) {
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $sub_category = filter_var($_POST['sub_category'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('INSERT INTO complaints (user_id, category, sub_category, description) VALUES (?, ?, ?, ?)');
    if ($stmt->execute([$user_id, $category, $sub_category, $description])) {
        $success_message = 'تم تسجيل الشكوى بنجاح!';
    } else {
        $error_message = 'حدث خطأ أثناء تسجيل الشكوى';
    }
}
?>

    <!-- Floating circles decoration -->
    <div class="floating-circles">
        <div style="left: 10%; width: 80px; height: 80px; animation-delay: 0s;"></div>
        <div style="left: 20%; width: 120px; height: 120px; animation-delay: 2s;"></div>
        <div style="left: 35%; width: 60px; height: 60px; animation-delay: 7s;"></div>
        <div style="left: 50%; width: 100px; height: 100px; animation-delay: 0s;"></div>
        <div style="left: 65%; width: 70px; height: 70px; animation-delay: 5s;"></div>
        <div style="left: 80%; width: 90px; height: 90px; animation-delay: 3s;"></div>
    </div>

    <div class="container">
        <div class="sidebar">
            <div class="logo">IT Help Desk</div>
        </div>

        <div class="main-content">
            <div class="chat-container">
                <div class="avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <h1>Hi, I'm IT Help Desk Team</h1>
                <p class="subtitle">How can I help you today?</p>

                <?php if (isset($success_message)): ?>
                    <div class="status-message success"><?php echo $success_message; ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="status-message error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="chat-history" id="chatHistory"></div>

                <div id="optionsStep1" class="options-container">
                    <button class="option-btn" onclick="selectOption(1, 'Hardware Issue')">Hardware Issue</button>
                    <button class="option-btn" onclick="selectOption(1, 'Software Issue')">Software Issue</button>
                    <button class="option-btn" onclick="selectOption(1, 'Network Problem')">Network Problem</button>
                    <button class="option-btn" onclick="selectOption(1, 'Account Access')">Account Access</button>
                </div>

                <div id="optionsStep2" class="options-container" style="display: none;"></div>

                <form id="descriptionStep" style="display: none;" method="POST">
                    <input type="hidden" name="submit_problem" value="1">
                    <input type="hidden" id="category" name="category">
                    <input type="hidden" id="sub_category" name="sub_category">
                    <div class="subtitle">Please describe your problem in details:</div>
                    <div class="input-container">
                        <input type="text" id="problemDescription" name="description" placeholder="Describe your problem...">
                        <button type="submit">Send</button>
                    </div>
                </form>

                <div id="confirmationMessage" style="display: none; margin-top: 20px;">
                    <p>Your problem will be dealt with as soon as possible.</p>
                    <button id="newProblemBtn" class="new-problem-btn" onclick="startNewProblem()">New Problem</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let selectedCategory = '';
        let selectedSubCategory = '';

        const subOptions = {
            'Hardware Issue': ['Computer not turning on', 'Peripheral not working', 'Hardware upgrade request', 'Other hardware issue'],
            'Software Issue': ['Application not launching', 'System error message', 'Software installation help', 'Other software issue'],
            'Network Problem': ['No internet connection', 'Slow network speed', 'Can\'t access internal resources', 'Other network issue'],
            'Account Access': ['Password reset', 'Account locked', 'Permission request', 'Other access issue']
        };

        document.addEventListener('DOMContentLoaded', function() {
            const circlesContainer = document.querySelector('.floating-circles');
            for (let i = 0; i < 10; i++) {
                const circle = document.createElement('div');
                const size = Math.random() * 100 + 50;
                const left = Math.random() * 100;
                const delay = Math.random() * 10;
                const duration = Math.random() * 20 + 10;

                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;
                circle.style.left = `${left}%`;
                circle.style.bottom = `-${size}px`;
                circle.style.animationDelay = `${delay}s`;
                circle.style.animationDuration = `${duration}s`;

                circlesContainer.appendChild(circle);
            }

            addBotMessage("Hi, I'm IT Help Desk Team. How can I help you today?");
        });

        function addBotMessage(text) {
            const chatHistory = document.getElementById('chatHistory');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message bot-message';
            messageDiv.textContent = text;
            chatHistory.appendChild(messageDiv);
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        function addUserMessage(text) {
            const chatHistory = document.getElementById('chatHistory');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message user-message';
            messageDiv.textContent = text;
            chatHistory.appendChild(messageDiv);
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        function selectOption(step, option) {
            if (step === 1) {
                selectedCategory = option;
                addUserMessage(option);
                currentStep = 2;

                document.getElementById('optionsStep1').style.display = 'none';
                const optionsStep2 = document.getElementById('optionsStep2');
                optionsStep2.innerHTML = '';

                subOptions[option].forEach(subOption => {
                    const btn = document.createElement('button');
                    btn.className = 'option-btn';
                    btn.textContent = subOption;
                    btn.onclick = function() {
                        selectOption(2, subOption);
                    };
                    optionsStep2.appendChild(btn);
                });

                optionsStep2.style.display = 'block';
                addBotMessage("Please specify the exact problem:");
                document.getElementById('category').value = option;

            } else if (step === 2) {
                selectedSubCategory = option;
                addUserMessage(option);
                currentStep = 3;

                document.getElementById('optionsStep2').style.display = 'none';
                document.getElementById('descriptionStep').style.display = 'block';
                addBotMessage("Please describe your problem in details:");
                document.getElementById('sub_category').value = option;
            }
        }

        function startNewProblem() {
            currentStep = 1;
            selectedCategory = '';
            selectedSubCategory = '';

            document.getElementById('chatHistory').innerHTML = '';
            document.getElementById('problemDescription').value = '';
            document.getElementById('category').value = '';
            document.getElementById('sub_category').value = '';

            document.getElementById('optionsStep1').style.display = 'block';
            document.getElementById('optionsStep2').style.display = 'none';
            document.getElementById('descriptionStep').style.display = 'none';
            document.getElementById('confirmationMessage').style.display = 'none';

            addBotMessage("Hi, I'm IT Help Desk Team. How can I help you today?");
        }

        document.getElementById('problemDescription').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('descriptionStep').submit();
            }
        });
    </script>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(40deg, #1e3a8a, #212ca8, #000000, #212ca8, #dfdfdf, #000000);
            background-size: 400%;
            animation: gradientAnimation 10s ease infinite;
            position: relative;
            overflow: hidden;
        }
        
        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        .container {
            display: flex;
            flex: 1;
        }
        
        .sidebar {
            width: 80px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px 0;
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            padding: 15px 0;
        }
        
        .main-content {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .chat-container {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            padding: 40px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 50px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        h1 {
            font-size: 28px;
            margin-bottom: 15px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .input-container {
            display: flex;
            margin-top: 30px;
            width: 100%;
            max-width: 500px;
        }
        
        input {
            flex: 1;
            padding: 15px 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s;
            background-color: rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        input:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.3);
        }
        
        button {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 15px 24px;
            margin-left: 10px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        button:hover {
            background-color: #1765cc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .options-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 20px 0;
        }
        
        .option-btn {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: left;
        }
        
        .option-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .chat-history {
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            text-align: left;
            padding: 10px;
            border-radius: 8px;
            background-color: rgba(0, 0, 0, 0.2);
        }
        
        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 18px;
            max-width: 80%;
            word-wrap: break-word;
        }
        
        .user-message {
            background-color: rgba(26, 115, 232, 0.7);
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }
        
        .bot-message {
            background-color: rgba(255, 255, 255, 0.1);
            margin-right: auto;
            border-bottom-left-radius: 5px;
        }
        
        .new-problem-btn {
            margin-top: 20px;
            display: none;
        }
        
        .floating-circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .floating-circles div {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 15s linear infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }
        
        .status-message {
            margin: 1rem 0;
            padding: 0.5rem;
            border-radius: 0.25rem;
            text-align: center;
            font-size: 14px;
        }
        
        .status-message.error {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .status-message.success {
            background-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            .main-content {
                padding: 20px;
            }
            .chat-container {
                padding: 30px 20px;
            }
            .avatar {
                width: 80px;
                height: 80px;
                font-size: 35px;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
<?php include 'includes/footer.php'; ?>