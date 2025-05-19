<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // Conectar ao PostgreSQL
    $conn = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=bserver', 'bsuser', 'bsXYA2o1w63a9zQ');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Captura dos dados
    $username = strtoupper(trim($_POST['username'] ?? ''));
    $senha = trim($_POST['value'] ?? '');
    $fullname = strtoupper(trim($_POST['fullname'] ?? 'CADASTRO CIDADE CONECTADA'));
    $groupname = 'MIKROTIK_PUBLICA'; // perfil fixo

    if (!$username || !$senha) {
        echo json_encode(['erro' => 'Campos obrigatórios ausentes']);
        exit;
    }

    // Verificar se já existe Cleartext-Password
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM radcheck WHERE username = :username AND attribute = 'Cleartext-Password'");
    $stmtCheck->execute([':username' => $username]);

    if ($stmtCheck->fetchColumn() > 0) {
        echo json_encode(['erro' => 'Usuário já cadastrado']);
        exit;
    }

    // Obter timestamp formatado sem milissegundos
    $stmtNow = $conn->query("SELECT TO_CHAR(NOW(), 'YYYY-MM-DD HH24:MI:SS') AS now");
    $now = $stmtNow->fetch(PDO::FETCH_ASSOC)['now'];

    // Inserir Cleartext-Password
    $stmt1 = $conn->prepare("INSERT INTO radcheck (identity, users, username, attribute, op, value, description, created) 
                             VALUES (1, 1, :username, 'Cleartext-Password', ':=', :senha, :desc, :created)");
    $stmt1->execute([
        ':username' => $username,
        ':senha' => $senha,
        ':desc' => $fullname,
        ':created' => $now
    ]);

    // Inserir Simultaneous-Use = 1
    $stmt2 = $conn->prepare("INSERT INTO radcheck (identity, users, username, attribute, op, value, description, created) 
                             VALUES (1, 1, :username, 'Simultaneous-Use', ':=', '1', :desc, :created)");
    $stmt2->execute([
        ':username' => $username,
        ':desc' => $fullname,
        ':created' => $now
    ]);

    // Inserir no radusergroup (não possui campo "created", mas se tiver, adicione similarmente)
    $stmt3 = $conn->prepare("INSERT INTO radusergroup (identity, users, username, groupname, priority)
                             VALUES (1, 1, :username, :groupname, 0)");
    $stmt3->execute([
        ':username' => $username,
        ':groupname' => $groupname
    ]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso']);

} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
