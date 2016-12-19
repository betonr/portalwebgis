<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="create_content">
    <div class="content">
        <h1>Draw Maps</h1>

        <?php
        $sql = "SELECT * FROM tb_maps WHERE rep_id='{$Admin['id']}' ORDER BY datestart DESC";
        $result = pg_query(Connection::getConn(), $sql);
        if(pg_num_rows($result) > 0){
            foreach (pg_fetch_all($result) as $MAP):
            extract($MAP);
            if($status == 0){
                $status = 'inativo';
                $statusStyle = 'color: red;';
            }else{
                $status = 'ativo';
                $statusStyle = 'color: green;';
            }

        echo"
            <article class='create_maps box box4'>
                <img src='images/icons/map3.png' title='' alt='' />
                <h1>{$title} <span style='font-size:0.7em; {$statusStyle}'>( {$status} )</span></h1>
                <p>{$description}</p>
                <center>
                    <a href='dashboard.php?p=draw/edit&id={$id}' class='btn btn_edit'>DRAW</a>
                </center>
            </article>
         ";

            endforeach;
        }else{
            echo '<br>';
            echo Erro("<span class='icon-notification'>{$Admin['name']}, there are no registered maps !</span>", E_USER_NOTICE);
        }

        ?>
        </form>
        <div class="clear"></div>
    </div>
</section>