<?php
            $service = "http://localhost:8080/geoserver/"; //url do geoserver
            $request = "rest/workspaces/pauliceia/datastores/Postgis"; // Local dos workspaces
            $url = $service . $request;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //option to return string

            //AUTENTICAÇÃO
            $passwordStr = "admin:geoserver"; // replace with your username:password
            curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);

            //DELETE data
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/atom+xml"));

            //DELETE return code
            $successCode = 200;

            //tratando o resultado
            $buffer = curl_exec($ch);
            $buffer = strip_tags($buffer);
            $pos1 = strpos($buffer, '"')+1;
            $buffer = substr($buffer, $pos1);
            $pos2 = strpos($buffer, '"')+1;
            $buffer = substr($buffer, $pos2);

            $buffer = trim($buffer);
            $buffer = explode("\n", $buffer);

            curl_close($ch);
?>
<div class="layersFloat">
    <article class="box box3">
        <p>Selecione um mapa:</p>
        <select name="dbpostgis">
            <option value='none'>Nenhum</option>
                <?php
                $i = 0;
                while (isset($buffer[$i]) && !empty($buffer[$i])){
                    $buffer[$i] = str_replace(" ", "", $buffer[$i]);
                    echo "<option value='{$buffer[$i]}'>{$buffer[$i]}</option>";
                    $i++;
                }
                ?>
        </select>
        <button class="btn selectMap">Selecionar</button>
        <center><a class="btn linkstyle">&#9998; Modificar o estilo do mapa</a></center>
    </article>
    <article class="box box3">
        <li><input type="checkbox" name="layerbase" value="mapAtual" checked><b><?= $title ?> <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></b></li>
        <li><input type="checkbox" name="layerbase" value="sara" checked>1930 BASE <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="1920">1920 <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="1910">1910 <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="1900">1900 <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="1890">1890 <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="1880">1880 <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="1868">1868 <a title="mapAtual" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="distritos" checked>Distritos <a title="municipios" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="municipios" checked>Municipios <a title="distritos"class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
    </article>
    <article class="box box3">
        <li><input type="radio" name="layer" value="openstreetmap" checked>OpenStreetMap <a title="openstreetmap" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="google">Google <a title="google" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="google">Google eath <a title="google" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="esri">Esri Maps <a title="esri" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="stamen">Stamen <a title="stamen" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="none">Blank</li>
    </article>
</div>
