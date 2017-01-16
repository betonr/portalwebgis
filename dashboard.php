<?php
ob_start();
session_start();

require 'config/infoBase.php';
$Conn = new Connection();

if (isset($_SESSION['userLogin']) && isset($_SESSION['userLogin']['level']) && $_SESSION['userLogin']['level'] >= 1):
    $sql = "SELECT * FROM tb_users WHERE id = {$_SESSION['userLogin']['id']}";
    $result = pg_query($Conn->getConn(), $sql);
    $ArrayResult = pg_fetch_all($result);
    if (!$ArrayResult || $ArrayResult[0]['level'] < 1):
        unset($_SESSION['userLogin']);
        header('Location: ./index.php');
    else:
        $Admin = $_SESSION['userLogin'];
        $DashboardLogin = true;
    endif;
else:
    unset($_SESSION['userLogin']);
    header('Location: ./index.php');
endif;

$LogOff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
if ($LogOff):
    $_SESSION['trigger_login'] = Erro("<b>LOGOFF:</b> Olá {$Admin['name']}, você desconectou com sucesso, volte logo!");
    unset($_SESSION['userLogin']);
    header('Location: ./index.php');
endif;

$getViewInput = filter_input(INPUT_GET, 'p', FILTER_DEFAULT);
$getView = ($getViewInput == 'home' ? 'home' : $getViewInput);

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Portal WEB - <?= P_NAME; ?></title>
        <meta name="description" content="<?= P_DESC; ?>"/>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="robots" content="noindex, nofollow"/>

        <link rel="icon" href="images/favicon.png" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro:300,500' rel='stylesheet' type='text/css'>
        <link rel="base" href="<?= BASE; ?>">

        <link rel="stylesheet" href="css/reset.css"/>
        <link rel="stylesheet" href="css/ol.css"/>
        <link rel="stylesheet" href="css/main.css"/>
        <link rel="stylesheet" href="css/fonticon.css"/>

        <script src="js/jquery/jquery.js"></script>
        <script src="js/jquery/cookie.js"></script>
        <script src="js/jquery/jquery.form.js"></script>
        <script src="js/jquery/maskinput.js"></script>
        <script src="js/main.js"></script>

    </head>
    <body>
        <header>
            <div class="logo">
                <a href="dashboard.php?p=home" title="pauliceia"><img src="images/logo.png" alt="[logo pauliceia]" title="logo pauliceia"/></a>
            </div>
            <div class="banner">
                <p>Welcome, <b><?= $Admin['name']; ?></b>! </p>
                <a class="icon-exit btn" title="logoff <?= P_NAME; ?>!" href="dashboard.php?p=home&logoff=true">LOGOFF!</a>
            </div>
            <div class="clear"></div>
        </header>

        <?php
        //QUERY STRING - URL DINÂMICA
        if (!empty($getView)):
             $includepatch = __DIR__ . '/sis/' . strip_tags(trim($getView) . '.php');
        else:
             $includepatch = __DIR__ . '/sis/' . 'dashboard.php';
        endif;
        if (file_exists($includepatch)):
             require_once($includepatch);
        else:
             $_SESSION['trigger_controll'] = "<b>DESCULPE:</b> O controlador <b class='fontred'>sis/{$getView}.php</b> não foi encontrado ou não existe no destino especificado!";
             header('Location: dashboard.php?p=home');
             endif;
        //FIM QUERY STRING
        ?>
        <center><a href="dashboard.php?p=home" title="home pauliceia" class="btnback icon-office">Voltar ao Menu Principal</a></center>
        <footer>
            <div class="content">
                <p>2016 - Pauliceia 2.0</p>
            </div>
        </footer>
    </body>
</html>
<?php
ob_end_flush();
