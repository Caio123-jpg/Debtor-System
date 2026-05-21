<?php
$file = "contas.json";

if (!file_exists($file)) {
    file_put_contents($file, "[]");
}

function lerContas()
{
    $data = file_get_contents("contas.json");
    return json_decode($data, true) ?: [];
}

function salvarContas($contas)
{
    file_put_contents("contas.json", json_encode($contas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Aqui começa a API:
$acao = $_GET['acao'] ?? '';

if ($acao === 'api') {
    header('Content-Type: application/json; charset=UTF-8');

    $method = $_SERVER['REQUEST_METHOD'];
    $tipo = $_GET['tipo'] ?? '';
    $code = $_GET['code'] ?? '';

    if ($method === 'POST' && $tipo === 'criar') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['code']) || !isset($input['favored']) || !isset($input['value'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'Campos obrigatórios faltando']);
            exit;
        }

        $contas = lerContas();

        foreach ($contas as $c) {
            if ($c['code'] === $input['code']) {
                http_response_code(400);
                echo json_encode(['erro' => 'Código já existe']);
                exit;
            }
        }

        $nova = [
            'code' => (string)$input['code'],
            'favored' => (string)$input['favored'],
            'maturity' => $input['maturity'] ?? '',
            'value' => (float)$input['value'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $contas[] = $nova;
        salvarContas($contas);

        http_response_code(201);
        echo json_encode($nova);
        exit;
    }

    if ($method === 'POST' && $tipo === 'deletar' && $code) {
        $contas = lerContas();

        foreach ($contas as $i => $c) {
            if ($c['code'] === $code) {
                unset($contas[$i]);
                salvarContas(array_values($contas));
                echo json_encode(['ok' => true]);
                exit;
            }
        }

        http_response_code(404);
        echo json_encode(['erro' => 'Não encontrado']);
        exit;
    }

    if ($method === 'POST' && $tipo === 'atualizar' && $code) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['favored']) || !isset($input['value'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'Campos obrigatórios faltando']);
            exit;
        }

        $contas = lerContas();
        $updated = false;

        foreach ($contas as $i => $c) {
            if ($c['code'] === $code) {
                $contas[$i]['favored'] = (string)$input['favored'];
                $contas[$i]['maturity'] = $input['maturity'] ?? '';
                $contas[$i]['value'] = (float)$input['value'];
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            http_response_code(404);
            echo json_encode(['erro' => 'Não encontrado']);
            exit;
        }

        salvarContas($contas);
        echo json_encode(['ok' => true]);
        exit;
    }

    if ($method === 'GET' && $tipo === 'listar') {
        $contas = lerContas();
        echo json_encode($contas);
        exit;
    }

    echo json_encode(['erro' => 'Requisição inválida']);
    exit;
}

// Aqui começa o HTML:
$contas = lerContas();
$total = 0;
foreach ($contas as $c) $total += $c['value'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas a Pagar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Contas a Pagar</h1>

        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nova Conta</h5>
                        <form id="form">
                            <div class="mb-3">
                                <p>Código:</p>
                                <input type="text" class="form-control" id="code" placeholder="Código" required>
                            </div>
                            <div class="mb-3">
                                <p>Favorecido:</p>
                                <input type="text" class="form-control" id="favored" placeholder="Favorecido" required>
                            </div>
                            <div class="mb-3">
                                <p>Data:</p>
                                <input type="date" class="form-control" id="maturity">
                            </div>
                            <div class="mb-3">
                                <p>Valor:</p>
                                <input type="number" step="0.01" class="form-control" id="value" placeholder="Valor" required>
                            </div>
                            <button type="submit" id="submitButton" class="btn btn-primary w-100">Cadastrar</button>
                            <button type="button" id="cancelEdit" class="btn btn-secondary w-100 mt-2 d-none">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contas Registradas</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Favorecido</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contas as $c): ?>
                                        <tr>
                                            <td><?= $c['code'] ?></td>
                                            <td><?= $c['favored'] ?></td>
                                            <td><?= $c['maturity'] ? date('d/m/Y', strtotime($c['maturity'])) : '-' ?></td>
                                            <td>R$ <?= number_format($c['value'], 2, ',', '.') ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning me-1" onclick="edit('<?= $c['code'] ?>')">Editar</button>
                                                <button class="btn btn-sm btn-danger" onclick="del('<?= $c['code'] ?>')">Del</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-3"><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const contas = <?= json_encode($contas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?>;
        let editMode = false;
        let editingCode = '';

        document.getElementById('form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const data = {
                code: document.getElementById('code').value,
                favored: document.getElementById('favored').value,
                maturity: document.getElementById('maturity').value,
                value: document.getElementById('value').value
            };

            const url = editMode && editingCode
                ? `?acao=api&tipo=atualizar&code=${encodeURIComponent(editingCode)}`
                : '?acao=api&tipo=criar';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const json = await res.json();

                if (res.ok) {
                    alert(editMode ? '✅ Atualizado!' : '✅ Cadastrado!');
                    location.reload();
                } else {
                    alert('X ' + json.erro);
                }
            } catch (e) {
                alert('X Erro: ' + e.message);
            }
        });

        document.getElementById('cancelEdit').addEventListener('click', () => {
            resetForm();
        });

        function edit(code) {
            const conta = contas.find((c) => c.code === code);
            if (!conta) {
                alert('Conta não encontrada');
                return;
            }

            editMode = true;
            editingCode = code;
            document.getElementById('code').value = conta.code;
            document.getElementById('favored').value = conta.favored;
            document.getElementById('maturity').value = conta.maturity ?? '';
            document.getElementById('value').value = conta.value;
            document.getElementById('code').readOnly = true;
            document.getElementById('submitButton').textContent = 'Salvar';
            document.getElementById('cancelEdit').classList.remove('d-none');
        }

        async function del(code) {
            if (confirm('Excluir?')) {
                const res = await fetch(`?acao=api&tipo=deletar&code=${code}`, {
                    method: 'POST'
                });
                if (res.ok) {
                    location.reload();
                }
            }
        }

        function resetForm() {
            editMode = false;
            editingCode = '';
            document.getElementById('form').reset();
            document.getElementById('code').readOnly = false;
            document.getElementById('submitButton').textContent = 'Cadastrar';
            document.getElementById('cancelEdit').classList.add('d-none');
        }
    </script>
</body>
</html>