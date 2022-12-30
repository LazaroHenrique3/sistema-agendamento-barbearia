<?php

namespace App\Controllers;

use MF\Controller\Action;

//require 'D:\CursoWeb\PHP\Projeto barbearia\Barbearia do Zé\App\libs\dompdf\vendor\autoload.php';
require substr(__DIR__, 0, -12).'\libs\dompdf\vendor\autoload.php';


use Dompdf\Dompdf;

class GerarPDFController extends Action
{    
    static function gerarPdf($table, $tipoRelatorio){
        
         $data = "".date('d/m/Y');

         //Criando a instancia do DOMPDF
         $dompdf = new Dompdf();

         $dompdf->loadHtml(
             '<h1 style="text-align: center;">Relatório de '.$tipoRelatorio.'</h1>' .
                 '<h2 style="font-weight: 600; text-align: right; font-size: 20px">Data: '.$data.'</h2>'.
                 $table
         );
 
         //Renderizar o pdf
         $dompdf->render();
 
         $dompdf->stream(
             "Relatório de ".$tipoRelatorio.".pdf",
             array(
                "Attachment" => false
             )
         );
    }
}
