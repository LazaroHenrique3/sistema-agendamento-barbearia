<?php

namespace App\Models;

use MF\Model\Model;

class Agendamento extends Model
{

    private $id;
    private $statusAgendamento;
    private $idCliente;
    private $idServico;
    private $valor;
    private $dataHoraInicio;
    private $dataHoraFim;
    private $observacao;

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
            agendamento (id_cliente, id_servico, valor, start, end, observacao)
        VALUES
            (:id_cliente, :id_servico, :valor, :start, :end, :observacao)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_cliente', $this->__get('idCliente'));
        $stmt->bindValue(':id_servico', $this->__get('idServico'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->bindValue(':start', $this->__get('dataHoraInicio'));
        $stmt->bindValue(':end', $this->__get('dataHoraFim'));
        $stmt->bindValue(':observacao', $this->__get('observacao'));

        $stmt->execute();

        return $this;
    }

    public function update()
    {

        $query = "UPDATE 
        agendamento
      SET
        id_cliente = :idCliente,
        id_servico = :idServico,
        valor = :valor,
        start = :dataHoraInicio,
        end = :dataHoraFim,
        observacao = :observacao
      WHERE 
        id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':idCliente', $this->__get('idCliente'));
        $stmt->bindValue(':idServico', $this->__get('idServico'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->bindValue(':dataHoraInicio', $this->__get('dataHoraInicio'));
        $stmt->bindValue(':dataHoraFim', $this->__get('dataHoraFim'));
        $stmt->bindValue(':observacao', $this->__get('observacao'));

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM agendamento WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->id);

        $stmt->execute();
    }

    public function concluirAgendamento()
    {
        $query = "UPDATE agendamento SET status_agendamento = 3  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));

        $stmt->execute();
    }

    public function cancelarAgendamento()
    {
        $query = "UPDATE agendamento SET status_agendamento = 2  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));

        $stmt->execute();
    }

    public function existeAgendamentoServico()
    {
        $query = "SELECT 
                        COUNT(*) AS numAgendamento
                      FROM 
                        agendamento
                      WHERE id_servico = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('idServico'));

        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numAgendamento'] == 0) ? false : true;
    }

    public function existeAgendamentoCliente()
    {
        $query = "SELECT 
                    COUNT(*) AS numAgendamento
                  FROM 
                    agendamento
                  WHERE id_cliente = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('idCliente'));

        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numAgendamento'] == 0) ? false : true;
    }

    public function selectAgendamentosHoje()
    {

        $query = "SELECT 
                    a.id, 
                    a.id_cliente, 
                    cli.nome AS nomeCliente,
                    cli.telefone AS telefone,
                    cli.email AS email,
                    cli.endereco AS endereco,
                    a.id_servico, 
                    serv.descricao AS nomeServico, 
                    a.valor, 
                    start, 
                    end, 
                    observacao 
                FROM agendamento AS a
                JOIN cliente AS cli
                ON cli.id = a.id_cliente
                JOIN  servico AS serv
                ON serv.id = a.id_servico
                WHERE start >= :start 
                AND end <= :end
                AND status_agendamento = 1
                ORDER BY a.start";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':start', $this->__get('dataHoraInicio'));
        $stmt->bindValue(':end', $this->__get('dataHoraFim'));

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectAgendamentosAtrasados()
    {

        $query = "SELECT 
                    a.id, 
                    a.id_cliente, 
                    cli.nome AS nomeCliente,
                    cli.telefone AS telefone,
                    cli.email AS email,
                    cli.endereco AS endereco,
                    a.id_servico, 
                    serv.descricao AS nomeServico, 
                    a.valor, 
                    start, 
                    end, 
                    observacao 
                FROM agendamento AS a
                JOIN cliente AS cli
                ON cli.id = a.id_cliente
                JOIN  servico AS serv
                ON serv.id = a.id_servico
                WHERE start <= :start 
                AND status_agendamento = 1
                ORDER BY a.start";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':start', $this->__get('dataHoraInicio'));

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectOrderName($name, $order, $orderType, $inicio, $qnt_result_pg)
    {
        $orderBy = ($order == 1) ? "ASC" : "DESC";

        switch ($orderType) {
            case '1':
                $orderType = "ag.status_agendamento";
                break;

            case '2':
                $orderType = "cli.nome";
                break;

            case '3':
                $orderType = "serv.descricao";
                break;

            case '4':
                $orderType = "ag.valor";
                break;

            case '5':
                $orderType = "ag.start";
                break;

            default:
                $orderType = "ag.id";
                break;
        }

        $query = "SELECT
                    ag.id AS idAgendamento,
                    ag.status_agendamento AS status,
                    ag.id_cliente,
                    cli.nome AS nomeCliente,
                    cli.telefone AS telefone,
                    cli.email AS email,
                    cli.endereco AS endereco,
                    ag.id_servico,
                    serv.descricao AS nomeServico, 
                    ag.valor, 
                    start, 
                    end, 
                    observacao
                FROM agendamento AS ag
                JOIN cliente AS cli
                ON ag.id_cliente = cli.id
                JOIN servico AS serv
                ON ag.id_servico = serv.id
                WHERE cli.nome LIKE :name
                ORDER BY {$orderType} {$orderBy}
                LIMIT {$inicio}, {$qnt_result_pg}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectAll()
    {
        $query = "SELECT 
                    a.id, 
                    a.id_cliente, 
                    cli.nome, 
                    a.id_servico, 
                    serv.descricao, 
                    a.valor, 
                    start, 
                    end, 
                    observacao 
                FROM agendamento AS a
                JOIN cliente AS cli
                ON cli.id = a.id_cliente
                JOIN  servico AS serv
                ON serv.id = a.id_servico";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectAllPdf($name = "", $orderType = 1, $order = 1)
    {

        $orderBy = ($order == 1) ? "ASC" : "DESC";

        switch ($orderType) {
            case '1':
                $orderType = "ag.status_agendamento";
                break;

            case '2':
                $orderType = "cli.nome";
                break;

            case '3':
                $orderType = "serv.descricao";
                break;

            case '4':
                $orderType = "ag.valor";
                break;

            case '5':
                $orderType = "ag.start";
                break;

            default:
                $orderType = "ag.id";
                break;
        }

        $query = "SELECT
                    ag.id AS idAgendamento,
                    ag.status_agendamento AS status,
                    ag.id_cliente,
                    cli.nome AS nomeCliente,
                    cli.telefone AS telefone,
                    cli.email AS email,
                    cli.endereco AS endereco,
                    ag.id_servico,
                    serv.descricao AS nomeServico, 
                    ag.valor, 
                    start, 
                    end, 
                    observacao
                FROM agendamento AS ag
                JOIN cliente AS cli
                ON ag.id_cliente = cli.id
                JOIN servico AS serv
                ON ag.id_servico = serv.id
                WHERE cli.nome LIKE :name
                ORDER BY {$orderType} {$orderBy}";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', "%" . $name . "%");
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function countAgendamentos($nomeCliente = "")
    {

        $query = "SELECT count(*) AS total 
                    FROM agendamento AS ag
                    JOIN cliente AS cli
                    ON cli.id = ag.id_cliente
                    WHERE cli.nome LIKE :nome_cliente
                    AND status_agendamento = 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome_cliente', "%" . $nomeCliente . "%");
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function countAgendamentosTabela($nomeCliente = "")
    {

        $query = "SELECT count(*) AS total 
                    FROM agendamento AS ag
                    JOIN cliente AS cli
                    ON cli.id = ag.id_cliente
                    WHERE cli.nome LIKE :nome_cliente";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome_cliente', "%" . $nomeCliente . "%");
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function duplicacaoData()
    {
        $query = "
        SELECT CASE WHEN EXISTS(SELECT * 
                                  FROM agendamento
                                 WHERE :start < end
                                   AND :end > start)
                    THEN 'ocupado'
                    ELSE 'livre' END AS resultado";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':start', $this->__get('dataHoraInicio'));
        $stmt->bindValue(':end', $this->__get('dataHoraFim'));
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0]['resultado'];
    }

    public function duplicacaoAlterarData()
    {
        $query = "
        SELECT CASE WHEN EXISTS(SELECT * 
                                  FROM agendamento
                                 WHERE :start < end
                                   AND :end > start
                                   AND id <> :id)
                    THEN 'ocupado'
                    ELSE 'livre' END AS resultado";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':start', $this->__get('dataHoraInicio'));
        $stmt->bindValue(':end', $this->__get('dataHoraFim'));
        $stmt->bindValue(':id', $this->__get('id'));

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0]['resultado'];
        /*
        $query = "SELECT count(*) AS numAgendamento FROM agendamento WHERE start = :start AND end = :end AND id <> :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':start', $this->__get('dataHoraInicio'));
        $stmt->bindValue(':end', $this->__get('dataHoraFim'));
        $stmt->bindValue(':id', $this->__get('id'));

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return ($result[0]['numAgendamento'] == 0) ? false : true;
        */
    }
}
