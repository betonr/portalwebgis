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
            <li><a href="dashboard.php?p=info/delcolaborador" title="excluir colaboradores" style="color: #333;">Excluir Colaborador</a></li>
            <li><a href="dashboard.php?p=info/delconta" title="excluir usuário">Excluir Conta</a></li>
        </ul>
    </nav>
<?php } ?>
<section class="create_content">
    <div class="content">

        <h1>Excluir Colaboradores</h1>
        <div style="background: #ccc">
            <article class="box box3" style="border-right: 1px solid #999;">
                <center><b>Nome</b></center>
            </article>
            <article class="box box3" style="border-right: 1px solid #999;">
                <center><b>E-mail</b></center>
            </article>
            <article class="box box3">
                <center><b>Excluir</b></center>
            </article>
        </div>
        <hr>

        <?php
            $sql = "SELECT * FROM tb_users WHERE rep_id LIKE '%{$Admin['id']},%' AND id<>'{$Admin['id']}' ORDER BY datestart DESC";
            $result = pg_query(Connection::getConn(), $sql);
            if(pg_num_rows($result) > 0){
                foreach (pg_fetch_all($result) as $Colaborador):
                extract($Colaborador);
                ?>
                <div class="del_colaborador" id="<?= $id ?>">
                    <article class="box box3" style="border-right: 1px solid #999;">
                        <?= $name ?>
                    </article>
                    <article class="box box3" style="border-right: 1px solid #999;">
                        <?= $email ?>
                    </article>
                    <article class="box box3">
                        <center><span rel='del_colaborador' class='btn j_delete_action icon-cancel-circle' id='<?= $id ?>'>DELETE</span>
                        <span rel='del_colaborador' rep='<?= $Admin['id'] ?>' callback='Users' callback_action='user_del' class='btn j_delete_action_confirm icon-warning' style='display:none; background: #cccc00' id='<?= $id ?>'>CONFIRMAR</span></center>
                    </article>
                </div>
                <?php
                endforeach;
            }else{
                echo '<br>';
                echo Erro("<span class='icon-notification'>{$Admin['name']}, você não possui Colaboradores !</span>", E_USER_NOTICE);
            }
            ?>
    </div>
    <div class="clear"></div>
</section>