<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <style>
        /* General email styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f4f9; /* Light background color */
            color: #444; /* Dark text color for contrast */
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff; /* White background for the content */
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        .email-header {
            background-color: #1a3a5a; /* Dark blue header */
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold; /* Bold only for the heading */
        }
        .email-content {
            padding: 15px;
            line-height: 1.6;
            color: #444;
        }
        .email-content div {
            margin-bottom: 15px;
        }
        .email-content span {
            /* font-weight: bold; */
            color: #1a3a5a; /* Dark blue for labels */
        }
        .email-content a {
            color: #387478; /* Light blue for links */
            text-decoration: none;
        }
        .email-footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777;
            background-color: #f1f4f9;
            border-radius: 0 0 8px 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="email-header">
            <h2>You have received a notification from Connexo</h2>
        </div>

        <!-- Email Body Content -->
        <div class="email-content">
            <div>
                <span style="font-weight: bold">Subject :</span> {{ $request->subject }}
            </div>
            <div>
                <span style="font-weight: bold">Summary :</span> {{ $request->summary }}
            </div>
            <div>
                <span style="font-weight: bold">File :</span>
                <a href="{{ asset('uploads/' . $file) }}" target="_blank">{{ $file }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© {{ date('Y') }} Connexo  All rights reserved.</p>
        </div>
    </div>
</body>
</html>
