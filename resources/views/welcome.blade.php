<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GensanMed Landing Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* background: url('{{ asset('img/background.jpg') }}') no-repeat center center fixed; */
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8); /* Add transparency */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
            color: white;
            background-color: #FF2D20;
            border: 2px solid #FF2D20;
        }

        a:hover {
            background-color: white;
            color: #FF2D20;
            border: 2px solid #FF2D20;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Our Website</h1>
        <p>We're glad you're here! Please log in or register to get started.</p>

        <a href="{{ route('login') }}">
            Log in
        </a>

        <!-- @if (Route::has('register'))
            <a href="{{ route('register') }}">
                Register
            </a>
        @endif -->
    </div>
</body>
</html>
