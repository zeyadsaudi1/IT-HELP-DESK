<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
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

    <div class="dashboard-container">
        <div class="sidebar">
            <div class="user-info">
                <h2>Hi, <?php echo $username; ?></h2>
                <p>Welcome back</p>
            </div>

            <div class="nav-menu">
                <div class="nav-item" onclick="navigateTo('chat')">
                    <i class="fas fa-comment-dots"></i>
                    <span>Start Chat</span>
                </div>
                <div class="nav-item" onclick="navigateTo('my_complaints')">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>My Complaints</span>
                </div>
                <div class="nav-item" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="welcome-message">
                <h1>Welcome to Your Dashboard</h1>
                <p>Please select an option from the sidebar to get started.</p>
            </div>

            <div class="quick-actions">
                <button class="btn" onclick="navigateTo('chat')">
                    <i class="fas fa-comment-dots"></i> Start Chat
                </button>
                <button class="btn btn-secondary" onclick="navigateTo('my_complaints')">
                    <i class="fas fa-exclamation-circle"></i> My Complaints
                </button>
            </div>
        </div>
    </div>

    <script>
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
        });

        function navigateTo(destination) {
            window.location.href = destination + '.php';
        }
    </script>
<style>
        body {
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(40deg, #1e3a8a, #212ca8, #000000, #212ca8, #dfdfdf, #000000);
            background-size: 400%;
            animation: gradientAnimation 10s ease infinite;
            color: white;
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
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 20px 0;
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        
        .user-info {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-info h2 {
            margin: 10px 0 5px;
            font-size: 18px;
        }
        
        .user-info p {
            margin: 0;
            color: #bdc3c7;
            font-size: 14px;
        }
        
        .nav-menu {
            margin-top: 30px;
        }
        
        .nav-item {
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #3498db;
        }
        
        .nav-item i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            overflow-y: auto;
        }
        
        .welcome-message {
            margin-bottom: 30px;
        }
        
        .welcome-message h1 {
            color: white;
            margin-bottom: 10px;
            font-size: 2.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .welcome-message p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: rgba(52, 152, 219, 0.9);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn:hover {
            background-color: rgba(41, 128, 185, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .btn-secondary {
            background-color: rgba(149, 165, 166, 0.9);
        }
        
        .btn-secondary:hover {
            background-color: rgba(127, 140, 141, 0.9);
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
    </style>
<?php include 'includes/footer.php'; ?>