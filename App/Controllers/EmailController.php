<?php

namespace App\Controllers;

use MF\Controller\Action;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'C:\Users\Pichau\Desktop\Projetos\Barbearia do Zé v3\App\libs\phpmailer\vendor\autoload.php';

class EmailController extends Action
{
   static function enviarEmailSenhaTemporaria($senhaTemporaria, $emailUsuario, $nomeUsuario)
   {

      //Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
         //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
         $mail->CharSet = 'UTF-8';
         $mail->isSMTP();
         $mail->Host = 'smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Username = '6fb56a10d49961';
         $mail->Password = '8cee634fa35c65';
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $mail->Port = 2525;

         $mail->setFrom('barbeariaDoZe@gmail.com.br', 'Barbearia do Ze');
         $mail->addAddress($emailUsuario, $nomeUsuario);

         $mail->isHTML(true);
         $mail->Subject = 'Novo Cadastro';

         $mail->Body = '<body style="margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
         <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
         <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="image_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:15px;padding-top:15px;width:100%;padding-right:0px;padding-left:0px;">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3f3852; background-position: center top; background-repeat: no-repeat; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 45px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="heading_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-top:35px;text-align:center;width:100%;">
                                                                <h1 style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <canvas id="7f2d2302e28943b2bfe6f6ed6fc74abb"></canvas>
                                                                    <script> var images = [' . "'data:image/bmp;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAY2SURBVHhe1ZtbrFxTGMe31jWucb9UQhNF+iAurbim7tIEbd0jdUsjIWnog1ANdTlmrTWnDpF4QCJelB5xHFURGpEgquG0KMoDEdKiFyVVHMrnv9b65syetdeZM2O+2Xv3n/yyZ/Zae33/2Zc167YTURHtlFDl1IRUT0L6DbAW/AiGwe9gHfg0IfMCWAAuSOjx3fjoHVykZvofp6lNfgODOP6WhKrHuJPYjkhNTOjhQ/hbAbJXkNSzwY/qhC1gOegFc8FluFOmIsbJHnMOuA77FbbvY/svtvPZTc6i/vEwMABiPyRPVrKjnEVqXsRMEeAu6NuDXeUk6tsfgX8NjBSIOZ6d5STSt2ZNFIk6n53lJNJvZU0USf4nYHPWRJFUz2JnXRSpE/1f0cJ9ENQ2biJGisK2B8wN+DyZ3QqLKtNR+N9gPQJNADfVgxfOJlyUcdh+DP4AZ7NrIVHvnijUNmlrAT/ju8Ck9hWIWQw/e+GzvUB23zf4viu7FxCpG+vBaqhXEGRnfC5BQ8jMALODfbPYvYDcGU4XPhJkkWuA2JZYND0XhnwnTL8b7H+K3QuI9BdB4SnUHHRGDsPn77NpXQc9TNc/wB2QSfuA3QuI9Nag8DR/wcB53ojeFqR1E1R27tY/HJ9tNztMX8fuO5S/vcLCQ9AmqEyCmWNBFd9jhiRBJafPTKjnSMRbE6TV2Mq/QED12jXGP2AZ7oBpnJtPmpqC/Q+AjwA6K9Fj28We2Lt9vVM9lL/H8lm2sBsB+dGcWBDbJpjKuUaXHbDwz6ntw7+O7ddgO5fRjA1gBXgMJ/TChJ7chUv0sq2/0TtkGziXgFxDIxPAXtXTOUf7sv/T7vlFL47MKVyZ4RFyIzyoVFscKiN1ceCLUd9yDgHFn7NBTi1epN8OvFlWcKqA/G0bBFAzObV4RRtq+kVOFRChUdFYuK349uXU4uX/CtP+LI9wqoDI3NtYuPqEU8oj+8w3eDR3cIqAsreYYDNTSKSfb/SoLucUAZE6Nyj8Tk4pj0g9GHicwikCchMW6cLNFZxSHpG6udEjGkpiooW7o9BUaw7/22VT4106DM/jOEVIpDfWAyw6kPeWR9R7dN0fWpriIr2KCxfsZAjKD85wn0W9w3sF5efrbOHoGiNY2WT7CWTQNXcXaYD3CopMeuZ3af5TUU3kJ2jhqebPCDaDrVyATI9wSdtT2d2QH694qdGbQg+xfzznENCoPS7J1tb/lPUQ9SY5UeJXfESCuDG5ozhX/qKeI+BhlOE6s4BzCciN+MSCWMxizpW/SD+T9TOCYEVI+sug8DTbcRdM5Jz5iXoPRuw/Ay8pzBrOKSDSv2QDpDGac+Ynt8gq5mWETZyzQ7mhq2iANGs599jyk5jzccwgGAKr8f01bO0AausTm359UMxLDTTdJf4JfD8gFiDATOAjsvKttOuBnb0Za4QYJ8Xc5uKOJj8P2MKgqsT8oJ/6ihSeITsj62Zs1ZVI+yrI2wo/4di7oifCDaRGjwmQWIPoV4LZIbBIgDTmUj7Ci/QlwC6UjORtB/MdmN3Q6PJzDpG8DcCzVGPIj8/HgqQ5gfNOBm+m9jfDVq6pXmZTViZUOc3HqBwQSQ/Z6PKKyK0HiAZJMxdX5glsm80i1bCdFlR6tontFjbYBVetrDiz9cdzuCPsQslYeprP2b2ASL8cFN4JqPWrJ3HJdfkZ5qBN3wnqVS5ZQKTujwdpi20oZ96YzyWpa5EXFWC0jDZQFS5RQH4hdCRIy6BCUlcDtAFaYhqOaTYl3wrXsHsB+VWhrUxmlgXUFegoiWrslleZWMWuBUX6niBImXmIXQvKz7/VxtzKDG7/yiR2LSzS/UGwMrKM3XZBVDkjErBsXMRuuyQ77x4PXAaWs8suyq3Mci86xQwUyTDu0OPYZZflX2aKmSiS+9hdTiLzdMREUQy6TlWucj05817ETN7YFzH3Zlc5yy+j5znDQhjCc38QuylIfswwXESVB0uKu/Ixkb4KrE8Z7Bab0Vucw1FLJvcWiVur040Xqmz3uK+UizMycsPW9mVo92pdJ91otOtdL/R21yXfIUWP7oeKajpOiJ1gtX2JD8EP4GdgT44d57Of7TtJq8EAfnQVzHDTX11VkvwHodxXz2J8VGAAAAAASUVORK5CYII='" . ']; function LoadImagesAndRender() { var loadedImages = 0; for (var i = 0; i < images.length; i++) { var source = images[i]; images[i] = new Image(); images[i].src = source; images[i].onload = function () { loadedImages++; if (loadedImages == images.length) { Render(); } }; } } function Render() { let a = document.getElementById(' . "'7f2d2302e28943b2bfe6f6ed6fc74abb'" . '); let b = a.getContext(' . "'2d'" . '); a.width = 64; a.height = 64; b.drawImage(images[0], 0, 0, 64, 64, 0, 0, 64, 64); } window.addEventListener(' . "'onload'" . ', LoadImagesAndRender()); </script>
                                                                </h1>
                                                                <h1
                                                                    style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <strong>Barbearia Do Zé</strong>
                                                                </h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-4" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-left:45px;padding-right:45px;padding-top:10px;">
                                                                <div style="font-family: Arial, sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
                                                                        <p
                                                                            style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Seu código de acesso é:</span>
                                                                        </p>
                                                                        <h1 style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: justify; margin-top: 0; margin-bottom: 0;">
                                                                            <strong>' . $senhaTemporaria . '</strong>
                                                                        </h1>
                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Olá, ' . $nomeUsuario . '.</span>
                                                                        </p>
                                                                        <br>
                                                                        <p
                                                                            style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">
                                                                                Por favor, va até página de login e
                                                                                insira o código acima no campo de senha
                                                                                para logar no sistema,
                                                                                em seguida vá até a aba de configurações
                                                                                do seu perfil e cadastre uma nova senha.
                                                                            </span>
                                                                        </p>

                                                                        <br>

                                                                        <div
                                                                            style="border: solid 1px #00ff88; margin-bottom: 10px;">
                                                                        </div>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#00ff88;">
                                                                                E em hipótese alguma compartilhe o código com terceiros!
                                                                            </span>
                                                                        </p>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Atenciosamente,</span>
                                                                        </p>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#00ff88;">Barbearia do Zé</span>
                                                                        </p>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-9" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:10px;">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                      
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 10px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="5" cellspacing="0"
                                                        class="divider_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                        width="100%">
                                                                        <tr>
                                                                            <td class="divider_inner"
                                                                                style="font-size: 1px; line-height: 1px; border-top: 0px solid #BBBBBB;">
                                                                                <span> </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-2" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                        <p
                                                                            style="margin: 0; mso-line-height-alt: 14.399999999999999px;">
                                                                             </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="10" cellspacing="0"
                                                        class="social_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        class="social-table" role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;"
                                                                        width="72px">
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4"
                        role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="icons_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="vertical-align: middle; color: #9d9d9d; font-family: inherit; font-size: 15px; padding-bottom: 5px; padding-top: 5px; text-align: center;">
                                                                <table cellpadding="0" cellspacing="0"
                                                                    role="presentation"
                                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                    width="100%">
                                                                    <tr>
                                                                        <td class="alignment"
                                                                            style="vertical-align: middle; text-align: center;">
                                                                            <!--[if vml]><table align="left" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
                                                                            <!--[if !vml]><!-->
                                                                            <table cellpadding="0" cellspacing="0"
                                                                                class="icons-inner" role="presentation"
                                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <!--<![endif]-->
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
      </table><!-- End -->
      </body>';

         $mail->send();

         return true;
      } catch (Exception $e) {
         echo "Erro: E-mail não enviado com sucesso. Error PHPMailer: {$mail->ErrorInfo}";
      }
   }

   static function enviarEmailSenhaRecuperar($senhaTemporaria, $emailUsuario, $nomeUsuario)
   {

      //Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
         //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
         $mail->CharSet = 'UTF-8';
         $mail->isSMTP();
         $mail->Host = 'smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Username = '6fb56a10d49961';
         $mail->Password = '8cee634fa35c65';
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $mail->Port = 2525;

         $mail->setFrom('barbeariaDoZe@gmail.com.br', 'Barbearia do Ze');
         $mail->addAddress($emailUsuario, $nomeUsuario);

         $mail->isHTML(true);
         $mail->Subject = 'Token de Recuperação';

         
         $mail->Body = '<body style="margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
         <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
         <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="image_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:15px;padding-top:15px;width:100%;padding-right:0px;padding-left:0px;">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3f3852; background-position: center top; background-repeat: no-repeat; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 45px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="heading_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-top:35px;text-align:center;width:100%;">
                                                                <h1 style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <canvas id="7f2d2302e28943b2bfe6f6ed6fc74abb"></canvas>
                                                                    <script> var images = [' . "'data:image/bmp;base64,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'" . ']; function LoadImagesAndRender() { var loadedImages = 0; for (var i = 0; i < images.length; i++) { var source = images[i]; images[i] = new Image(); images[i].src = source; images[i].onload = function () { loadedImages++; if (loadedImages == images.length) { Render(); } }; } } function Render() { let a = document.getElementById(' . "'7f2d2302e28943b2bfe6f6ed6fc74abb'" . '); let b = a.getContext(' . "'2d'" . '); a.width = 64; a.height = 64; b.drawImage(images[0], 0, 0, 64, 64, 0, 0, 64, 64); } window.addEventListener(' . "'onload'" . ', LoadImagesAndRender()); </script>
                                                                </h1>
                                                                <h1
                                                                    style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <strong>Barbearia Do Zé</strong>
                                                                </h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-4" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-left:45px;padding-right:45px;padding-top:10px;">
                                                                <div style="font-family: Arial, sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
                                                                        <p
                                                                            style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Seu código de acesso é:</span>
                                                                        </p>
                                                                        <h1 style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: justify; margin-top: 0; margin-bottom: 0;">
                                                                            <strong>' . $senhaTemporaria . '</strong>
                                                                        </h1>
                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Olá, ' . $nomeUsuario . '.</span>
                                                                        </p>
                                                                        <br>
                                                                        <p
                                                                            style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">
                                                                              Um pedido de recuperação de senha foi solicitado para esse usuário, caso não tenha sido você quem o
                                                                              realizou pedimos que apenas ignore este email. Caso contrário va até a página de login e insira o código
                                                                              acima no campo de senha para logar no sistema e em seguida vá até a aba de configurações do seu perfil 
                                                                              e cadastre uma nova senha (Este código é válido por 1 Hora).
                                                                            </span>
                                                                        </p>

                                                                        <br>

                                                                        <div
                                                                            style="border: solid 1px #00ff88; margin-bottom: 10px;">
                                                                        </div>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#00ff88;">
                                                                                E em hipótese alguma compartilhe o código com terceiros!
                                                                            </span>
                                                                        </p>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Atenciosamente,</span>
                                                                        </p>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#00ff88;">Barbearia do Zé</span>
                                                                        </p>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-9" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:10px;">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                      
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 10px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="5" cellspacing="0"
                                                        class="divider_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                        width="100%">
                                                                        <tr>
                                                                            <td class="divider_inner"
                                                                                style="font-size: 1px; line-height: 1px; border-top: 0px solid #BBBBBB;">
                                                                                <span> </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-2" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                        <p
                                                                            style="margin: 0; mso-line-height-alt: 14.399999999999999px;">
                                                                             </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="10" cellspacing="0"
                                                        class="social_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        class="social-table" role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;"
                                                                        width="72px">
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4"
                        role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="icons_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="vertical-align: middle; color: #9d9d9d; font-family: inherit; font-size: 15px; padding-bottom: 5px; padding-top: 5px; text-align: center;">
                                                                <table cellpadding="0" cellspacing="0"
                                                                    role="presentation"
                                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                    width="100%">
                                                                    <tr>
                                                                        <td class="alignment"
                                                                            style="vertical-align: middle; text-align: center;">
                                                                            <!--[if vml]><table align="left" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
                                                                            <!--[if !vml]><!-->
                                                                            <table cellpadding="0" cellspacing="0"
                                                                                class="icons-inner" role="presentation"
                                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <!--<![endif]-->
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
      </table><!-- End -->
      </body>';

         $mail->send();

         return true;
      } catch (Exception $e) {
         echo "Erro: E-mail não enviado com sucesso. Error PHPMailer: {$mail->ErrorInfo}";
      }
   }

   static function enviarAvisoAgendamentoUsuario($dataHoraInicio, $dataHoraFim, $valorAgendamento, $servicoAgendamento, $emailUsuario, $nomeUsuario)
   {

      //Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
         //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
         $mail->CharSet = 'UTF-8';
         $mail->isSMTP();
         $mail->Host = 'smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Username = '6fb56a10d49961';
         $mail->Password = '8cee634fa35c65';
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $mail->Port = 2525;

         $mail->setFrom('barbeariaDoZe@gmail.com.br', 'Barbearia do Ze');
         $mail->addAddress($emailUsuario, $nomeUsuario);

         $mail->isHTML(true);
         $mail->Subject = 'Novo Agendamento';

         $mail->Body = '<body style="margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
         <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
         <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="image_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:15px;padding-top:15px;width:100%;padding-right:0px;padding-left:0px;">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3f3852; background-position: center top; background-repeat: no-repeat; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 45px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="heading_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-top:35px;text-align:center;width:100%;">
                                                                <h1 style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <canvas id="7f2d2302e28943b2bfe6f6ed6fc74abb"></canvas>
                                                                    <script> var images = [' . "'data:image/bmp;base64,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'" . ']; function LoadImagesAndRender() { var loadedImages = 0; for (var i = 0; i < images.length; i++) { var source = images[i]; images[i] = new Image(); images[i].src = source; images[i].onload = function () { loadedImages++; if (loadedImages == images.length) { Render(); } }; } } function Render() { let a = document.getElementById(' . "'7f2d2302e28943b2bfe6f6ed6fc74abb'" . '); let b = a.getContext(' . "'2d'" . '); a.width = 64; a.height = 64; b.drawImage(images[0], 0, 0, 64, 64, 0, 0, 64, 64); } window.addEventListener(' . "'onload'" . ', LoadImagesAndRender()); </script>
                                                                </h1>
                                                                <h1
                                                                    style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <strong>Barbearia Do Zé</strong>
                                                                </h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-4" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-left:45px;padding-right:45px;padding-top:10px;">
                                                                <div style="font-family: Arial, sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Olá, ' . $nomeUsuario . ' seu agendamento foi marcado para:</span>
                                                                        </p>

                                                                        <div style="font-weight: 500; font-size: 20px; color: #00ff88; padding-bottom: 15px;">
                                                                           <p><span style="font-weight: 600;">Data/Hora Início: </span> ' . $dataHoraInicio . '</p>
                                                                           <p><span style="font-weight: 600;">Previsão Término: </span> ' . $dataHoraFim . '</p>
                                                                           <p><span style="font-weight: 600;">Serviço: </span> ' . $servicoAgendamento . '</p>
                                                                           <p><span style="font-weight: 600;">Valor (R$): </span> ' . $valorAgendamento . '</p>
                                                                        </div>

                                                                        <div
                                                                            style="border: solid 1px #00ff88; margin-bottom: 10px;">
                                                                        </div>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Atenciosamente,</span>
                                                                        </p>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#00ff88;">Barbearia do Zé</span>
                                                                        </p>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-9" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:10px;">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                      
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 10px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="5" cellspacing="0"
                                                        class="divider_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                        width="100%">
                                                                        <tr>
                                                                            <td class="divider_inner"
                                                                                style="font-size: 1px; line-height: 1px; border-top: 0px solid #BBBBBB;">
                                                                                <span> </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-2" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                        <p
                                                                            style="margin: 0; mso-line-height-alt: 14.399999999999999px;">
                                                                             </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="10" cellspacing="0"
                                                        class="social_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        class="social-table" role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;"
                                                                        width="72px">
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4"
                        role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="icons_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="vertical-align: middle; color: #9d9d9d; font-family: inherit; font-size: 15px; padding-bottom: 5px; padding-top: 5px; text-align: center;">
                                                                <table cellpadding="0" cellspacing="0"
                                                                    role="presentation"
                                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                    width="100%">
                                                                    <tr>
                                                                        <td class="alignment"
                                                                            style="vertical-align: middle; text-align: center;">
                                                                            <!--[if vml]><table align="left" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
                                                                            <!--[if !vml]><!-->
                                                                            <table cellpadding="0" cellspacing="0"
                                                                                class="icons-inner" role="presentation"
                                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <!--<![endif]-->
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
      </table><!-- End -->
      </body>';

         $mail->send();

         return true;
      } catch (Exception $e) {
         echo "Erro: E-mail não enviado com sucesso. Error PHPMailer: {$mail->ErrorInfo}";
      }
   }

   static function enviarAvisoAlteracaoAgendamentoUsuario($dataHoraInicio, $dataHoraFim, $valorAgendamento, $servicoAgendamento, $emailUsuario, $nomeUsuario)
   {

      //Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
         //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
         $mail->CharSet = 'UTF-8';
         $mail->isSMTP();
         $mail->Host = 'smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Username = '6fb56a10d49961';
         $mail->Password = '8cee634fa35c65';
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $mail->Port = 2525;

         $mail->setFrom('barbeariaDoZe@gmail.com.br', 'Barbearia do Ze');
         $mail->addAddress($emailUsuario, $nomeUsuario);

         $mail->isHTML(true);
         $mail->Subject = 'Alteração no Agendamento';

         $mail->Body = '<body style="margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
         <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
         <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="image_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:15px;padding-top:15px;width:100%;padding-right:0px;padding-left:0px;">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #3f3852; background-position: center top; background-repeat: no-repeat; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 45px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="heading_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-top:35px;text-align:center;width:100%;">
                                                                <h1 style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <canvas id="7f2d2302e28943b2bfe6f6ed6fc74abb"></canvas>
                                                                    <script> var images = [' . "'data:image/bmp;base64,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'" . ']; function LoadImagesAndRender() { var loadedImages = 0; for (var i = 0; i < images.length; i++) { var source = images[i]; images[i] = new Image(); images[i].src = source; images[i].onload = function () { loadedImages++; if (loadedImages == images.length) { Render(); } }; } } function Render() { let a = document.getElementById(' . "'7f2d2302e28943b2bfe6f6ed6fc74abb'" . '); let b = a.getContext(' . "'2d'" . '); a.width = 64; a.height = 64; b.drawImage(images[0], 0, 0, 64, 64, 0, 0, 64, 64); } window.addEventListener(' . "'onload'" . ', LoadImagesAndRender()); </script>
                                                                </h1>
                                                                <h1
                                                                    style="margin: 0; color: #00ff88; direction: ltr; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; font-size: 28px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;">
                                                                    <strong>Barbearia Do Zé</strong>
                                                                </h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-4" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-left:45px;padding-right:45px;padding-top:10px;">
                                                                <div style="font-family: Arial, sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; font-family:' . "'Trebuchet MS'" . ',' . "'Lucida Sans Unicode'" . ',' . "'Lucida Grande'" . ',' . "'Lucida Sans'" . ', Arial, sans-serif; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Olá, ' . $nomeUsuario . ' seu agendamento foi alterado para:</span>
                                                                        </p>

                                                                        <div style="font-weight: 500; font-size: 20px; color: #00ff88; padding-bottom: 15px;">
                                                                           <p><span style="font-weight: 600;">Data/Hora Início: </span> ' . $dataHoraInicio . '</p>
                                                                           <p><span style="font-weight: 600;">Previsão Término: </span> ' . $dataHoraFim . '</p>
                                                                           <p><span style="font-weight: 600;">Serviço: </span> ' . $servicoAgendamento . '</p>
                                                                           <p><span style="font-weight: 600;">Valor (R$): </span> ' . $valorAgendamento . '</p>
                                                                        </div>

                                                                        <div
                                                                            style="border: solid 1px #00ff88; margin-bottom: 10px;">
                                                                        </div>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#ffffff;">Atenciosamente,</span>
                                                                        </p>

                                                                        <p style="margin: 0; text-align: justify; mso-line-height-alt: 27px;">
                                                                            <span style="font-size:20px;color:#00ff88;">Barbearia do Zé</span>
                                                                        </p>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-9" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:20px;padding-left:10px;padding-right:10px;padding-top:10px;">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                      
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3"
                        role="presentation"
                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f3e6f8;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 10px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="5" cellspacing="0"
                                                        class="divider_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                        width="100%">
                                                                        <tr>
                                                                            <td class="divider_inner"
                                                                                style="font-size: 1px; line-height: 1px; border-top: 0px solid #BBBBBB;">
                                                                                <span> </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="text_block block-2" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div style="font-family: sans-serif">
                                                                    <div class=""
                                                                        style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #8412c0; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                        <p
                                                                            style="margin: 0; mso-line-height-alt: 14.399999999999999px;">
                                                                             </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="10" cellspacing="0"
                                                        class="social_block block-3" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                                        class="social-table" role="presentation"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;"
                                                                        width="72px">
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4"
                        role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                                        class="row-content stack" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px;"
                                        width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        class="icons_block block-1" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                        width="100%">
                                                        <tr>
                                                            <td class="pad"
                                                                style="vertical-align: middle; color: #9d9d9d; font-family: inherit; font-size: 15px; padding-bottom: 5px; padding-top: 5px; text-align: center;">
                                                                <table cellpadding="0" cellspacing="0"
                                                                    role="presentation"
                                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;"
                                                                    width="100%">
                                                                    <tr>
                                                                        <td class="alignment"
                                                                            style="vertical-align: middle; text-align: center;">
                                                                            <!--[if vml]><table align="left" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
                                                                            <!--[if !vml]><!-->
                                                                            <table cellpadding="0" cellspacing="0"
                                                                                class="icons-inner" role="presentation"
                                                                                style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <!--<![endif]-->
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
      </table><!-- End -->
      </body>';

         $mail->send();

         return true;
      } catch (Exception $e) {
         echo "Erro: E-mail não enviado com sucesso. Error PHPMailer: {$mail->ErrorInfo}";
      }
   }
}
