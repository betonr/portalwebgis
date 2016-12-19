<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel || !isset($_GET['id'])):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="mapedit" id="mapafixo">
    <div class="top">
        <div class='title'>Draw Map {$name}</div>
        <div class='layersFixed'>
            <ul>
                <li style="color: red;"><input type="checkbox" name="layerbase" value="atual" checked>{$name}</li>
                <li style="color: blue;"><input type="checkbox" name="layerbase" value="sara" checked>1930 oficial</li>
                <li style="color: blue;"><input type="checkbox" name="layerbase" value="distritos" checked>Distritos</li>
                <li style="color: blue;"><input type="checkbox" name="layerbase" value="municipios" checked>Municipios</li>
                <li><input type="radio" name="layer" value="openstreetmap" checked>OpenStreetMap</li>
                <li><input type="radio" name="layer" value="google">Google</li>
                <li><input type="radio" name="layer" value="none">Blank</li>
            </ul>
        </div>
        <div class='maislayers'><p>+ MAIS LAYERS</p></div>
        <div class="clear"></div>
    </div>
    <img src="images/logo.png" title="logo pauliceia">



</section>
