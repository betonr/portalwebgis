<?php
$AdminLevel = 2;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<nav>
    <ul>
        <li><a href="dashboard.php?p=info/add" title="adicionar usuários" style="color: #333;">Adicionar</a></li>
        <li><a href="dashboard.php?p=info/colaboradores" title="meus colaboradores">Meus Colaboradores</a></li>
    </ul>
</nav>

<section class="create_content">
    <div class="content">

        <h1 style="color: #4d4dff;">Adicionar Usuários Existentes</h1>
        <form action="" name="create_form" method="post" enctype="multipart/form-data">

            <input type="hidden" name="callback" value="Users">
            <input type="hidden" name="callback_action" value="user_active">
            <input type="hidden" name="responsavel" value="<?= $Admin['id'] ?>">

            <label>
                <span class="legend">&#10143; Email:</span>
                <input type="email" name="email" placeholder="e-mail: ">
            </label>

            <img class="form_load" style="float: right; margin-top: 5px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn"  style="background: #4d4dff;">Adicionar como seu colaborador</button>

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
    </div>
    <div class="content">

        <h1>Adicionar novos Usuários</h1>
        <form action="" name="create_form" method="post" enctype="multipart/form-data">

            <input type="hidden" name="callback" value="Users">
            <input type="hidden" name="callback_action" value="user_add">
            <input type="hidden" name="responsavel" value="<?= $Admin['id'] ?>">

            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Nome:</span>
                    <input type="text" name="name" placeholder="nome: " required>
                </label>
                <label>
                    <span class="legend">&#10143; Telefone:</span>
                    <input type="text" name="phone" placeholder="Telefone: " class="formPhone" required>
                </label>
                <label>
                    <span class="legend">&#10143; Senha:</span>
                    <input type="password" name="pass" placeholder="******" required>
                </label>
            </div>
            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Email:</span>
                    <input type="email" name="email" placeholder="e-mail: " required>
                </label>
                <label>
                    <span class="legend">&#10143; Instituição:</span>
                    <input type="text" name="institution" placeholder="Instituição: " required>
                </label>
                <label>
                    <span class="legend">&#10143; Repitição da Senha:</span>
                    <input type="password" name="rePass" placeholder="******" required>
                </label>
            </div>

            <img class="form_load" style="float: right; margin-top: 5px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn">Adicionar Usuário</button>

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
    </div>
</section>