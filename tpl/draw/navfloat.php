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
        <center><a class="btn linkstyle" title="postgis" id="Lbases">&#9998; Modificar Opacidade</a></center>
    </article>
    <article class="box box3">
        <li><input type="checkbox" name="layerbase" value="mapAtual" checked><b><?= $title ?> <a title="mapAtual" id="Lbases" class="linkstyle_complex icon-image" style="cursor: pointer; color: gray;"></a><a class="selectC icon-wrench" style="cursor: pointer; color: green;"></a></b></li>
        <li><input type="checkbox" name="layerbase" id='saraLayer' value="1">1930 BASE <a title="sara" id="Lsara" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" id='saraLayer' value="2">1920 <a title="1920" id="Lsara" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" id='saraLayer' value="3">1910 <a title="1910" id="Lsara" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" id='saraLayer' value="4">1900 <a title="1900" id="Lsara" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" id='saraLayer' value="5">1890 <a title="1890" id="Lsara" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" id='saraLayer' value="6">1880 <a title="1880" id="Lsara" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" id='saraLayer' value="7">1868 <a title="1868" id="Lsara" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="distritos" checked>Distritos <a title="distritos"  id="Lbases" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="checkbox" name="layerbase" value="municipios" checked>Municipios <a title="municipios"  id="Lbases" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
    </article>
    <article class="box box3">
        <li><input type="radio" name="layer" value="openstreetmap" checked>OpenStreetMap <a title="openstreetmap"  id="Lmapa" class="linkstyle icon-image" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="bingRoad">Bing Road <a title="google" class="linkstyle icon-image"  id="Lmapa" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="esri">Esri Maps <a title="esri" class="linkstyle icon-image"  id="Lmapa" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="stamen">Stamen <a title="stamen" class="linkstyle icon-image"  id="Lmapa" style="cursor: pointer; color: gray;"></a></li>
        <li><input type="radio" name="layer" value="none">Blank</li>
    </article>
</div>
