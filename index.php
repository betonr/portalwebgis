<?php
ob_start();
session_start();

require 'config/infoBase.php';
$Conn = new Connection();

if(isset($_SESSION['userLogin']) && isset($_SESSION['userLogin']['level']) && $_SESSION['userLogin']['level'] >= 1){
  header('Location: dashboard.php?p=home');
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
    <meta charset="UTF-8">
        <meta name="mit" content="004671">
        <title><?= P_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="<?= P_DESC; ?>"/>
        <meta name="robots" content="noindex, nofollow"/>

        <link rel="shortcut icon" href="images/favicon.png" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro:300,500' rel='stylesheet' type='text/css'>
        <link rel="base" href="<?= BASE; ?>">

        <link rel="stylesheet" href="css/index.css"/>
        <link rel="stylesheet" href="css/reset.css"/>
    </head>
    <body>
           <div class="login">
                <img class="logo" alt="<?= P_NAME; ?>" title="<?= P_NAME; ?>" src="images/logo.png"/>
                <hr style="margin-bottom: 20px;">
                <form name="login_form" action="" method="post" enctype="multipart/form-data">
                    <div class="callback_return" style="margin-bottom: -10px;">
                        <?php
                        if (!empty($_SESSION['trigger_login'])):
                            echo $_SESSION['trigger_login'];
                            unset($_SESSION['trigger_login']);
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="callback" value="Login">
                    <input type="hidden" name="callback_action" value="login_submit">

                    <label class="label">
                        <span class="legend">Mail:</span>
                        <input type="email" class="user" name="email" placeholder="contato@pauliceia.com" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Password:</span>
                        <input type="password" class="pass" name="password" placeholder="********" required/>
                    </label>

                    <img class="form_load" style="float: right; margin-top: 20px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
                    <button class="btn">Entrar!</button>
                    <div class="clear"></div>
                </form>
            </div>

        <script src="js/jquery/jquery.js"></script>
        <script src="js/jquery/jquery.form.js"></script>
        <script src="js/jquery/html5shiv.js"></script>
        <script src="js/jquery/maskinput.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php
ob_end_flush();
