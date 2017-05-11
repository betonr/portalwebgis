<article class="draw_form editDado">
        <form action="" name="edit_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Draw">
            <input type="hidden" name="callback_action" value="draw_editar">
            <input type="hidden" name="responsavel" value="<?= $Admin['id'] ?>">
            <input type="hidden" name="map" value="<?= $name ?>">
            <input type="hidden" name="geom">
            <input type="hidden" name="camadas">
            <input type="hidden" name="id">
            <div class="callback_return" style="margin-bottom: -10px;">
                    <?php
                    if (!empty($_SESSION['trigger_login'])):
                        echo $_SESSION['trigger_login'];
                        unset($_SESSION['trigger_login']);
                    endif;
                    ?>
            </div>
            <?php
                foreach ($atributos as $columns){
                    extract($columns);
                    if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id' && $column_name != 'datemod' && $column_name != 'camadas'){
                        ?>
                        <label>
                            <span class="legend">&#10143; <?= $column_name; ?>:</span>
                            <input type="text" name="<?= $column_name; ?>" placeholder="<?= $column_name; ?>" class="inF">
                        </label>
                        <?php
                    }
                }
            ?>

            <input type="checkbox" name="1930" value="1930" style="display: inline-block; width: 25px;">1930
            <input type="checkbox" name="1920" value="1920" style="display: inline-block; width: 25px;">1920
            <input type="checkbox" name="1910" value="1910" style="display: inline-block; width: 25px;">1910
            <input type="checkbox" name="1900" value="1900" style="display: inline-block; width: 25px;">1900<br>
            <input type="checkbox" name="1890" value="1890" style="display: inline-block; width: 25px;">1890
            <input type="checkbox" name="1880" value="1880" style="display: inline-block; width: 25px;">1880
            <input type="checkbox" name="1870" value="1870" style="display: inline-block; width: 25px;">1868
            <div class="clear"></div>

            <label>
                    <span class="legend">&#10143; Autor:</span>
                    <?php
                    $sql = "SELECT id, name FROM tb_users";
                    $result = pg_query(Connection::getConn(), $sql);
                    $resAutor = pg_fetch_all($result);
                    $jsonAutor = json_encode($resAutor);
                    ?>
                    <p id="jsonAutor" style="display: none;"><?= $jsonAutor; ?></p>
                    <input type="text" name="autor" disabled>
            </label>

            <a class="closeForm icon-cancel-circle icon-notext" style="color: #333; cursor: pointer; bottom: 20px;"></a>
            <img class="form_load" style="float: right; margin-top: 25px; margin-left: 10px; position: relative; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
            <button class="btn btnblue">Update</button>
        </form>

</article>