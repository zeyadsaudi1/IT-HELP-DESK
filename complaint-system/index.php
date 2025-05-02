<?php include 'includes/header.php'; ?>
    <nav class="navbar">
        <div class="logo">Assignment 2</div>
        <a href="#" id="aboutUsLink">About us</a>
    </nav>

    <div class="content">
        <div class="main-text">Welcom to Project L- Database wel Web</div>
        <div class="sub-text">IT Help Disk Team </div>
        <button class="arrow-btn" id="arrowBtn">let's start</button>
    </div>

    <!-- Pop-up Modal -->
    <div class="modal" id="aboutModal">
        <div class="modal-content">
            <table class="team-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hamza Saeed Shokri</td>
                        <td>20241711</td>
                    </tr>
                    <tr>
                        <td>Zeyad Mohamed Saudi</td>
                        <td>20241739</td>
                    </tr>
                    <tr>
                        <td>Aya Tullah Hany</td>
                        <td>20230569</td>
                    </tr>
                    <tr>
                        <td>Anas Yahea Mohamed</td>
                        <td>20241719</td>
                    </tr>
                    <tr>
                        <td>Eman Saeed Ahmed</td>
                        <td>20230488</td>
                    </tr>
                    <tr>
                        <td>Basmla Hussien Tawfiq</td>
                        <td>20230489</td>
                    </tr>
                    <tr>
                        <td>Amal Sophy Niazy</td>
                        <td>20230238</td>
                    </tr>
                    <tr>
                        <td>Khaled Ibrahim Hassan</td>
                        <td>20230660</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainText = document.querySelector('.main-text');
            const arrowBtn = document.getElementById('arrowBtn');
            const aboutUsLink = document.getElementById('aboutUsLink');
            const aboutModal = document.getElementById('aboutModal');

            mainText.addEventListener('animationend', (event) => {
                if (event.animationName === 'typing') {
                    mainText.style.borderLeft = 'none';
                    arrowBtn.style.display = 'inline-block';
                }
            });

            arrowBtn.addEventListener('click', () => {
                window.location.href = 'login.php';
            });

            aboutUsLink.addEventListener('click', (e) => {
                e.preventDefault();
                aboutModal.style.display = 'flex';
            });

            aboutModal.addEventListener('click', (e) => {
                if (e.target === aboutModal) {
                    aboutModal.style.display = 'none';
                }
            });
        });
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
        
        .navbar {
            position: absolute;
            top: 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-sizing: border-box;
        }
        
        .logo {
            font-size: 1.5em;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1em;
            text-transform: uppercase;
        }
        
        .nav-links .more-btn {
            background: #ff4081;
            padding: 8px 20px;
            border-radius: 20px;
            color: white;
        }
        
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            direction: ltr;
        }
        
        .main-text {
            font-size: 2em;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            border-left: 3px solid white;
            width: 0;
            animation: typing 5s steps(40, end) forwards, blink 0.5s step-end infinite;
            display: inline-block;
            direction: ltr;
        }
        
        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }
        
        @keyframes blink {
            50% {
                border-color: transparent;
            }
        }
        
        .sub-text {
            font-size: 1.2em;
            margin: 20px 0;
            opacity: 0;
            animation: fadeIn 1s ease forwards 5s;
        }
        
        .arrow-btn {
            display: none;
            background: rgba(10, 0, 85, 0.326);
            padding: 8px 20px;
            border-radius: 10px;
            cursor: pointer;
            animation: fadeIn 0.5s ease forwards;
            position: relative;
            color: white;
            font-size: 1em;
            border: none;
        }
        
        .arrow-btn::before {
            content: '';
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            width: 12px;
            height: 12px;
            border: solid white;
            border-width: 2px 0px 0px 2px;
            transform: rotate(135deg);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        /* Pop-up Modal Styles */
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: rgba(0, 0, 0, 0.9);
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            max-width: 600px;
            position: relative;
        }
        
        .team-table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }
        
        .team-table th,
        .team-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .team-table th {
            background: rgba(255, 255, 255, 0.1);
            font-size: 1.1em;
-Chunk 2/8- text-transform: uppercase;
        }
        
        .team-table td {
            font-size: 1em;
        }
        
        .team-table tr {
            transition: background 0.3s ease;
        }
        
        .team-table tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
<?php include 'includes/footer.php'; ?>