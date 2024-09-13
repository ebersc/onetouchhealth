<?php

class Conexao
{
    private $host = 'localhost'; // Host do banco de dados
    private $db = 'onetouchhealth'; // Nome do banco de dados
    private $user = 'root'; // Nome de usuário do banco de dados
    private $pass = ''; // Senha do banco de dados
    private $charset = 'utf8mb4'; // Conjunto de caracteres
    private $pdo;
    

    function __construct(){
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Exibe exceções de erro
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Define o modo de busca padrão para associativo
            PDO::ATTR_EMULATE_PREPARES => false, // Desativa emulação de preparação de instruções
        ];

        try {
            // Criando uma nova instância PDO
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Capturando erros de conexão
            echo "Erro na conexão: " . $e->getMessage();
        }
    }


    public function getPDO(): PDO
    {
        return $this->pdo;
    }

}