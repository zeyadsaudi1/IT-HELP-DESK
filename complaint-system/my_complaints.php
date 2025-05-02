<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$complaints = $stmt->fetchAll();
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
            <div class="logo">My Problems</div>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>My Reported Problems</h1>
                <button class="new-problem-btn" onclick="location.href='chat.php'">
                    <i class="fas fa-plus"></i> New Problem
                </button>
            </div>

            <table class="problems-table">
                <thead>
                    <tr>
                        <th>Problem ID</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Problem Description</th>
                        <th>Admin Response</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($complaints as $complaint): ?>
                        <tr>
                            <td>#<?php echo $complaint['id']; ?></td>
                            <td><span class="status status-<?php echo strtolower(str_replace(' ', '-', $complaint['status'])); ?>"><?php echo $complaint['status']; ?></span></td>
                            <td><?php echo date('Y-m-d', strtotime($complaint['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($complaint['description']); ?></td>
                            <td><?php echo htmlspecialchars($complaint['admin_response'] ?: 'No response yet'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($complaints)): ?>
                        <tr>
                            <td colspan="5">No complaints found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
            min-height: 100vh;
            background: linear-gradient(40deg, #1e3a8a, #212ca8, #000000, #212ca8, #dfdfdf, #000000);
            background-size: 400%;
            animation: gradientAnimation 10s ease infinite;
            position: relative;
            overflow-x: hidden;
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
            min-height: 100vh;
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
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            overflow-y: auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header h1 {
            font-size: 28px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .new-problem-btn {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .new-problem-btn:hover {
            background-color: #1765cc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .problems-table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .problems-table th,
        .problems-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .problems-table th {
            background-color: rgba(0, 0, 0, 0.3);
            font-weight: 500;
            color: #1a73e8;
        }
        
        .problems-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }
        
        .status-in-progress {
            background-color: rgba(3, 169, 244, 0.2);
            color: #03a9f4;
        }
        
        .status-resolved {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4caf50;
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
        
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }
            .main-content {
                padding: 20px;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            .new-problem-btn {
                width: 100%;
                margin-top: 15px;
            }
            .problems-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
<?php include 'includes/footer.php'; ?>