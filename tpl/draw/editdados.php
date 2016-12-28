<article class="draw_form editDado">
                <form action="" name="create_form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Draw">
                <input type="hidden" name="callback_action" value="draw_editar">
                <input type="hidden" name="responsavel" value="<?= $Admin['id'] ?>">
                <input type="hidden" name="map" value="<?= $name ?>">
                <input type="hidden" name="geom">
                <input type="text" name="id">
                <?php
                    foreach ($atributos as $columns){
                        extract($columns);
                        if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id' && $column_name != 'datemod'){
                            ?>
                            <label>
                                <span class="legend">&#10143; <?= $column_name; ?>:</span>
                                <input type="text" name="<?= $column_name; ?>" placeholder="<?= $column_name; ?>" required>
                            </label>
                            <?php
                        }
                    }
                ?>
                <img class="form_load" style="float: right; margin-top: 20px; margin-left: 10px; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
                <button class="btn btnblue">Update</button>
                </form>
                <div class="clear"></div>
        </article>