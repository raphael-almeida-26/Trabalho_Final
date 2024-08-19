<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}

require_once 'db.php';

$userid = $_SESSION['userid'];

// Verifica se a autenticação em duas etapas já está habilitada
$sql_check_auth = "SELECT autenticacao_habilitada, codigo_autenticacao, perfil FROM usuarios WHERE id=?";
$stmt_check_auth = $mysqli->prepare($sql_check_auth);
$stmt_check_auth->bind_param("i", $userid);
$stmt_check_auth->execute();
$result_check_auth = $stmt_check_auth->get_result();

if ($result_check_auth->num_rows > 0) {
    $row = $result_check_auth->fetch_assoc();
    if (!$row['autenticacao_habilitada']) {
        $_SESSION['message'] = "Autenticação em duas etapas não está habilitada.";
        if ($row['perfil'] == 'admin') {
            header('Location: dashboard.php');
        } else {
            header('Location: dashboard_public.php');
        }
        exit();
    }

    $codigo_autenticacao = $row['codigo_autenticacao'];
    if (!$codigo_autenticacao) {
        $codigo_autenticacao = rand(100000, 999999);
        $sql_update = "UPDATE usuarios SET codigo_autenticacao=? WHERE id=?";
        $stmt_update = $mysqli->prepare($sql_update);
        $stmt_update->bind_param("ii", $codigo_autenticacao, $userid);
        $stmt_update->execute();
        $stmt_update->close();
    }
} else {
    $_SESSION['error'] = "Erro ao verificar autenticação em duas etapas.";
    if ($row['perfil'] == 'admin') {
        header('Location: dashboard.php');
    } else {
        header('Location: dashboard_public.php');
    }
    exit();
}

$stmt_check_auth->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Autenticação em Duas Etapas</title>
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

        .container {
            width: 100%;
            max-width: 400px;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.9);
            text-align: left;
        }

        h2 {
            font-size: 26px;
            font-weight: 600;
            color: #e50914;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #b3b3b3;
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #e50914;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #f40612;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: #333;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: rgba(50, 50, 50, 0.9);
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
            color: #fff;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #bbb;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            cursor: pointer;
        }

        p {
            color: #b3b3b3;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Autenticação em Duas Etapas</h2>
        <!-- Mensagem de erro -->
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: #e50914;"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <!-- Mensagem de sucesso -->
        <?php if (isset($_SESSION['message'])): ?>
            <p style="color: #28a745;"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <p>Um código de autenticação foi enviado para você. Por favor, insira o código abaixo:</p>
        <form action="verificar_codigo.php" method="post">
            <div class="form-group">
                <label for="codigo">Código de Autenticação:</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Verificar Código" class="btn">
                <button type="button" class="btn btn-secondary" id="showCodigo">Mostrar Código de Autenticação</button>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div id="modalCodigo" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Código de Autenticação</h3>
            <p>O código de autenticação é: <strong><?php echo $codigo_autenticacao; ?></strong></p>
            <p>Use este código para completar o processo de autenticação em duas etapas.</p>
        </div>
    </div>

    <script>
        // Mostrar modal ao clicar no botão
        document.getElementById('showCodigo').addEventListener('click', function() {
            var modal = document.getElementById('modalCodigo');
            modal.style.display = 'flex';

            // Fechar modal ao clicar no botão de fechar
            var closeBtn = document.getElementsByClassName('close')[0];
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // Fechar modal ao clicar fora do conteúdo do modal
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

