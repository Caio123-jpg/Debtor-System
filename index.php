<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas a Pagar - CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fb;
            color: #334155;
        }
        .page-header {
            background: linear-gradient(135deg, #eef2ff 0%, #ffffff 100%);
            border: 1px solid #dbeafe;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }
        .card-body {
            background-color: #ffffff;
        }
        .table thead th {
            border-bottom: 2px solid #e2e8f0;
        }
        .total-row {
            background-color: #f8fafc;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="page-header rounded-4 p-4 mb-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="h3 mb-2">Contas a Pagar</h1>
                </div>
                <div class="text-md-end">
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">
                        <h2 class="h5 mb-4">Cadastrar nova conta</h2>
                        <form>
                            <div class="mb-3">
                                <label for="code" class="form-label">Código</label>
                                <input type="text" class="form-control" id="code" placeholder="Digite o código da conta">
                            </div>
                            <div class="mb-3">
                                <label for="favored" class="form-label">Favorecido (Credor)</label>
                                <input type="text" class="form-control" id="favored" placeholder="Nome do favorecido">
                            </div>
                            <div class="mb-3">
                                <label for="maturity" class="form-label">Vencimento</label>
                                <input type="date" class="form-control" id="maturity">
                            </div>
                            <div class="mb-4">
                                <label for="value" class="form-label">Valor (R$)</label>
                                <input type="number" class="form-control" id="value" placeholder="0,00">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary w-100">Cadastrar Conta</button>
                                <button type="reset" class="btn btn-outline-secondary w-100">Limpar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h2 class="h5 mb-1">Contas registradas</h2>
                                <p class="text-muted mb-0">Visualize as contas que precisam ser pagas.</p>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">0 contas</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="bg-light rounded-3">
                                    <tr>
                                        <th class="ps-0">Código</th>
                                        <th>Favorecido</th>
                                        <th>Vencimento</th>
                                        <th class="text-end">Valor (R$)</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="accounts-table-body">
                                    <tr>
                                        <td class="ps-0 text-muted" colspan="5">Nenhuma conta cadastrada</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td class="ps-0" colspan="3">Total</td>
                                        <td class="text-end">R$ 0,00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-E40g+2Q0i1a0v8wE2Fw0HBC80qT1mv/KjIx5SPCNt3XpCyqKc47H6jsP1Nn/2Z0p" 
    crossorigin="anonymous">
    </script>
</body>
</html>
