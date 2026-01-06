<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Back Online Alert</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .content {
            padding: 40px 30px;
        }

        .success-box {
            background-color: #f0fff4;
            border-left: 5px solid #2ecc71;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details-table th,
        .details-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .details-table th {
            color: #7f8c8d;
            font-weight: 600;
            width: 30%;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #2ecc71;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
            transition: background 0.3s;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #2ecc71;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Service Restored</h1>
        </div>
        <div class="content">
            <div class="success-box">
                <p style="margin: 0; font-size: 18px; color: #27ae60; font-weight: bold;">
                    URL is Back Online!
                </p>
                <p style="margin: 5px 0 0 0;">
                    Good news! <strong>{{ $url->name }}</strong> is now reachable again.
                </p>
            </div>

            <table class="details-table">
                <tr>
                    <th>URL Name</th>
                    <td>{{ $url->name }}</td>
                </tr>
                <tr>
                    <th>URL Address</th>
                    <td><a href="{{ $url->url }}" style="color: #3498db;">{{ $url->url }}</a></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="status-badge">ONLINE / ACTIVE</span></td>
                </tr>
                <tr>
                    <th>Response Time</th>
                    <td>{{ $url->response_time }} ms</td>
                </tr>
                @if ($url->status_code)
                    <tr>
                        <th>Status Code</th>
                        <td>{{ $url->status_code }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Restored At</th>
                    <td>{{ $url->last_checked_at->format('M d, Y H:i:s') }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('url-management.show', $url->encrypted_id) }}" class="btn">View URL History</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated notification from Website Status Checker.</p>
            <p>&copy; {{ date('Y') }} Website Status Checker. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
