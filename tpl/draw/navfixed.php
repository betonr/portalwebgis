    <div class='title'>Draw Map <b>'<?= $title ?>'</b></div>
    <div class='layersFixed'>
            <ul>
                <li style="background: #999;"><input type="checkbox" name="layerbase" value="mapAtual" checked><b><?= $title ?></b></li>
                <li style="background: #AAA;"><input type="checkbox" name="layerbase" value="sara" checked>1930 BASE</li>
                <li style="background: #AAA;"><input type="checkbox" name="layerbase" value="distritos" checked>Distritos</li>
                <li style="background: #AAA;"><input type="checkbox" name="layerbase" value="municipios" checked>Municipios</li>
                <li style="background: #ccc;"><input type="radio" name="layer" value="openstreetmap" checked>OpenStreetMap</li>
                <li style="background: #ccc;"><input type="radio" name="layer" value="google">Google</li>
                <li style="background: #ccc;"><input type="radio" name="layer" value="none">Blank</li>
            </ul>
        </div>
        <div class='maislayers' style="float: right"><p class="icon-point-down"></p></div>
        <div class="clear"></div>
    </div>