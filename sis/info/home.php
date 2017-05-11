<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="create_content">
    <div class="content">
    <?php
        $sql = "SELECT * FROM tb_users WHERE id='{$Admin['id']}'";
        $result = pg_query($Conn->getConn(), $sql);
        if(pg_num_rows($result) > 0){
            $MAP = pg_fetch_all($result)[0];
            extract($MAP);
            ?>
        <h1>Altere suas informações, <b><?= $name;?></b></h1>
        <form action="" name="create_form" method="post" enctype="multipart/form-data">

            <input type="hidden" name="callback" value="Users">
            <input type="hidden" name="callback_action" value="user_edit">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Nome:</span>
                    <input type="text" name="name" value="<?= $name ?>" required>
                </label>
                <label>
                    <span class="legend">&#10143; Telefone:</span>
                    <input type="text" name="phone" value="<?= $phone ?>" class="formPhone" required>
                </label>
            </div>
            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Email:</span>
                    <input type="email" name="email" value="<?= $email ?>" disabled required>
                </label>
                <label>
                    <span class="legend">&#10143; Instituição:</span>
                    <input type="text" name="institution" value="<?= $institution ?>" required>
                </label>
            </div>

            <?php
            if($level == 1){
                $funcao = 'Colaborador (aluno)';
            }elseif($level == 2){
                $funcao = 'Responsável (professor)';
            }else{
                $funcao = 'Administrador';
            }
            ?>
            <label>
                    <span class="legend">&#10143; Função:</span>
                    <input type="text" name="level" value="<?= $funcao ?>" disabled required>
            </label>


            <div class="newpass" style="display: none;">
                <label>
                    <span class="legend">&#10143; Senha Atual:</span>
                    <input type="password" name="passAtual" placeholder="******">
                </label>
                <div class="box box2">
                    <label>
                        <span class="legend">&#10143; Nova Senha:</span>
                        <input type="password" name="newPass" placeholder="******">
                    </label>
                </div>
                <div class="box box2">
                    <label>
                        <span class="legend">&#10143; Repita a nova Senha:</span>
                        <input type="password" name="repNewPass" placeholder="******">
                    </label>
                </div>
            </div>

            <br>
            <a class="btn actnewPass" style="background: #4d4dff; float: left;">Alterar Senha</a>
            <img class="form_load" style="float: right; margin-top: 5px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn">Alterar Info</button>

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
        <?php
        }else{
             echo '<br>';
             echo Erro("<span class='icon-notification'>Usuário não existente!</span>", E_USER_NOTICE);
        }
    ?>
        <div class="clear"></div>
    </div>
</section>