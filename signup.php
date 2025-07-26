<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 400px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, color 0.3s;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--heading-color);
        }

        .form-control {
            border-radius: 30px;
            border: 1px solid #00796b;
        }

        .btn-primary {
            background-color: #00796b;
            border-color: #00796b;
            border-radius: 30px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #004d40;
            border-color: #004d40;
        }

        .toggle-password {
            cursor: pointer;
            float: right;
            margin-right: 10px;
            margin-top: -30px;
            position: relative;
            z-index: 2;
        }

        /* Light Theme */
        :root {
            --background-color: #e0f7fa;
            --text-color: #004d40;
            --heading-color: #00796b;
        }

        /* Dark Theme */
        body.dark-theme {
            --background-color: #121212;
            --text-color: #e0e0e0;
            --heading-color: #1db954;
        }

        body.dark-theme .container {
            background-color: #1c1c1c;
            color: #e0e0e0;
        }

        .btn-toggle {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px;
            transition: background-color 0.3s;
            position: fixed;
            bottom: 20px;
            left: 20px;
        }

        .btn-toggle:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword()">
                            <i id="togglePasswordIcon" class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
        </form>
        <p class="mt-3">Already have an account? <a href="index.php">Sign In</a></p>
    </div>
    <button id="theme-toggle" class="btn-toggle">Toggle to Dark</button>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const toggleIcon = document.getElementById("togglePasswordIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        // Theme Toggle Script
        document.addEventListener("DOMContentLoaded", function() {
            const themeToggle = document.getElementById('theme-toggle');
            const currentTheme = localStorage.getItem('theme') || 'light';

            if (currentTheme === 'dark') {
                document.body.classList.add('dark-theme');
                themeToggle.textContent = 'Toggle to Light';
            }

            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-theme');
                const newTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
                localStorage.setItem('theme', newTheme);

                themeToggle.textContent = newTheme === 'dark' ? 'Toggle to Light' : 'Toggle to Dark';
            });
        });
    </script>
</body>
</html>
