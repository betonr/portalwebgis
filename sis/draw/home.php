<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="draw_content">
    <div class="content">
        <h1>Draw Maps</h1>

        <?php
        $rep = str_replace(" ", "", $Admin['rep_id']);
        $rep = explode(",", $rep);
        $i=0;
        while(isset($rep[$i]) && !empty($rep[$i])){
            $repAtual = $rep[$i];
            $sql = "SELECT * FROM tb_maps WHERE rep_id='{$Admin['id']}' OR rep_id='{$repAtual}' ORDER BY datestart DESC";
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

                echo "
                    <article class='draw_maps'><center>
                        <div class='view'><p>{$title} <span style='font-size:0.7em; {$statusStyle}'>( {$status} )</span></p></div>
                        <div class='view t50'><p>{$description}</p></div>
                        <div class='view'><p><center><a href='dashboard.php?p=draw/edit&id={$id}'>&#10000; DRAW</a></center></p></div>
                    </center></article>
                 ";

                endforeach;
                $i=100000000;
                $erro=false;
            }else{
                $i=$i+1;
                $erro=true;
            }
        }
        if((isset($erro) && $erro==true) || (strlen($Admin['rep_id']) == 0)){
            echo '<br>';
            echo Erro("<span class='icon-notification'>{$Admin['name']}, there are no registered maps !</span>", E_USER_NOTICE);
        }
        ?>
        </form>
        <div class="clear"></div>
    </div>
</section>