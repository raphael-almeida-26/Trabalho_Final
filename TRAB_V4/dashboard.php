<?php
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['perfil'] != 'admin') {
    header('Location: dashboard_public.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('netflix.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .dashboard-container {
            width: 100%;
            max-width: 400px;
            background-color: rgba(0, 0, 0, 0.85);
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
            margin-bottom: 30px;
            color: #b3b3b3;
        }

        .dashboard-container a {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #e50914;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .dashboard-container a:hover {
            background-color: #f40612;
            transform: scale(1.05);
        }

        .dashboard-container .logout-link {
            background-color: #d9534f;
        }

        .dashboard-container .logout-link:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Bem-vindo ao Painel de Administração, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Aqui você pode gerenciar o sistema.</p>

        <a href="criar_backup.php">Criar Backup</a>
        <a href="logout.php" class="logout-link">Sair</a>
    </div>
</body>
</html>
