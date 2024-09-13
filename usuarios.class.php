<?php

require_once 'conexao.class.php';
class Usuarios
{

    public function salvar(string $nome, string $email): void
    {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            echo json_encode(["msg" => "Usuário cadastrado com sucesso", "status" => 200], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["msg" => "Erro ao cadastrar o usuário", "status" => 501], JSON_UNESCAPED_UNICODE);
            ;
        }
    }

    public function excluir(int $id)
    {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "DELETE FROM usuarios WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo json_encode(["msg" => "Usuário excluido com sucesso", "status" => 200], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["msg" => "Erro ao excluir o usuário", "status" => 501], JSON_UNESCAPED_UNICODE);
        }
    }

    public function listar()
    {
        $conexao = new Conexao();
        $pdo = $conexao->getPDO();

        $sql = "SELECT id, nome, email FROM usuarios";

        $stmt = $pdo->query($sql);

        $usuarios = [];
        foreach ($stmt as $key => $dado) {
            $linha = [];
            $linha[] = $dado['id'];
            $linha[] = $dado['nome'];
            $linha[] = $dado['email'];
            $linha[] = "<a href='#' onclick='excluirUsuario(" . $dado["id"] . ")' class='btn btn-danger'>Excluir</a>";
            $usuarios[] = (object)$linha;
        }

        $retorno_dados = [
            "draw" => intval(count($usuarios)),
            "recordsTotal" => intval(count($usuarios)),
            "recordsFiltered" => intval(count($usuarios)),
            "data" => isset($usuarios) ? $usuarios : []
        ];

        echo json_encode($retorno_dados);
    }

}

$acao = $_POST['action'];
switch ($acao) {
    case 'salvar':
        if (isset($_POST['nome']) && !empty($_POST['nome'])) {
            $nome = $_POST['nome'];
        } else {
            echo json_encode(["msg" => "Nome não pode ser em branco", "status" => 501], JSON_UNESCAPED_UNICODE);
            return false;
        }

        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            echo json_encode(["msg" => "E-mail não pode ser em branco", "status" => 501], JSON_UNESCAPED_UNICODE);
            return false;
        }

        (new Usuarios)->salvar($nome, $email);
        break;
    case 'deletar':
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            (new Usuarios)->excluir($id);
        } else {
            echo json_encode(["msg" => "ID do usuário não informado", "status" => 501], JSON_UNESCAPED_UNICODE);
            return false;
        }
        break;
    case 'listar':
        (new Usuarios)->listar();
        break;
    default:
        break;
}