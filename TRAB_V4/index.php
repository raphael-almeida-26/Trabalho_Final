<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('netflix.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 360px;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.9);
            text-align: left;
        }

        .container h2 {
            margin-bottom: 30px;
            font-size: 26px;
            font-weight: 600;
            color: #e50914;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .container a {
            display: block;
            margin-bottom: 20px;
            padding: 15px 0;
            background-color: #e50914;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .container a:hover {
            background-color: #f40612;
            transform: scale(1.05);
        }

        .container a:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bem-vindo!</h2>
        <a href="login.php">Login</a>
        <a href="register.php">Registrar</a>
    </div>
</body>
</html>
