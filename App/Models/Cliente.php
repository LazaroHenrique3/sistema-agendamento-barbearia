<?php

namespace App\Models;

use App\Models\Pessoa;

class Cliente extends Pessoa
{

    private $endereco;
    private $cep;

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }

    public function insert()
    {

        $query = "INSERT 
        INTO 
            cliente (nome, sexo, data_nascimento, telefone, email, cpf, endereco, cep)
        VALUES
            (:nome, :sexo, :data_nascimento, :telefone, :email, :cpf, :endereco, :cep)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sexo', $this->__get('sexo'));
        $stmt->bindValue(':data_nascimento', $this->__get('dataNascimento'));
        $stmt->bindValue(':telefone', $this->__get('telefone'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':cpf', $this->__get('cpf'));
        $stmt->bindValue(':endereco', $this->__get('endereco'));
        $stmt->bindValue(':cep', $this->__get('cep'));

        $stmt->execute();

        return $this;
    }

    public function update()
    {

        $query = "UPDATE 
        cliente
      SET
        nome = :nome,
        sexo = :sexo,
        data_nascimento = :data_nascimento,
        telefone = :telefone,
        email = :email,
        cpf = :cpf,
        endereco = :endereco,
        cep = :cep
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
        $stmt->bindValue(':endereco', $this->__get('endereco'));
        $stmt->bindValue(':cep', $this->__get('cep'));

        return $stmt->execute();
    }

    public function delete()
    {

        $query = "DELETE FROM cliente WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->id);

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
                $orderType = "endereco";
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
                    endereco, 
                    cep
                FROM cliente
                WHERE nome LIKE :name
                ORDER BY {$orderType} {$orderBy}
                LIMIT {$inicio}, {$qnt_result_pg}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

            case '5':
                $orderType = "endereco";
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
                    endereco, 
                    cep
                 FROM cliente
                 WHERE nome LIKE :name
                 ORDER BY {$orderType} {$orderBy}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function countClientes($name = "")
    {

        $query = "SELECT count(*) AS total FROM cliente WHERE nome LIKE :name";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function duplicacaoEmail()
    {

        $query = "SELECT count(*) AS numCliente FROM cliente WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numCliente'] == 0) ? false : true;
    }

    public function duplicacaoAlterarEmail()
    {

        $query = "SELECT count(*) AS numCliente FROM cliente WHERE email = :email AND id <> :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numCliente'] == 0) ? false : true;
    }

    public function duplicacaoCpf()
    {

        $query = "SELECT count(*) AS numCliente FROM cliente WHERE cpf = :cpf";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':cpf', $this->cpf);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numCliente'] == 0) ? false : true;
    }

    public function duplicacaoAlterarCpf()
    {

        $query = "SELECT count(*) AS numCliente FROM cliente WHERE cpf = :cpf AND id <> :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':cpf', $this->cpf);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numCliente'] == 0) ? false : true;
    }
}
