<?php
$AdminLevel = 2;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel || !isset($_GET['id'])):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<nav>
    <ul>
        <li><a href="dashboard.php?p=create/create" title="create map in Pauliceia">Create</a></li>
        <li><a href="dashboard.php?p=create/edit" title="edit map in Pauliceia"  style="color: #333;">Edit</a></li>
    </ul>
</nav>

<section class="create_content">
    <div class="content">
    <?php
        $sql = "SELECT * FROM tb_maps WHERE rep_id='{$Admin['id']}' AND id='{$_GET['id']}'";
        $result = pg_query($Conn->getConn(), $sql);
        if(pg_num_rows($result) > 0){
            $MAP = pg_fetch_all($result)[0];
            extract($MAP);
            $adminName = strtolower('_'.$Admin['name']);
            $adminName = str_replace(" ", "", $adminName);
            $name = str_replace($adminName, "", $name);
            ?>
        <h1>Edit Map '<b><?= $title;?></b>'</h1>
        <form action="" name="create_form" method="post" enctype="multipart/form-data">

            <input type="hidden" name="callback" value="Create">
            <input type="hidden" name="callback_action" value="edit_submit">
            <input type="hidden" name="responsavel" value="<?= $Admin['id'] ?>">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="status" value="<?= $status ?>">
            <input type="hidden" name="autor" value="<?= $Admin['name'] ?>">

            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Title:</span>
                    <input type="text" name="title" value="<?= $title ?>" required>
                </label>
                <label>
                <span class="legend">&#10143; Name:</span>
                    <?php if($status == 0){?>
                        <input type="text" name="name" value="<?= $name ?>" required>
                    <?php }else{?>
                        <input type="text" disabled value="<?= $name ?>" required>
                        <input type="hidden" name="name" value="<?= $name ?>">
                    <?php }?>
                </label>
            </div>
            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Description ( <i>Max: 255caractere</i> ):</span>
                    <input type="text" name="description" value="<?= $description ?>" required>
                </label>
                <label>
                    <span class="legend" required>&#10143; Type:</span>
                    <?php
                    if($type == 'MultiLineString'){
                        $type = 'Lines';
                    }elseif($type == 'MultiPolygon'){
                        $type = 'Polygons';
                    }
                    ?>
                    <input type="text" disabled value="<?= $type ?>">
                </label>
            </div>

            <label>
                <span class="legend">&#10143; Attributes:</span>
                <input type="text" disabled value="<?= $atribs ?>" required>
            </label>

            <label>
                <span class="legend">&#10143; <span style="color: blue">Add</span> Attributes ( <i>Separate with commas</i> ):</span>
                <?php if($status == 0){?>
                <input type="text" name="addAtribs" placeholder="Attributes1, Attributes2 ...">
                <?php }else{?>
                 <input type="text" disabled placeholder="Attributes1, Attributes2 ...">
                <?php }?>
            </label>

            <label>
                <span class="legend">&#10143; <span style="color: red">Delete</span> Attributes ( <i>Separate with commas</i> ):</span>
                <?php if($status == 0){?>
                <input type="text" name="delAtribs" placeholder="Attributes1, Attributes2 ...">
                <?php }else{?>
                <input type="text" disabled placeholder="Attributes1, Attributes2 ...">
                <?php }?>
            </label>

            <img class="form_load" style="float: right; margin-top: 5px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn">Update Map</button>

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
             echo Erro("<span class='icon-notification'>Map not found or you are not allowed to modify it!</span>", E_USER_NOTICE);
        }
    ?>
        <div class="clear"></div>
    </div>
</section>