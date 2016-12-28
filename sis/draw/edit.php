<?php
$AdminLevel = 1;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel || !isset($_GET['id'])):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<script src="js/ol3/ol-debug.js" defer></script>
<script src="js/map.js" defer></script>

<script src="js/draw/points.js" defer></script>
<script src="js/draw/line.js" defer></script>
<script src="js/draw/poligons.js" defer></script>

<section class="mapedit" id="mapafixo">
<?php
    //LEITURA DAS INFORMAÇÕES BÁSICSA DO MAPA SELECIONADO
    $sql = "SELECT * FROM tb_maps WHERE (rep_id='{$Admin['id']}' OR rep_id='{$Admin['rep_id']}') AND id='{$_GET['id']}'";
    $result = pg_query($Conn->getConn(), $sql);
    if(pg_num_rows($result) > 0){
        $MAP = pg_fetch_all($result)[0];
        extract($MAP);

        //CONSTRUÇÃO DO SQL PARA LEITURA DOS DADOS GEOMETRICOS DA TABELA SELECIONADA
        $sqljson = "SELECT id";

        //LEITURA DAS COLUNAS PERTENCENTES A TABELA ATUAL
        $sql = "SELECT column_name FROM information_schema.columns WHERE table_name ='{$name}'";
        $result = pg_query($Conn->getConn(), $sql);
        if(pg_num_rows($result) > 0){
            $atributos = pg_fetch_all($result);
            foreach ($atributos as $columns){
                extract($columns);
                if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id'  && $column_name != 'datemod'){
                    //INSERÇÃO DAS COLUNAS NO SQL PARA LEITURA DOS DADOS GEOGRAFICOS
                    $sqljson .= ", {$column_name}";
                }
            }
        }

        //CONCLUSÃO DA CONSTRUÇÃO DO SQL DOS DADOS GEOGRAFICOS, COM BASE NO TIPO DE DADO A SER CADASTRADO
        if($type=='Point'){
            $sqljson .= ", st_asgeojson(st_transform(geom,3857)) AS geojson FROM {$name}";
        }elseif($type=='MultiLineString'){
            $sqljson .= ", st_asgeojson(st_transform(geom,4326)) AS geojson FROM {$name}";
        }elseif($type=='MultiPolygon'){
            $sqljson .= ", st_asgeojson(st_transform(geom,4326)) AS geojson FROM {$name}";
        }

        $result = pg_query($Conn->getConn(), $sqljson);

        //CONSTRUÇÃO DA Build GeoJSON
        $output = '';
        $rowOutput = '';
        function escapeJsonString($value) {
          $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
          $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
          $result = str_replace($escapers, $replacements, $value);
          return $result;
        }
        while ($row = pg_fetch_assoc($result)) {
            $rowOutput = (strlen($rowOutput) > 0 ? ',' : '') . '{"type": "Feature", "geometry": ' . $row['geojson'] . ', "properties": {"tabName":"'.$name.'",';
            $props = '';
            $id    = '';
            foreach ($row as $key => $val) {
                if ($key != "geojson") {
                    $props .= (strlen($props) > 0 ? ',' : '') . '"' . $key . '":"' . escapeJsonString($val) . '"';
                }
                if ($key == "id") {
                    $id .= ',"id":"' . escapeJsonString($val) . '"';
                }
            }
            $rowOutput .= $props . '}';
            $rowOutput .= $id;
            $rowOutput .= '}';
            $output .= $rowOutput;
        }
        $output = '{ "type": "FeatureCollection", "features": [ ' . $output . ' ]}';
    ?>
    <p id="jsonMap" style="display: none;"><?= $output; ?></p>
    <div class="top">

    <?php require 'tpl/draw/navfloat.php'; ?>

    <?php require 'tpl/draw/navfixed.php'; ?>

    <!--- btn AÇÕES DESENHO -->
    <div class="functionsType">
    <span style="color: #3366FF; font-size: 2em; font-weight: 600;padding-left: 4px; text-shadow: 1px 1px 1px #333;">&#9997;</span>
    <?php
        if($type=='Point'){
            echo "<a class='btn points'>&#10687;</a>";
        }elseif($type=='MultiLineString'){
            echo "<a class='btn line'>&#9761;</a>";
        }elseif($type=='MultiPolygon'){
            echo "<a class='btn poligons'>&#9640;</a>";
        }
    ?>
    </div>
    <!--- fim AÇÕES -->

    <!--- TOOBAR dos Desenhos -->
    <?php
        if($type=='Point'){ ?>
        <div id="pointsOptions" class="toobar">
            <form name="coordenadas" action="" method="post">
                <input type="text" name="latitude" placeholder="latitude"/>
                <input type="text" name="longitude" placeholder="longitude"/>
                <button name="localizar" id="localizar" class="localizar">&#10004;</button>
            </form>
            <p class="btn" id="panPoint">[ ]</p>
            <p class="btn" id="drawPoint">Desenhar</p>
            <p class="btn" id="erasePoint">Apagar</p>
        </div>
        <?php }elseif($type=='MultiLineString'){ ?>
        <div id="lineOptions" class="toobar">
            <p class="btn" id="panLine">[ ]</p>
            <p class="btn" id="drawLine">Desenhar</p>
            <p class="btn" id="editLine">Editar</p>
            <p class="btn" id="eraseLine">Apagar</p>
        </div>
        <?php }elseif($type=='MultiPolygon'){ ?>
        <div id="poligonsOptions" class="toobar">
            <p class="btn" id="panPoligons">[ ]</p>
            <p class="btn" id="drawPoligons">Desenhar</p>
            <p class="btn" id="editPoligons">Editar</p>
            <p class="btn" id="erasePoligons">Apagar</p>
        </div>
        <?php } ?>
        <!---fim toobar -->

        <!--- FORMULARIO DE ENVIO DO DADO -->
        <?php require 'tpl/draw/insertdados.php'; ?>

        <!--- FORMULARIO DE EDIÇÃO DO DADO -->
        <?php require 'tpl/draw/editdados.php'; ?>

        <img src="images/logo.png" title="logo pauliceia">
        <a href="dashboard.php?p=home" title="portal web pauliceia" class="icon-office btnbackMap">Voltar ao Menu</a>

        <!--- FORMULARIO DE EDIÇÃO DO DADO -->
        <?php require 'tpl/draw/editStyle.php'; ?>

    <?php } ?>
</section>
<div class="clear"></div>