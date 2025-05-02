<?php
require_once 'includes/db_connect.php';
require_once 'includes/auth.php';
// require_once 'includes/functions.php';

// // التحقق من أن المستخدم مشرف
// if (!isAdmin()) {
//     header("Location: login.php");
//     exit();
// }

// معالجة تحديث حالة الشكوى وإضافة رد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax_update'])) {
    header('Content-Type: application/json');
    $complaintId = $_POST['complaint_id'];
    $status = $_POST['status'];
    $response = sanitize($_POST['response']);
    
    $stmt = $pdo->prepare("UPDATE complaints SET status = ?, admin_response = ? WHERE id = ?");
    if ($stmt->execute([$status, $response, $complaintId])) {
        echo json_encode(['success' => true, 'message' => 'تم تحديث الشكوى بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء تحديث الشكوى']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_complaint'])) {
        $complaintId = $_POST['complaint_id'];
        $status = $_POST['status'];
        $response = sanitize($_POST['response']);
        
        $stmt = $pdo->prepare("UPDATE complaints SET status = ?, admin_response = ? WHERE id = ?");
        if ($stmt->execute([$status, $response, $complaintId])) {
            $success = "تم تحديث الشكوى بنجاح";
        } else {
            $error = "حدث خطأ أثناء تحديث الشكوى";
        }
    }
}

// جلب جميع الشكاوى من قاعدة البيانات
$search = isset($_GET['search']) ? "%".$_GET['search']."%" : "%";
$stmt = $pdo->prepare("SELECT c.*, u.username FROM complaints c JOIN users u ON c.user_id = u.id 
                    WHERE c.description LIKE ? OR u.username LIKE ? OR c.category LIKE ?
                    ORDER BY c.created_at DESC");
$stmt->execute([$search, $search, $search]);
$complaints = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - IT Help Desk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
        
        .search-bar {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 8px 15px;
            width: 300px;
        }
        
        .search-bar input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: white;
            padding: 8px;
        }
        
        .search-bar button {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
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
        
        .action-btn {
            background: none;
            border: none;
            color: #1a73e8;
            cursor: pointer;
            margin-right: 10px;
            font-size: 14px;
        }
        
        .action-btn:hover {
            text-decoration: underline;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: rgba(0, 0, 0, 0.9);
            padding: 30px;
            border-radius: 10px;
            width: 500px;
            max-width: 90%;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-header h2 {
            color: #1a73e8;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            color: white;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .submit-btn {
            background: #1a73e8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .submit-btn:hover {
            background: #1765cc;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background: rgba(46, 125, 50, 0.3);
            color: #81c784;
            border: 1px solid rgba(46, 125, 50, 0.5);
        }
        
        .alert-error {
            background: rgba(211, 47, 47, 0.3);
            color: #e57373;
            border: 1px solid rgba(211, 47, 47, 0.5);
        }
        
        /* Floating circles decoration */
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
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }
        
        @media (max-width: 768px) {
            .sidebar { width: 60px; }
            .main-content { padding: 20px; }
            .header { flex-direction: column; }
            .search-bar { width: 100%; margin-top: 15px; }
            .problems-table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
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
            <div class="logo">Admin Panel</div>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Problems Management</h1>
                <form method="GET" class="search-bar">
                    <input type="text" name="search" placeholder="Search problems..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <table class="problems-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Problem</th>
                        <th>Date</th>
                        <th>Response</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($complaints)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No complaints found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($complaints as $complaint): ?>
                        <tr>
                            <td>#<?php echo $complaint['id']; ?></td>
                            <td><?php echo htmlspecialchars($complaint['username']); ?></td>
                            <td><?php echo htmlspecialchars($complaint['category']); ?></td>
                            <td>
                                <span class="status status-<?php echo str_replace('_', '-', $complaint['status']); ?>" id="status-<?php echo $complaint['id']; ?>">
                                    <?php 
                                    echo ucwords(str_replace('_', ' ', $complaint['status'])); 
                                    ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($complaint['description']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($complaint['created_at'])); ?></td>
                            <td id="response-<?php echo $complaint['id']; ?>">
                                <?php if (!empty($complaint['admin_response'])): ?>
                                    <?php echo htmlspecialchars($complaint['admin_response']); ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="action-btn" onclick="openModal(<?php echo $complaint['id']; ?>, '<?php echo $complaint['status']; ?>', `<?php echo htmlspecialchars($complaint['admin_response'], ENT_QUOTES); ?>`)">
                                    <i class="fas fa-edit"></i> Update
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for updating complaint -->
    <div class="modal" id="updateModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Complaint</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <form method="POST" action="" id="updateComplaintForm">
                <input type="hidden" name="complaint_id" id="modal_complaint_id">
                <input type="hidden" name="ajax_update" value="1">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="modal_status" required>
                        <option style="color: #000000;" value="pending">Pending</option>
                        <option style="color: #000000;" value="in_progress">In Progress</option>
                        <option style="color: #000000;" value="resolved">Resolved</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="response">Admin Response</label>
                    <textarea name="response" id="modal_response" placeholder="Enter your response..."></textarea>
                </div>
                <button type="submit" class="submit-btn">Update Complaint</button>
            </form>
        </div>
    </div>

    <script>
        // Add more floating circles dynamically
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

            // AJAX form submission
            document.getElementById('updateComplaintForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const complaintId = formData.get('complaint_id');
                
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the status display
                        const statusElement = document.getElementById(`status-${complaintId}`);
                        const newStatus = formData.get('status').replace('_', '-');
                        const statusText = formData.get('status').replace('_', ' ');
                        
                        // Remove all status classes
                        statusElement.className = 'status';
                        // Add the new status class
                        statusElement.classList.add(`status-${newStatus}`);
                        // Update the text
                        statusElement.textContent = statusText.charAt(0).toUpperCase() + statusText.slice(1);
                        
                        // Update the response display
                        const responseElement = document.getElementById(`response-${complaintId}`);
                        responseElement.textContent = formData.get('response') || '-';
                        
                        // Show success message
                        alert(data.message);
                        closeModal();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء تحديث الشكوى');
                });
            });
        });

        // Modal functions
        function openModal(complaintId, status, response) {
            document.getElementById('modal_complaint_id').value = complaintId;
            document.getElementById('modal_status').value = status;
            document.getElementById('modal_response').value = response;
            document.getElementById('updateModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('updateModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>