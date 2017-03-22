<?php
/*
* Arquivo responsável por: armazenar as constantes iniciais do projeto, inclur as classes em php e exibir os erros personalizadamente no portal.
*
* @author Beto Noronha
*/

/*
 * BANCO DE DADOS
 */
define('P_HOST', 'localhost'); //host
define('P_PORT', '5432'); //Port
define('P_USER', 'postgres'); //Usuário
define('P_PASS', 'root'); //Senha
define('P_DBSA', 'db_pauliceia'); //nome da Base de Dados

/*
 * INFORMAÇÕES DO PORTAL
 */
define('BASE', 'http://localhost/pauliceia/');
define('P_NAME', 'Pauliceia 2.0');
define('P_DESC', 'Portal WEBGIS com informações históricas da cidade de São Paulo');

/*
 * AUTO LOAD DE CLASSES
 * Função responsável por carregar as classes em php, da pasta config, de maneira automática.
 * Fazendo com que não seje necessário inclui-las novamente no decorrer das páginas
 */
function MyAutoLoad($Class) {
    $cDir = ['SQL', 'Help'];
    $iDir = null;

    foreach ($cDir as $dirName):
        if (!$iDir && file_exists(__DIR__ . '/' . $dirName . '/' . $Class . '.class.php') && !is_dir(__DIR__ . '/' . $dirName . '/' . $Class . '.class.php')):
            include_once (__DIR__ . '/' . $dirName . '/' . $Class . '.class.php');
            $iDir = true;
        endif;
    endforeach;
}
spl_autoload_register("MyAutoLoad");

/*
 * EXIBE MENSAGEM DE ERRO
 */
function Erro($ErrMsg, $ErrNo = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? 'trigger_info' : ($ErrNo == E_USER_WARNING ? 'trigger_alert' : ($ErrNo == E_USER_ERROR ? 'trigger_error' : 'trigger_success')));
    echo "<div class='trigger {$CssClass}'>{$ErrMsg}<span class='ajax_close'></span></div>";
}

/*
 * EXIBE ERROS LANÇADOS POR AJAX
 */
function AjaxErro($ErrMsg, $ErrNo = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? 'trigger_info' : ($ErrNo == E_USER_WARNING ? 'trigger_alert' : ($ErrNo == E_USER_ERROR ? 'trigger_error' : 'trigger_success')));
    return "<div class='trigger trigger_ajax {$CssClass}'>{$ErrMsg}<span class='ajax_close'></span></div>";
}

/*
 * PERSONALIZA OS ERROS VINDOS DO PHP
 */
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    echo "<div class='trigger trigger_error'>";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class='ajax_close'></span></div>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');