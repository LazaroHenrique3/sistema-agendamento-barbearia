<?php

namespace App\Controllers\Util;

class Utilidades
{
    static function formataCPF($cpf)
    {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    static function formataTelefone($telefone)
    {
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 3, 1) . ' ' . substr($telefone, 3, 4) . '-' . substr($telefone, 7, 10);
    }

    static function formataCep($cep)
    {
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 8);
    }

    static function formataData($data)
    {
        return implode("/", array_reverse(explode("-", $data)));
    }

    static function formataDataHora($data)
    {
        $dataHora = explode("T", $data);

        $dataFormatada = implode("/", array_reverse(explode("-", $dataHora[0])));
        $dataHoraFormatada = $dataFormatada . " " . $dataHora[1];

        return $dataHoraFormatada;
    }

    static function apenasNumeros($str)
    {
        $string = $str;

        //Remove o espaço
        $string = str_replace(' ', '', $string);
        //Remove o traço
        $string = str_replace('-', '', $string);
        //Remove o ponto
        $string = str_replace('.', '', $string);
        //A abertura de parenteses
        $string = str_replace('(', '', $string);
        //O fechamento de parenteses
        $string = str_replace(')', '', $string);

        return $string;
    }

    static function formataValor($valor)
    {

        $valor = str_replace('.', ',', $valor);
        $strValor = "R$ " . $valor;

        return $strValor;
    }

    static function formataStatusAgendamento($status)
    {

        switch ($status) {
            case 1:
                return 'Agendado';
                break;

            case 2:
                return 'Cancelado';
                break;

            case 3:
                return 'Concluído';
                break;
        }
    }

    static function formataNivelAcesso($nivel)
    {

        switch ($nivel) {
            case 1:
                return '1 - Root';
                break;

            case 2:
                return '2 - Total';
                break;

            case 3:
                return '3 - Parcial';
                break;
        }
    }

    static function gerarSenha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos)
    {
        /*Essa função vai gerar uma senha aleatória para o usuário que esta sendo cadastrado, essa senha
        será envia em seu email e ele poderá fazer login com ela e registrar uma nova senha de sua escolha*/

        $senha = '';
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
        $nu = "0123456789"; // $nu contem os números
        $si = "!@#$%¨&*()_+="; // $si contem os símbolos

        if ($maiusculas) {
            // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($ma);
        }

        if ($minusculas) {
            // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($mi);
        }

        if ($numeros) {
            // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($nu);
        }

        if ($simbolos) {
            // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($si);
        }

        // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
        return substr(str_shuffle($senha), 0, $tamanho);
    }
}
