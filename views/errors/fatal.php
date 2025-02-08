<?php
$env = $_ENV['APP_ENV'] ?: 'production'; // Default to production
global $caughtError; // Retrieve the error passed from index.php
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error (500)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(to bottom right, #2A0052, #4B0082, #8A2BE2);
        color: #E0E0FF;
        height: 100vh;
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 20px;
    }

    .error-header {
        text-align: center;
        padding: 20px;
        background: rgba(30, 0, 60, 0.8);
        box-shadow: 0 0 20px rgba(138, 43, 226, 0.6);
    }

    .error-title {
        color: #DDA0DD;
        /* Soft Neon Purple */
        font-size: 2.5rem;
        font-weight: bold;
    }

    .error-message {
        color: #BA55D3;
        /* Slightly lighter purple */
        font-size: 1.3rem;
        margin-top: 10px;
    }

    .content-container {
        max-width: 100%;
        margin: auto;
        padding: 30px;
    }

    .debug-section {
        background: rgba(20, 0, 40, 0.8);
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.4);
    }

    .debug-title {
        color: #FFDDFF;
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .debug-info {
        color: #E0E0FF;
        font-size: 1.1rem;
        overflow-x: auto;
        white-space: pre-wrap;
        word-wrap: break-word;
        padding: 10px;
        background: rgba(0, 0, 20, 0.7);
        border-radius: 5px;
    }

    .btn-home {
        background: #8A2BE2;
        color: white;
        padding: 12px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s;
        display: block;
        text-align: center;
        margin-top: 20px;
        width: 200px;
    }

    .btn-home:hover {
        background: #BA55D3;
    }
    </style>
</head>

<body>
    <div class="error-header">
        <h1 class="error-title">500 - Server Error</h1>
        <p class="error-message">Something went wrong on our end. Please try again later.</p>
    </div>

    <div class="content-container">

        <?php if ($env === 'dev' || $env === 'local'): ?>
        <div class="debug-section">
            <h2 class="debug-title">Debug Information</h2>
            <?php if ($caughtError): ?>
            <p><strong>Error:</strong> <?= htmlspecialchars($caughtError['message'] ?? 'Unknown Error') ?></p>
            <p><strong>File:</strong>
                <?= htmlspecialchars($caughtError['file'] ?? 'Unknown File') ?>:<?= $caughtError['line'] ?? '?' ?></p>
            <?php if (!empty($caughtError['trace'])): ?>
            <div class="debug-info">
                <strong>Stack Trace:</strong>
                <pre><?= htmlspecialchars($caughtError['trace']) ?></pre>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <p>No error details available.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>