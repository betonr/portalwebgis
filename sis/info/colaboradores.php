<?php
$AdminLevel = 2;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<nav>
    <ul>
        <li><a href="dashboard.php?p=info/add" title="adicionar usuários">Adicionar</a></li>
        <li><a href="dashboard.php?p=info/colaboradores" title="meus colaboradores"  style="color: #333;">Meus Colaboradores</a></li>
    </ul>
</nav>

<section class="create_content">
    <div class="content">

        <h1 style="color: #4d4dff;">Meus Colaboradores</h1>
        <form action="" name="create_form" method="post">

            <input type="hidden" name="callback" value="Users">
            <input type="hidden" name="callback_action" value="search_user">
            <input type="hidden" name="id" value="<?= $Admin['id'] ?>">

            <label style="display: inline-block; float: left; width: 80%;">
                <input type="text" name="search" placeholder="Procure por nome, e-mail ou instituição: " <?php if(isset($_GET['search']) && !empty($_GET['search'])){ echo 'value="'.$_GET['search'].'"'; } ?>>
            </label>

            <img class="form_load" style="float: right; margin-top: 20px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn" style="background: #4d4dff; display: inline-block; float:right; margin-top:10px;">Buscar</button>

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
        <div class="clear"></div>

        <div style="background: #ccc">
            <article class="box box4" style="border-right: 1px solid #999;">
                <center><b>Nome</b></center>
            </article>
            <article class="box box4" style="border-right: 1px solid #999;">
                <center><b>E-mail</b></center>
            </article>
            <article class="box box4" style="border-right: 1px solid #999;">
                <center><b>Telefone</b></center>
            </article>
            <article class="box box4">
                <center><b>Instituição</b></center>
            </article>
        </div>
        <hr>

        <?php
            if(isset($_GET['search']) && !empty($_GET['search'])){
                $sql = "SELECT * FROM tb_users WHERE rep_id LIKE '%{$Admin['id']},%' AND (name  ~*  '{$_GET['search']}' OR email ~* '{$_GET['search']}' OR institution ~* '{$_GET['search']}')";
            }else{
                $sql = "SELECT * FROM tb_users WHERE rep_id LIKE '%{$Admin['id']},%' AND id<>'{$Admin['id']}' ORDER BY datestart DESC";
            }
            $result = pg_query(Connection::getConn(), $sql);
            if(pg_num_rows($result) > 0){
                foreach (pg_fetch_all($result) as $Colaborador):
                extract($Colaborador);
                ?>
                    <article class="box box4" style="border-right: 1px solid #999;">
                        <?= $name ?>
                    </article>
                    <article class="box box4" style="border-right: 1px solid #999;">
                        <?= $email ?>
                    </article>
                    <article class="box box4" style="border-right: 1px solid #999;">
                        <?= $phone ?>
                    </article>
                    <article class="box box4">
                        <?= $institution ?>
                    </article>
                <?php
                endforeach;
            }else{
                echo '<br>';
                echo Erro("<span class='icon-notification'>{$Admin['name']}, você não possui Colaboradores !</span>", E_USER_NOTICE);
            }
            ?>
    </div>
</section>