<?php

namespace App\Models;

use MF\Model\Model;

class Servico extends Model
{

    private $id;
    private $descricao;
    private $valor;

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
            servico (descricao, valor)
        VALUES
            (:descricao, :valor)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':descricao', $this->__get('descricao'));
        $stmt->bindValue(':valor', $this->__get('valor'));

        $stmt->execute();

        return $this;
    }

    public function update()
    {

        $query = "UPDATE 
        servico
      SET
        descricao = :descricao,
        valor = :valor
      WHERE 
        id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':descricao', $this->__get('descricao'));
        $stmt->bindValue(':valor', $this->__get('valor'));

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM servico WHERE id = :id";

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
                $orderType = "descricao";
                break;

            case '3':
                $orderType = "valor";

            default:
                $orderType = "id";
                break;
        }

        $query = "SELECT
                    id,
                    descricao, 
                    valor
                FROM servico
                WHERE descricao LIKE :descricao
                ORDER BY {$orderType} {$orderBy}
                LIMIT {$inicio}, {$qnt_result_pg}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':descricao', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function countServicos($name = "")
    {

        $query = "SELECT count(*) AS total FROM servico WHERE descricao LIKE :descricao";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':descricao', "%" . $name . "%");
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
                $orderType = "descricao";
                break;

            case '3':
                $orderType = "valor";
                break;

            default:
                $orderType = "id";
                break;
        }

        $query = "SELECT
                    id,
                    descricao, 
                    valor
                 FROM servico
                 WHERE descricao LIKE :descricao
                 ORDER BY {$orderType} {$orderBy}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':descricao', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function countServico($descricao = "")
    {

        $query = "SELECT count(*) AS total FROM servico WHERE descricao LIKE :descricao";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':descricao', "%" . $descricao . "%");
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function duplicacaoDescricao()
    {

        $query = "SELECT count(*) AS numServico FROM servico WHERE descricao = :descricao";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':descricao', $this->descricao);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numServico'] == 0) ? false : true;
    }

    public function duplicacaoAlterarDescricao()
    {

        $query = "SELECT count(*) AS numServico FROM servico WHERE descricao = :descricao AND id <> :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':descricao', $this->descricao);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numServico'] == 0) ? false : true;
    }
}
