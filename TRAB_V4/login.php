<?php
session_start();
require_once 'db.php';
require_once 'utils.php'; // Inclua a função de log

// Gera o token CSRF se não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($mysqli, $_POST['username']);
    $password = sanitize_input($mysqli, $_POST['senha']);
    $csrf_token = $_POST['csrf_token'];

    // Verifica o token CSRF
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $_SESSION['error'] = "Token CSRF inválido.";
        log_activity("Tentativa de login falhou: CSRF inválido para usuário '$username'.");
        header('Location: login.php');
        exit();
    }

    // Prepara a query usando prepared statements
    $stmt = $mysqli->prepare("SELECT id, senha, perfil, autenticacao_habilitada FROM usuarios WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['senha'])) {
            $_SESSION['userid'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['perfil'] = $user['perfil']; // Adiciona o perfil à sessão

            // Regenera o token CSRF após login bem-sucedido
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            log_activity("Usuário '$username' logado com sucesso.");

            // Redireciona para o local apropriado com base no perfil e autenticação em duas etapas
            if ($user['autenticacao_habilitada']) {
                header('Location: autenticacao.php');
            } else {
                $redirect_page = ($user['perfil'] === 'admin') ? 'dashboard.php' : 'dashboard_public.php';
                header('Location: ' . $redirect_page);
            }
            exit();
        } else {
            $_SESSION['error'] = "Credenciais incorretas.";
            log_activity("Tentativa de login falhou: Credenciais incorretas para usuário '$username'.");
        }
    } else {
        $_SESSION['error'] = "Usuário não encontrado.";
        log_activity("Tentativa de login falhou: Usuário '$username' não encontrado.");
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

        .login-container {
            width: 100%;
            max-width: 360px;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.9);
            text-align: left;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-size: 26px;
            font-weight: 600;
            color: #e50914;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            text-align: center;
        }

        .login-container label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #b3b3b3;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .login-container input[type="submit"],
        .login-container .btn-back {
            width: 100%;
            padding: 12px;
            background-color: #e50914;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .login-container input[type="submit"]:hover,
        .login-container .btn-back:hover {
            background-color: #f40612;
            transform: scale(1.05);
        }

        .login-container .btn-back {
            background-color: #333;
        }

        .error-message {
            color: #e50914;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <!-- Mensagem de erro PHP -->
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-message"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <!-- Formulário de login -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Nome de Usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="submit" value="Login">
        </form>
        <form action="index.php">
            <button type="submit" class="btn-back">Voltar para Index</button>
        </form>
    </div>
</body>
</html>
