<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Not Found | KAH TECH</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .error-container {
            background: #ffffff;
            padding: 32px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 420px;
            width: 100%;
        }
        .error-logo {
            height: 100px; /* control the visible space */
            overflow: hidden; /* hides extra padding in image */
        }
        .error-logo img {
            height: 150px; /* make logo bigger */
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .error-code {
            font-size: 48px;
            font-weight: 700;
            color: #ef4444;
            margin-top: 8px;
            margin-bottom: 8px;
        }
        .error-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .error-message {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 20px;
        }
        .actions a {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #111827;
            transition: 0.15s ease;
            margin: 0 4px;
        }
        .actions a.primary {
            background: #111827;
            color: #ffffff;
        }
        .actions a.primary:hover {
            background: #1f2937;
        }
        .hint {
            margin-top: 12px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-logo">
            <!-- adjust path if needed -->
            <img src="/Image/logo.png" alt="KAH TECH Logo">
        </div>
        <div class="error-code">404</div>
        <div class="error-title">Page not found</div>
        <p class="error-message">
            The page you’re looking for doesn’t exist, has been moved, or the link is incorrect.
        </p>
        <div class="actions">
            <a href="/" class="primary">Back to Home</a>
        </div>
        <p class="hint">If the problem continues, please contact the site administrator.</p>
    </div>
</body>
</html>
