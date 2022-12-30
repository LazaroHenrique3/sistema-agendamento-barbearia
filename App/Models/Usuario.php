<?php

namespace App\Models;

use App\Models\Pessoa;

class Usuario extends Pessoa
{

    private $senha;
    private $nivelAcesso;

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }

    public function authenticate()
    {

        //Verifica se há algum token de senha para esse usuário e se ele está valido
        $existeTokenSenha = $this->existeTokenSenha();

        //Comparando verificando se há token de senha
        if ($existeTokenSenha['expiracao'] != null) {

             //Definindo o fuso
             date_default_timezone_set('America/Sao_Paulo');

            //Significa que o token expirou
            if(date('Y-m-d H:i:s') > $existeTokenSenha['expiracao']){
                //Reseta os tokens para null e então tenta validar
                $this->resetTokenSenha();
                $this->validaLogin();
            } else {
                if($this->validaLogin()){
                    $this->resetTokenSenha();
                }
            }
          
        } else {
            $this->validaLogin();
        }

        //Retorno o próprio objeto
        return $this;
    }

    private function validaLogin()
    {
        $query = "SELECT 
                    id, nome, cpf, sexo, data_nascimento, telefone, email, nivel_acesso, senha, token_senha
                  FROM 
                    usuario 
                  WHERE 
                    email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));

        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        //Verifica se foi retornado valores para esses index
        if ($usuario['id'] != '' && password_verify($this->__get('senha'), $usuario['senha']) || password_verify($this->__get('senha'), $usuario['token_senha'])) {
            //Setando os valores no próprio objeto que posteriormente sera seta na sessão dentro do AuthController
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
            $this->__set('cpf', $usuario['cpf']);
            $this->__set('sexo', $usuario['sexo']);
            $this->__set('dataNascimento', $usuario['data_nascimento']);
            $this->__set('telefone', $usuario['telefone']);
            $this->__set('email', $usuario['email']);
            $this->__set('nivelAcesso', $usuario['nivel_acesso']);

            return true;
        } else {
            return false;
        }
    }

    //Verifica se há algum token de senha
    private function existeTokenSenha()
    {

        $query = "SELECT 
                    token_senha_solicitacao AS solicitacao, 
                    token_senha_expiracao AS expiracao 
                FROM 
                    usuario 
                WHERE 
                    email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //Reseta as colunas relacionadas ao token de senha para null
    private function resetTokenSenha()
    {

        $query = "UPDATE 
                    usuario 
                  SET 
                    token_senha = null,
                    token_senha_solicitacao = null,
                    token_senha_expiracao = null
                 WHERE 
                    email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function insert()
    {

        $query = "INSERT 
        INTO 
            usuario (nome, sexo, data_nascimento, telefone, email, cpf, senha, nivel_acesso)
        VALUES
            (:nome, :sexo, :data_nascimento, :telefone, :email, :cpf, :senha, :nivelAcesso)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sexo', $this->__get('sexo'));
        $stmt->bindValue(':data_nascimento', $this->__get('dataNascimento'));
        $stmt->bindValue(':telefone', $this->__get('telefone'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':cpf', $this->__get('cpf'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->bindValue(':nivelAcesso', $this->__get('nivelAcesso'));

        $stmt->execute();

        return $this;
    }

    public function update()
    {

        $sqlAdicional = "";

        if ($this->__get('nivelAcesso') != null || $this->__get('nivelAcesso') != '') {
            $sqlAdicional .= ",  nivel_acesso = :nivelAcesso";
        }

        if ($this->__get('senha') != null || $this->__get('senha') != '') {
            $sqlAdicional .= ",  senha = :senha";
        }

        $query = "UPDATE 
        usuario
      SET
        nome = :nome,
        sexo = :sexo,
        data_nascimento = :data_nascimento,
        telefone = :telefone,
        email = :email,
        cpf = :cpf
        " . $sqlAdicional . "
      WHERE 
        id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sexo', $this->__get('sexo'));
        $stmt->bindValue(':data_nascimento', $this->__get('dataNascimento'));
        $stmt->bindValue(':telefone', $this->__get('telefone'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':cpf', $this->__get('cpf'));

        if ($this->__get('nivelAcesso') != null || $this->__get('nivelAcesso') != '') {
            $stmt->bindValue(':nivelAcesso', $this->__get('nivelAcesso'));
        }

        if ($this->__get('senha') != null || $this->__get('senha') != '') {
            $stmt->bindValue(':senha', $this->__get('senha'));
        }

        $stmt->execute();

        //Retornando o bjeto atualizado
        return $this->selectUsuarioById();
    }

    public function setarTokenSenha($tokenSenha, $inicio, $expiracao)
    {

        $query = "UPDATE 
                    usuario 
                  SET 
                    token_senha = :tokenSenha,
                    token_senha_solicitacao = :inicio,
                    token_senha_expiracao = :expiracao
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':tokenSenha', $tokenSenha);
        $stmt->bindValue(':inicio', $inicio);
        $stmt->bindValue(':expiracao', $expiracao);
        $stmt->bindValue(':id', $this->__get('id'));

        $stmt->execute();
    }

    public function delete()
    {

        $query = "DELETE FROM usuario WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));

        $stmt->execute();
    }

    public function selectOrderName($name, $order, $orderType, $inicio, $qnt_result_pg)
    {
        $orderBy = ($order == 1) ? "ASC" : "DESC";

        switch ($orderType) {
            case '1':
                $orderType = "id";
                break;

            case '2':
                $orderType = "nome";
                break;

            case '3':
                $orderType = "sexo";
                break;

            case '4':
                $orderType = "data_nascimento";
                break;

            case '5':
                $orderType = "nivel_acesso";
                break;

            default:
                $orderType = "id";
                break;
        }

        $query = "SELECT
                    id,
                    nome, 
                    sexo, 
                    data_nascimento, 
                    telefone, 
                    email, 
                    cpf, 
                    nivel_acesso
                FROM usuario
                WHERE nome LIKE :name
                ORDER BY {$orderType} {$orderBy}
                LIMIT {$inicio}, {$qnt_result_pg}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectUsuarioById()
    {

        $query = "SELECT
                    id,
                    nome, 
                    sexo, 
                    data_nascimento, 
                    telefone, 
                    email, 
                    cpf
                FROM usuario
                WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectUsuarioByEmail()
    {

        $query = "SELECT
                    id,
                    nome, 
                    sexo, 
                    data_nascimento, 
                    telefone, 
                    email, 
                    cpf
                FROM usuario
                WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectAll($name = "", $orderType = 1, $order = 1)
    {

        $orderBy = ($order == 1) ? "ASC" : "DESC";

        switch ($orderType) {
            case '1':
                $orderType = "id";
                break;

            case '2':
                $orderType = "nome";
                break;

            case '3':
                $orderType = "sexo";
                break;

            case '4':
                $orderType = "data_nascimento";
                break;

            case '4':
                $orderType = "nivel_acesso";
                break;

            default:
                $orderType = "id";
                break;
        }

        $query = "SELECT
                    id,
                    nome, 
                    sexo, 
                    data_nascimento, 
                    telefone, 
                    email, 
                    cpf, 
                    nivel_acesso
                 FROM usuario
                 WHERE nome LIKE :name
                 ORDER BY {$orderType} {$orderBy}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function countUsuarios($name = "")
    {

        $query = "SELECT count(*) AS total FROM usuario WHERE nome LIKE :name";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function duplicacaoEmail()
    {

        $query = "SELECT count(*) AS numUsuario FROM usuario WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numUsuario'] == 0) ? false : true;
    }

    public function duplicacaoAlterarEmail()
    {

        $query = "SELECT count(*) AS numUsuario FROM usuario WHERE email = :email AND id <> :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numUsuario'] == 0) ? false : true;
    }

    public function duplicacaoCpf()
    {

        $query = "SELECT count(*) AS numUsuario FROM usuario WHERE cpf = :cpf";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':cpf', $this->cpf);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numUsuario'] == 0) ? false : true;
    }

    public function duplicacaoAlterarCpf()
    {

        $query = "SELECT count(*) AS numUsuario FROM usuario WHERE cpf = :cpf AND id <> :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':cpf', $this->cpf);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numUsuario'] == 0) ? false : true;
    }
}
