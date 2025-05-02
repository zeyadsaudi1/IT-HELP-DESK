<?php
include 'includes/header.php';
include 'includes/db_connect.php';


$login_error = '';
$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $login_error = 'يرجى ملء جميع الحقول';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $login_error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            }
        }
    } elseif (isset($_POST['register'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirmPassword'];

        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $register_error = 'يرجى ملء جميع الحقول';
        } elseif ($password !== $confirm_password) {
            $register_error = 'كلمات المرور غير متطابقة';
        } elseif (strlen($password) < 6) {
            $register_error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
        } elseif (strlen($username) < 4) {
            $register_error = 'اسم المستخدم يجب أن يكون 4 أحرف على الأقل';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR username = ?');
            $stmt->execute([$email, $username]);
            if ($stmt->fetch()) {
                $register_error = 'البريد الإلكتروني أو اسم المستخدم مستخدم بالفعل';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                if ($stmt->execute([$username, $email, $hashed_password])) {
                    $register_success = 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول';
                } else {
                    $register_error = 'حدث خطأ أثناء إنشاء الحساب';
                }
            }
        }
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

    <div class="login-container">
        <div class="login-form">
            <div class="logo-container">
                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZD0iTTEyIDJDNi40NzcgMiAyIDYuNDc3IDIgMTJzNC40NzcgMTAgMTAgMTAgMTAtNC40NzcgMTAtMTBTMTcuNTIzIDIgMTIgMnptLTEgMTVoMnYyaC0ydi0yem0wLTEzaDJ2MTBoLTJWNHoiIGZpbGw9IndoaXRlIi8+PC9zdmc+"
                    alt="نظام الشكاوى" class="logo">
            </div>
            <div id="login-form">
                <h1 class="text-white text-2xl font-bold mb-4">تسجيل الدخول</h1>
                <p class="text-gray-300 text-sm mb-8">أدخل بياناتك للوصول إلى النظام<br>أو <a href="#" id="switch-to-signup" class="text-blue-400 hover:underline">اضغط هنا</a> لإنشاء حساب</p>
                <form id="loginForm" method="POST">
                    <input type="hidden" name="login" value="1">
                    <input type="email" id="email" name="email" class="input-field w-full" placeholder="البريد الإلكتروني" required>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="input-field w-full" placeholder="كلمة المرور" required>
                        <span class="show-password" id="toggle-password">إظهار</span>
                    </div>
                    <div class="remember-container">
                        <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                        <label for="remember" class="text-gray-300 text-sm">تذكرني</label>
                    </div>
                    <div id="login-status" class="status-message error" style="<?php echo $login_error ? 'display: block;' : 'display: none;'; ?>">
                        <?php echo $login_error; ?>
                    </div>
                    <button type="submit" id="login-button" class="login-button w-full">تسجيل الدخول</button>
                </form>
            </div>

            <div id="registration-form" style="display: none;">
                <h1 class="text-white text-2xl font-bold mb-4">إنشاء حساب</h1>
                <p class="text-gray-300 text-sm mb-8">أدخل بياناتك لإنشاء حساب جديد<br>أو <a href="#" id="switch-to-login" class="text-blue-400 hover:underline">اضغط هنا</a> لتسجيل الدخول</p>
                <form id="registerForm" method="POST">
                    <input type="hidden" name="register" value="1">
                    <input type="text" id="reg-username" name="username" class="input-field w-full" placeholder="اسم المستخدم" required minlength="4">
                    <input type="email" id="reg-email" name="email" class="input-field w-full" placeholder="البريد الإلكتروني" required>
                    <div class="password-container">
                        <input type="password" id="reg-password" name="password" class="input-field w-full" placeholder="كلمة المرور" required minlength="6">
                        <span class="show-password" id="toggle-reg-password">إظهار</span>
                    </div>
                    <div class="password-container">
                        <input type="password" id="reg-confirm-password" name="confirmPassword" class="input-field w-full" placeholder="تأكيد كلمة المرور" required>
                        <span class="show-password" id="toggle-reg-confirm-password">إظهار</span>
                    </div>
                    <div id="register-status" class="status-message <?php echo $register_error ? 'error' : ($register_success ? 'success' : ''); ?>" style="<?php echo ($register_error || $register_success) ? 'display: block;' : 'display: none;'; ?>">
                        <?php echo $register_error ?: $register_success; ?>
                    </div>
                    <button type="submit" id="register-button" class="login-button w-full">إنشاء الحساب</button>
                </form>
            </div>
        </div>
        <div class="graphic-side">
            <div class="wave-animation"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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

            const switchToSignup = document.getElementById('switch-to-signup');
            const switchToLogin = document.getElementById('switch-to-login');
            const loginForm = document.getElementById('login-form');
            const registrationForm = document.getElementById('registration-form');

            if (switchToSignup && switchToLogin) {
                switchToSignup.addEventListener('click', (e) => {
                    e.preventDefault();
                    loginForm.style.display = 'none';
                    registrationForm.style.display = 'block';
                });

                switchToLogin.addEventListener('click', (e) => {
                    e.preventDefault();
                    registrationForm.style.display = 'none';
                    loginForm.style.display = 'block';
                });
            }

            const togglePassword = (id) => {
                const input = document.getElementById(id);
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                document.getElementById(`toggle-${id}`).textContent = type === 'password' ? 'إظهار' : 'إخفاء';
            };

            if (document.getElementById('toggle-password')) {
                document.getElementById('toggle-password').addEventListener('click', () => togglePassword('password'));
            }
            if (document.getElementById('toggle-reg-password')) {
                document.getElementById('toggle-reg-password').addEventListener('click', () => togglePassword('reg-password'));
            }
            if (document.getElementById('toggle-reg-confirm-password')) {
                document.getElementById('toggle-reg-confirm-password').addEventListener('click', () => togglePassword('reg-confirm-password'));
            }

            const validateEmail = (email) => {
                return email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/);
            };

            if (document.getElementById('loginForm')) {
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                const statusMessage = document.getElementById('login-status');

                emailInput.addEventListener('input', () => {
                    if (!validateEmail(emailInput.value)) {
                        emailInput.classList.add('invalid');
                        statusMessage.className = 'status-message error';
                        statusMessage.textContent = 'البريد الإلكتروني غير صالح';
                        statusMessage.style.display = 'block';
                    } else {
                        emailInput.classList.remove('invalid');
                        statusMessage.style.display = 'none';
                    }
                });

                passwordInput.addEventListener('input', () => {
                    if (passwordInput.value.length < 6) {
                        passwordInput.classList.add('invalid');
                        statusMessage.className = 'status-message error';
                        statusMessage.textContent = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
                        statusMessage.style.display = 'block';
                    } else {
                        passwordInput.classList.remove('invalid');
                        statusMessage.style.display = 'none';
                    }
                });
            }

            if (document.getElementById('registerForm')) {
                const usernameInput = document.getElementById('reg-username');
                const emailInput = document.getElementById('reg-email');
                const passwordInput = document.getElementById('reg-password');
                const confirmPasswordInput = document.getElementById('reg-confirm-password');
                const statusMessage = document.getElementById('register-status');

                usernameInput.addEventListener('input', () => {
                    if (usernameInput.value.length < 4) {
                        usernameInput.classList.add('invalid');
                        statusMessage.className = 'status-message error';
                        statusMessage.textContent = 'اسم المستخدم يجب أن يكون 4 أحرف على الأقل';
                        statusMessage.style.display = 'block';
                    } else {
                        usernameInput.classList.remove('invalid');
                        statusMessage.style.display = 'none';
                    }
                });

                emailInput.addEventListener('input', () => {
                    if (!validateEmail(emailInput.value)) {
                        emailInput.classList.add('invalid');
                        statusMessage.className = 'status-message error';
                        statusMessage.textContent = 'البريد الإلكتروني غير صالح';
                        statusMessage.style.display = 'block';
                    } else {
                        emailInput.classList.remove('invalid');
                        statusMessage.style.display = 'none';
                    }
                });

                passwordInput.addEventListener('input', () => {
                    if (passwordInput.value.length < 6) {
                        passwordInput.classList.add('invalid');
                        statusMessage.className = 'status-message error';
                        statusMessage.textContent = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
                        statusMessage.style.display = 'block';
                    } else {
                        passwordInput.classList.remove('invalid');
                        statusMessage.style.display = 'none';
                    }
                });

                confirmPasswordInput.addEventListener('input', () => {
                    if (confirmPasswordInput.value !== passwordInput.value) {
                        confirmPasswordInput.classList.add('invalid');
                        statusMessage.className = 'status-message error';
                        statusMessage.textContent = 'كلمات المرور غير متطابقة';
                        statusMessage.style.display = 'block';
                    } else {
                        confirmPasswordInput.classList.remove('invalid');
                        statusMessage.style.display = 'none';
                    }
                });
            }
        });
    </script>
<style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Tajawal', Arial, sans-serif;
            background: linear-gradient(40deg, #1e3a8a, #212ca8, #000000, #212ca8, #dfdfdf, #000000);
            background-size: 400%;
            animation: gradientAnimation 10s ease infinite;
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
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
        
        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 900px;
            width: 100%;
            display: flex;
            min-height: 500px;
            backdrop-filter: blur(10px);
            position: relative;
        }
        
        .login-form {
            padding: 2.5rem;
            width: 50%;
            display: flex;
            flex-direction: column;
        }
        
        .graphic-side {
            width: 50%;
            background: rgba(17, 21, 50, 0.7);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .wave-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="900" height="600" viewBox="0 0 900 600"><path d="M0 200 C 100 150, 300 150, 400 200 S 600 300, 800 200" stroke="%233B78FF" stroke-width="80" fill="none" opacity="0.3" transform="translate(0,150)"><animate attributeName="d" dur="15s" repeatCount="indefinite" values="M0 200 C 100 150, 300 50, 400 200 S 600 300, 800 200; M0 200 C 100 250, 300 250, 400 200 S 600 100, 800 200; M0 200 C 100 150, 300 50, 400 200 S 600 300, 800 200" /></path></svg>');
            background-size: cover;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo {
            max-width: 100px;
            height: auto;
            filter: brightness(0) invert(1);
        }
        
        .input-field {
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 0;
            margin-bottom: 1.5rem;
            outline: none;
            font-size: 16px;
            width: 100%;
            text-align: right;
        }
        
        .input-field:focus {
            border-bottom: 1px solid #3498db;
        }
        
        .login-button {
            background: rgba(52, 152, 219, 0.9);
            color: white;
            padding: 0.75rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .login-button:hover {
            background: rgba(41, 128, 185, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .status-message {
            margin-top: 1rem;
            padding: 0.5rem;
            border-radius: 0.25rem;
            text-align: center;
            font-size: 14px;
            display: none;
        }
        
        .status-message.error {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            display: block;
        }
        
        .status-message.success {
            background-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
            display: block;
        }
        
        .password-container {
            position: relative;
        }
        
        .show-password {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            cursor: pointer;
        }
        
        .remember-container {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .remember-checkbox {
            margin-left: 0.5rem;
        }
        
        .text-white {
            color: white;
        }
        
        .text-gray-300 {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .text-blue-400 {
            color: #60a5fa;
        }
        
        .hover\:underline:hover {
            text-decoration: underline;
        }
        
        .text-2xl {
            font-size: 1.5rem;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .mb-4 {
            margin-bottom: 1rem;
        }
        
        .text-sm {
            font-size: 0.875rem;
        }
        
        .mb-8 {
            margin-bottom: 2rem;
        }
        
        .w-full {
            width: 100%;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-form,
            .graphic-side {
                width: 100%;
            }
            .graphic-side {
                min-height: 200px;
                order: -1;
            }
        }
    </style>
<?php include 'includes/footer.php'; ?>