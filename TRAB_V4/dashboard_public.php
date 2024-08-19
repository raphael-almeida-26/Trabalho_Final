<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Público</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('netflix.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .dashboard-container {
            width: 100%;
            max-width: 400px;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.9);
            text-align: center;
        }

        .dashboard-container h1 {
            font-size: 28px;
            font-weight: 600;
            color: #e50914;
            margin-bottom: 20px;
        }

        .dashboard-container p {
            font-size: 16px;
            color: #b3b3b3;
            margin-bottom: 30px;
        }

        .dashboard-container a {
            display: block;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #e50914;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .dashboard-container a:hover {
            background-color: #f40612;
            transform: scale(1.05);
        }

        .dashboard-container .logout-link {
            background-color: #333;
        }

        .dashboard-container .logout-link:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Bem-vindo a netflix paguem 100R$ POR MES para ter acesso a nosso cataloco , <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Você so precisa colocar o numero do seu cartao e a senha em nosso email para poder ser contemplado com nossos incriveis filmes.</p>

        <a href="logout.php" class="logout-link">Sair</a>
    </div>
</body>
</html>
