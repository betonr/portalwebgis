<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="home">
            <div class="content">
            <?php
                if($Admin['level']>0){
            ?>
            <article class="box box3">
                <img src="images/icons/maps.png" alt="[maps create]" title="maps create"/>
                <center><a href="dashboard.php?p=create/create" title="criar mapas pauliceia" class="green icon-folder-open">Criar Mapas</a></center>
            </article>
            <article class="box box3">
                <img src="images/icons/Maps1.png" alt="[maps create]" title="maps create"/>
                <center><a href="" title="criar mapas pauliceia" class="blue icon-laptop">Publicar Mapas</a></center>
            </article>
            <article class="box box3">
                <img src="images/icons/maps2.png" alt="[maps create]" title="maps create"/>
                <center><a href="dashboard.php?p=draw/home" title="criar mapas pauliceia" class="red icon-pencil">Desenhar Mapas</a></center>
            </article>
            <?php } ?>
            <article class="box box3">
                <center><a href="" title="criar mapas pauliceia" class="blue icon-user">Informações Pessoais</a></center>
            </article>
            <article class="box box3">
                <center><a href="" title="criar mapas pauliceia" class="red icon-wrench">Adicionar Contribuintes</a></center>
            </article>
            <article class="box box3">
                <center><a href="" title="criar mapas pauliceia" class="green  icon-warning">Excluir Contribuintes</a></center>
            </article>
            </div>
</section>