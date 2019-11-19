<div class="card card-signin my-5">
    <div class="card-body">
        <h1>Lista de clientes</h1><br><br>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>DNI</th>
                        <th>PATENTE</th>
                        <th>Tipo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td>
                            <?=htmlspecialchars($cliente['DNI'], ENT_QUOTES, 'UTF-8')?>
                        </td>
                        <td>
                            <?=htmlspecialchars($cliente['PATENTE'], ENT_QUOTES, 'UTF-8')?>
                        </td>
                        <td>
                            <?=htmlspecialchars($cliente['DESCRIPCION'], ENT_QUOTES, 'UTF-8')?>
                        </td>
                        <td>
                            <form action="./../functions/editar-cliente.php" method="post">
                                <input type="hidden" name="editarCliente" value="<?=$cliente['DNI']?>">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-user-edit"></i> Editar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>