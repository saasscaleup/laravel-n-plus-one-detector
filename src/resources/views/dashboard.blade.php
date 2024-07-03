<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>N+1 Query Detector Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
        }
        pre {
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">N+1 Query Detector Dashboard</h1>
        <div id="logs" class="bg-white p-4 rounded shadow-sm">
            Loading logs...
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/api/n-plus-one-logs')
                .then(response => response.json())
                .then(data => {
                    const logsElement = document.getElementById('logs');
                    if (data.logs) {
                        logsElement.innerHTML = `<pre>${data.logs}</pre>`;
                    } else {
                        logsElement.innerHTML = '<div class="text-muted">No logs available.</div>';
                    }
                });
        });
    </script>
</body>
</html>