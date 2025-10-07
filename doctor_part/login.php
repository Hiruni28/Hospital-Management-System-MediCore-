<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $doctor = $result->fetch_assoc();
        $_SESSION['doctor'] = $doctor;
        header("Location: newdoctor-dashboard.php"); // <-- Redirect here
        exit();
    } else {
        echo "<script>alert('Invalid credentials'); window.location.href='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login </title>
    <link rel="icon" href="../images/doctor_portal.png" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, rgba(1, 87, 62, 0.8), rgba(7, 199, 141, 0.8)),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080"><defs><linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:%23f0f9ff;stop-opacity:1" /><stop offset="100%" style="stop-color:%23e0f2fe;stop-opacity:1" /></linearGradient></defs><rect width="1920" height="1080" fill="url(%23bg)"/><g opacity="0.1"><circle cx="200" cy="200" r="100" fill="%23667eea"/><circle cx="1600" cy="300" r="150" fill="%23764ba2"/><circle cx="400" cy="800" r="80" fill="%23667eea"/><circle cx="1200" cy="700" r="120" fill="%23764ba2"/><path d="M0,400 Q400,200 800,400 T1600,400 Q1800,500 1920,400 V1080 H0 Z" fill="%23667eea"/><path d="M0,600 Q600,400 1200,600 T1920,600 V1080 H0 Z" fill="%23764ba2"/></g><g opacity="0.05"><rect x="100" y="100" width="100" height="100" fill="%23667eea" transform="rotate(45 150 150)"/><rect x="1400" y="200" width="80" height="80" fill="%23764ba2" transform="rotate(45 1440 240)"/><rect x="300" y="600" width="60" height="60" fill="%23667eea" transform="rotate(45 330 630)"/><rect x="1500" y="800" width="70" height="70" fill="%23764ba2" transform="rotate(45 1535 835)"/></g></svg>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #9199bbff, #8e77a5ff, #7a82a8ff);
            border-radius: 27px;
            z-index: -1;
            opacity: 0.7;
            animation: borderGlow 3s ease-in-out infinite alternate;
        }

        @keyframes borderGlow {
            0% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo h1 {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .logo p {
            color: rgba(18, 20, 24, 0.98);
            font-size: 16px;
            font-weight: 400;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-group {
            position: relative;
            text-align: left;
        }

        .form-group label {
            display: block;
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-group input:focus {
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .login-btn {
            background: linear-gradient(135deg, #6979c0ff, #735196ff);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .forgot-password {
            margin-top: 20px;
        }

        .forgot-password a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .forgot-password a:hover {
            color: white;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 450px;
            position: relative;
            animation: slideInModal 0.4s ease;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        @keyframes slideInModal {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .close:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(90deg);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .modal-header h2 {
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .modal-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            line-height: 1.5;
        }

        .forgot-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .forgot-form .form-group {
            position: relative;
        }

        .forgot-form input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
        }

        .forgot-form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .forgot-form input:focus {
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .forgot-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .forgot-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .forgot-btn:hover::before {
            left: 100%;
        }

        .forgot-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .success-message {
            display: none;
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .success-message.show {
            display: block;
            animation: slideIn 0.5s ease;
        }

        .success-icon {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }

        .features {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 40px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .feature-icon {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            left: 10%;
            top: 20%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            right: 10%;
            top: 30%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 100px;
            height: 100px;
            left: 20%;
            bottom: 20%;
            animation-delay: 4s;
        }

        .floating-element:nth-child(4) {
            width: 70px;
            height: 70px;
            right: 20%;
            bottom: 30%;
            animation-delay: 1s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .medical-cross {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #ff6b6b, #ffa726);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 500px) {
            .login-container {
                margin: 20px;
                padding: 40px 30px;
            }

            .logo h1 {
                font-size: 28px;
            }

            .features {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <div class="login-container">
        <div class="medical-cross">+</div>

        <div class="logo">
            <h1>MediCore Hospitals</h1>
            <p>Doctor Portal Access</p>
        </div>

        <form class="login-form" action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-btn">Sign In</button>

        </form>

        <div class="forgot-password">
            <a href="#" onclick="openForgotModal()">Forgot your password?</a>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeForgotModal()">&times;</span>
            <div class="modal-header">
                <h2>Reset Password</h2>
                <p>Enter your username or email address and we'll send you instructions to reset your password.</p>
            </div>

            <form class="forgot-form" id="forgotPasswordForm" onsubmit="handleForgotPassword(event)">
                <div class="form-group">
                    <input type="text" id="forgot-identifier" name="identifier" placeholder="Username or Email Address" required>
                </div>

                <button type="submit" class="forgot-btn" id="forgotSubmitBtn">Send Reset Instructions</button>
            </form>

            <div class="success-message" id="successMessage">
                <span class="success-icon">✅</span>
                <h3 style="margin-bottom: 10px; color: white;">Instructions Sent!</h3>
                <p>If an account with that identifier exists, you'll receive password reset instructions via email within a few minutes.</p>
            </div>
        </div>
    </div>

    <div class="features" style="color: rgba(0, 0, 0, 0.6); font-size: 16px;">
        <div class="feature">
            <svg class="feature-icon" viewBox="0 0 24 24">
                <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1M12 7C13.4 7 14.8 8.6 14.8 10.2V11C15.4 11 16 11.4 16 12V16C16 16.6 15.6 17 15 17H9C8.4 17 8 16.6 8 16V12C8 11.4 8.4 11 9 11V10.2C9 8.6 10.6 7 12 7M12 8.2C11.2 8.2 10.2 8.7 10.2 10.2V11H13.8V10.2C13.8 8.7 12.8 8.2 12 8.2Z" />
            </svg>
            Secure Access
        </div>
        <div class="feature">
            <svg class="feature-icon" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12S6.48 22 12 22 22 17.52 22 12 17.52 2 12 2M12 20C7.59 20 4 16.41 4 12S7.59 4 12 4 20 7.59 20 12 16.41 20 12 20M12 6C8.69 6 6 8.69 6 12S8.69 18 12 18 18 15.31 18 12 15.31 6 12 6M12 16C9.79 16 8 14.21 8 12S9.79 8 12 8 16 9.79 16 12 14.21 16 12 16Z" />
            </svg>
            24/7 Support
        </div>
        <div class="feature">
            <svg class="feature-icon" viewBox="0 0 24 24">
                <path d="M19 3H5C3.89 3 3 3.89 3 5V19C3 20.11 3.89 21 5 21H19C20.11 21 21 20.11 21 19V5C21 3.89 20.11 3 19 3M19 19H5V5H19V19M17 12C17 9.24 14.76 7 12 7S7 9.24 7 12 9.24 17 12 17C13.38 17 14.64 16.44 15.54 15.54L17 17L18.41 15.59L17 14.17C17.59 13.27 18 12.04 18 12H17M12 15C10.34 15 9 13.66 9 12S10.34 9 12 9 15 10.34 15 12 13.66 15 12 15Z" />
            </svg>
            HIPAA Compliant
        </div>
    </div>

    <script>
        // Modal Functions
        function openForgotModal() {
            const modal = document.getElementById('forgotModal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeForgotModal() {
            const modal = document.getElementById('forgotModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';

            // Reset form
            document.getElementById('forgotPasswordForm').reset();
            document.getElementById('successMessage').classList.remove('show');
            document.getElementById('forgotPasswordForm').style.display = 'block';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('forgotModal');
            if (event.target === modal) {
                closeForgotModal();
            }
        }

        // Handle forgot password form submission
        function handleForgotPassword(event) {
            event.preventDefault();

            const submitBtn = document.getElementById('forgotSubmitBtn');
            const identifier = document.getElementById('forgot-identifier').value;
            const successMessage = document.getElementById('successMessage');
            const forgotForm = document.getElementById('forgotPasswordForm');

            // Show loading state
            submitBtn.innerHTML = 'Sending...';
            submitBtn.disabled = true;
            submitBtn.style.background = 'linear-gradient(135deg, #a0a0a0, #808080)';

            // Simulate API call (replace with actual AJAX call to your PHP script)
            setTimeout(() => {
                // Hide form and show success message
                forgotForm.style.display = 'none';
                successMessage.classList.add('show');

                // Here you would typically make an AJAX call to your PHP script
                // Example:
                /*
                fetch('forgot_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'identifier=' + encodeURIComponent(identifier)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        forgotForm.style.display = 'none';
                        successMessage.classList.add('show');
                    } else {
                        alert('Error: ' + data.message);
                        // Reset button state
                        submitBtn.innerHTML = 'Send Reset Instructions';
                        submitBtn.disabled = false;
                        submitBtn.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    // Reset button state
                    submitBtn.innerHTML = 'Send Reset Instructions';
                    submitBtn.disabled = false;
                    submitBtn.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                });
                */

                // Auto close modal after 5 seconds
                setTimeout(() => {
                    closeForgotModal();
                }, 5000);
            }, 2000);
        }

        // Add form validation and smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.login-form');
            const inputs = document.querySelectorAll('input');

            // Add focus animations
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Add form submission animation
            form.addEventListener('submit', function(e) {
                const submitBtn = document.querySelector('.login-btn');
                submitBtn.innerHTML = 'Signing In...';
                submitBtn.style.background = 'linear-gradient(135deg, #a0a0a0, #808080)';
                submitBtn.disabled = true;

                // Add loading animation
                setTimeout(() => {
                    submitBtn.innerHTML = 'Sign In ✓';
                }, 1000);
            });

            // Add typing animation to placeholders
            const placeholders = [
                'Enter your username',
                'Enter your password'
            ];

            inputs.forEach((input, index) => {
                if (index < 2) { // Only for main login form
                    const placeholder = placeholders[index];
                    let i = 0;

                    function typePlaceholder() {
                        if (i < placeholder.length) {
                            input.placeholder = placeholder.substring(0, i + 1);
                            i++;
                            setTimeout(typePlaceholder, 100);
                        }
                    }

                    // Start typing animation after a delay
                    setTimeout(() => {
                        input.placeholder = '';
                        typePlaceholder();
                    }, index * 1000);
                }
            });
        });

        // Add particle effect on click
        document.addEventListener('click', function(e) {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.left = e.clientX + 'px';
            particle.style.top = e.clientY + 'px';
            particle.style.width = '6px';
            particle.style.height = '6px';
            particle.style.background = 'rgba(255, 255, 255, 0.8)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '1000';
            particle.style.animation = 'particleFloat 1s ease-out forwards';

            document.body.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 1000);
        });

        // Add particle animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes particleFloat {
                0% {
                    transform: translateY(0) scale(1);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-100px) scale(0);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>