<!DOCTYPE html>
<html>
<head>
    <title>New Comment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
        	max-width: Auth::id()0px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: auto;
            margin-right: auto;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
        .content {
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        h2{
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="logo">
            <img src="{{ asset('/idea.svg') }}" alt="Application Logo">
        </div>

        <div class="content">
            <h2>New Comment on "{{ $ideaTitle }}"</h2>

            <p><strong>{{ $commentAuthor }}</strong> commented on <strong>{{ $commentDate }}</strong>:</p>

            <blockquote style="background: #f9f9f9; padding: 10px; border-left: 4px solid #007bff;">
                {{ $commentText }}
            </blockquote>

            <p>Visit the idea page to see more details.</p>

            <a href="{{ $ideaUrl }}" class="button">View Idea</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Idea. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
