<?php
$AdminLevel = 2;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="create_content">
    <div class="content">
        <h1>Publish Maps</h1>
        <?php
        $sql = "SELECT * FROM tb_maps WHERE rep_id='{$Admin['id']}' ORDER BY datestart DESC";
        $result = pg_query(Connection::getConn(), $sql);
        if(pg_num_rows($result) > 0){
            foreach (pg_fetch_all($result) as $MAP):
            extract($MAP);
            if($status == 0){
                $status = 'NÃO PUBLICADO';
                $statusStyle = 'color: red;';
            }else{
                $status = 'PUBLICADO';
                $statusStyle = 'color: green;';
            }
            ?>
                <article class='create_maps box box4' id='<?= $id ?>'>
                    <h1><?= $title ?><span style='font-size:0.7em; <?= $statusStyle ?>'> ( <?=$status ?> )</span></h1>
                    <p><?= $description ?></p>
                    <center>
                    <?php
                    if($status=='PUBLICADO'){ ?>
                        <span rel='create_maps' class='btn btn_del j_delete_action icon-cancel-circle' id='<?= $id ?>'>DESPUBLICAR MAPA</span>
                        <span rel='create_maps' rep='<?= $Admin['id'] ?>' callback='Publish' callback_action='publish_delete' class='btn btn_del j_delete_action_confirm icon-warning' style='display:none; background: #cccc00' id='<?= $id ?>'>CONFIRMAR</span>
                    <?php }else{ ?>
                        <span rel='create_maps' class='btn btn_edit j_delete_action icon-folder-open' id='<?= $id ?>'>PUBLICAR MAPA</span>
                        <span rel='create_maps' rep='<?= $Admin['id'] ?>' callback='Publish' callback_action='publish_create' class='btn btn_edit j_delete_action_confirm icon-warning' style='display:none; background: #cccc00' id='<?= $id ?>'>CONFIRMAR</span>
                    <?php } ?>
                        </center>
                    </article>
                <?php
            endforeach;
        }else{
            echo '<br>';
            echo Erro("<span class='icon-notification'>{$Admin['name']}, there are no registered maps !</span>", E_USER_NOTICE);
        }

        ?>
        <div class="clear"></div>
    </div>
</section>