<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
   <style>
        body {
            background-color: #f0f2f5;
        }
        .account-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .account-card:hover {
            transform: translateY(-5px);
        }
        .account-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 12px 12px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .task-item {
            background-color: #e9ecef;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9rem;
            color: #333;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Facebook Accounts</h2>

        @foreach ($data['data'] as $account)
        <a href="{{ url('pages/'.$account['id'] .'/'. $account['access_token']) }}">
            <div class="account-card">
                <!-- Card Header with Account Name -->
                <div class="account-header">
                    <h5>{{ $account['name'] }}</h5>
                    <small>ID: {{ $account['id'] }}</small>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <p>
                        <strong>Access Token:</strong>
                        {{ Str::substr($account['access_token'], 0, 20) }}...
                        <i class="fas fa-copy ms-2 copy-icon"
                           onclick="copyToClipboard('{{ $account['access_token'] }}')"
                           title="Copy Access Token"
                           style="cursor: pointer; color: #007bff;">
                        </i>
                    </p>
                    <p><strong>Category:</strong> {{ $account['category'] }}</p>

                    <!-- Category List -->
                    <p><strong>Category List:</strong></p>
                    <ul class="list-unstyled">
                        @foreach ($account['category_list'] as $category)
                            <li>- {{ $category['name'] }} (ID: {{ $category['id'] }})</li>
                        @endforeach
                    </ul>

                    <!-- Tasks -->
                    <p><strong>Tasks:</strong></p>
                    <div>
                        @foreach ($account['tasks'] as $task)
                            <span class="task-item">{{ $task }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert("Access Token copied to clipboard!");
            }, function(err) {
                console.error("Could not copy text: ", err);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
