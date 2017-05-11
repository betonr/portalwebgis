<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<?php
if($Admin['level']>1){
    ?>
    <nav>
        <ul>
            <li><a href="dashboard.php?p=info/delcolaborador" title="excluir colaboradores">Excluir Colaborador</a></li>
            <li><a href="dashboard.php?p=info/delconta" title="excluir usuário"  style="color: #333;">Excluir Conta</a></li>
        </ul>
    </nav>
<?php } ?>
<section class="create_content">
    <div class="content">

        <h1>Excluir Sua Conta </h1>
        <form action="" name="create_form" method="post" enctype="multipart/form-data">

            <input type="hidden" name="callback" value="Users">
            <input type="hidden" name="callback_action" value="user_del">
            <input type="hidden" name="responsavel" value="<?= $Admin['id'] ?>">

            <label>
                <span class="legend">&#10143; DESEJA REALMENTE EXCULIR SUA CONTA ?</span>
                <input type="text" name="name" disabled value="<?= $Admin['name'] ?>">
            </label>

            <img class="form_load" style="float: right; margin-top: 5px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn icon-cancel-circle" style="background: #e60000;">EXCLUIR!</button>

            <div class="clear"></div><br>
            <div class="callback_return m_botton">
                <?php
                    if (!empty($_SESSION['trigger_login'])):
                        echo $_SESSION['trigger_login'];
                        unset($_SESSION['trigger_login']);
                    endif;
                ?>
            </div>
        </form>
    </div>
    <div class="clear"></div>
</section>