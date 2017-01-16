<?php
$AdminLevel = 3;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="create_content">
    <div class="content">

        <h1>Excluir Usuários Responsáveis</h1>
        <form action="" name="create_form" method="post">

            <input type="hidden" name="callback" value="Users">
            <input type="hidden" name="callback_action" value="search_user">

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
        <div style="background: #ccc">
            <article class="box box4" style="border-right: 1px solid #999;">
                <center><b>Nome</b></center>
            </article>
            <article class="box box4" style="border-right: 1px solid #999;">
                <center><b>E-mail</b></center>
            </article>
            <article class="box box4" style="border-right: 1px solid #999;">
                <center><b>Função</b></center>
            </article>
            <article class="box box4">
                <center><b>Excluir</b></center>
            </article>
        </div>
        <hr>

        <?php
            if(isset($_GET['search']) && !empty($_GET['search'])){
                $sql = "SELECT * FROM tb_users WHERE level<3 AND (name  ~*  '{$_GET['search']}' OR email ~* '{$_GET['search']}' OR institution ~* '{$_GET['search']}') ORDER BY datestart DESC";
            }else{
                $sql = "SELECT * FROM tb_users WHERE level<3 ORDER BY datestart DESC";
            }
            $result = pg_query(Connection::getConn(), $sql);
            if(pg_num_rows($result) > 0){
                foreach (pg_fetch_all($result) as $Colaborador):
                extract($Colaborador);
                ?>
                <div class="del_colaborador" id="<?= $id ?>">
                    <article class="box box4" style="border-right: 1px solid #999;">
                        <?= $name ?>
                    </article>
                    <article class="box box4" style="border-right: 1px solid #999;">
                        <?= $email ?>
                    </article>
                    <article class="box box4" style="border-right: 1px solid #999;">
                        <?php
                        if($level==1){
                            $level = 'Colaborador';
                        }elseif($level==2){
                            $level = 'Responsável';
                        }else{
                            $level = 'Indefinido';
                        }
                        echo $level;
                        ?>
                    </article>
                    <article class="box box4">
                        <center><span rel='del_colaborador' class='btn j_delete_action icon-cancel-circle' id='<?= $id ?>'>DELETE</span>
                        <span rel='del_colaborador' rep='<?= $Admin['id'] ?>' callback='Users' callback_action='user_delresp' class='btn j_delete_action_confirm icon-warning' style='display:none; background: #cccc00' id='<?= $id ?>'>CONFIRMAR</span></center>
                    </article>
                </div>
                <?php
                endforeach;
            }else{
                echo '<br>';
                echo Erro("<span class='icon-notification'>{$Admin['name']}, não possui usuários para serem excluídos !</span>", E_USER_NOTICE);
            }
            ?>
    </div>
    <div class="clear"></div>
</section>