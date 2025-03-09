<!DOCTYPE html>
<html>
<head>
    <title>New Idea Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
        	max-width: 800px;
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
            max-width: 350px;
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
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        h1{
            text-align: center
        }
        h2{
            text-align: center;
            margin-top: 5px;
            padding-bottom: 15px;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="logo">
            <img src="{{ asset('/idea.svg') }}" alt="Application Logo">
        </div>

        <div class="content">
            <h1>New Idea posted</h1>
            <h2>"{{ $ideaTitle }}"</h2>

            <p><strong>Posted by:</strong> {{ $postedBy }}</p>
            <p><strong>Posted Date:</strong> {{ $postedDate }}</p>

            <h3>Idea Content:</h3>
            <p style="background: #f9f9f9; padding: 10px; border-left: 4px solid #28a745;">
                {{ $ideaContent }}
            </p>

            <p>Click below to submit the Idea.</p>

            <a href="{{ $ideaUrl }}" class="button">Submit Idea</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Idea. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
