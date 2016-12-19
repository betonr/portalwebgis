<?php
$AdminLevel = 2;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<nav>
    <ul>
        <li><a href="dashboard.php?p=create/create" title="create map in Pauliceia" style="color: #333;">Create</a></li>
        <li><a href="dashboard.php?p=create/edit" title="edit map in Pauliceia">Edit</a></li>
    </ul>
</nav>

<section class="create_content">
    <div class="content">
        <h1>Create new Map</h1>
        <form action="" name="create_form" method="post" enctype="multipart/form-data">

            <input type="hidden" name="callback" value="Create">
            <input type="hidden" name="callback_action" value="create_submit">
            <input type="hidden" name="responsavel" value="<?= $Admin['id'] ?>">

            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Title:</span>
                    <input type="text" name="title" placeholder="Mapa de Ruas" required>
                </label>
                <label>
                    <span class="legend">&#10143; Name:</span>
                    <input type="text" name="name" placeholder="map_ruas" required>
                </label>
            </div>
            <div class="box box2">
                <label>
                    <span class="legend">&#10143; Description ( <i>Max: 255caractere</i> ):</span>
                    <input type="text" name="description" placeholder="mapa referente as ruas da cidade de são paulo" required>
                </label>
                <label>
                    <span class="legend" required>&#10143; Type:</span>
                    <select name="type">
                        <option value="" disabled selected>Select Geometry</option>
                        <option value="Point">Points</option>
                        <option value="MultiLineString">Lines</option>
                        <option value="MultiPolygon">Polygons</option>
                    </select>
                </label>
            </div>

            <label>
                <span class="legend">&#10143; Attributes ( <i>Separate with commas</i> ):</span>
                <input type="text" name="atribs" placeholder="nome, ano, pontoinicial, pontofinal ..." required>
            </label>

            <img class="form_load" style="float: right; margin-top: 5px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn">Create Map</button>

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