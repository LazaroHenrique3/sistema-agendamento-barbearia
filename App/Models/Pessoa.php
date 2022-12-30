<?php

namespace App\Models;
use MF\Model\Model;

abstract class Pessoa extends Model {

    protected $id;
    protected $nome;
    protected $sexo;
    protected $dataNascimento;
    protected $telefone;
    protected $email;
    protected $cpf;

    public abstract function insert();
    public abstract function update();
    public abstract function delete();
    public abstract function selectAll();

}