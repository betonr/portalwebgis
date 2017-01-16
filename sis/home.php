<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="home">
            <div class="content">
            <?php if($Admin['level'] == 2){ ?>
                <article class="box box3">
                    <img src="images/icons/maps.png" alt="[maps config]" title="maps config"/>
                    <center><a href="dashboard.php?p=create/create" title="criar mapas pauliceia" class="green icon-folder-open">Configurar Mapas</a></center>
                </article>
                <article class="box box3">
                    <img src="images/icons/Maps1.png" alt="[map publish]" title="map publish"/>
                    <center><a href="dashboard.php?p=publish/home" title="publicar mapas pauliceia" class="blue icon-laptop">Publicar Mapas</a></center>
                </article>

            <?php } if($Admin['level']==1 || $Admin['level']==2){ ?>
            <article class="box box3">
                <img src="images/icons/maps2.png" alt="[maps draw]" title="maps draw"/>
                <center><a href="dashboard.php?p=draw/home" title="criar mapas pauliceia" class="red icon-pencil">Desenhar Mapas</a></center>
            </article>
            <?php } ?>

            <article class="box box3">
                <?php if($Admin['level']==1 || $Admin['level']==3){ ?>
                <img src="images/icons/EditInfo.png" alt="[edit info]" title="edit info"/>
                <?php } ?>
                <center><a href="dashboard.php?p=info/home" title="editar informações pessoais" class="blue icon-user">Informações Pessoais</a></center>
            </article>

            <?php if($Admin['level']==2){ ?>
                <article class="box box3">
                    <center><a href="dashboard.php?p=info/add" title="adicionar usuários" class="red icon-wrench">Adicionar Colaboradores</a></center>
                </article>
            <?php } elseif($Admin['level']==3){ ?>
                <article class="box box3">
                    <img src="images/icons/addUser.png" alt="[adicionar user]" title="adicionar user"/>
                    <center><a href="dashboard.php?p=info/addresp" title="adicionar usuários" class="red icon-wrench">Adicionar Responsáveis</a></center>
                </article>
            <?php } ?>

            <article class="box box3">
                <?php if($Admin['level']==3){ ?>
                    <img src="images/icons/delUser.png" alt="[del user]" title="del user"/>
                    <center><a href="dashboard.php?p=info/delresp" title="excluir usuários pauliceia" class="green  icon-warning">Excluir Responsável</a></center>
                <?php }elseif($Admin['level']==2){ ?>
                    <center><a href="dashboard.php?p=info/delcolaborador" title="excluir usuários Colaborador pauliceia" class="green  icon-warning">Excluir Colaborador</a></center>
                <?php }elseif($Admin['level']==1){ ?>
                     <img src="images/icons/delUser.png" alt="[del user]" title="del user"/>
                     <center><a href="dashboard.php?p=info/delconta" title="excluir usuários pauliceia" class="green  icon-warning">Excluir Conta</a></center>
                <?php } ?>
            </article>

            </div>
</section>