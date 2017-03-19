<article class="draw_form inserirDado">
                <form action="" name="create_form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Draw">
                <input type="hidden" name="callback_action" value="draw_insert">
                <input type="hidden" name="rep_id" value="<?= $Admin['id'] ?>" class="inF">
                <input type="hidden" name="map" value="<?= $name ?>" class="inF">
                <input type="hidden" name="geom" id="clearForm">
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
                        if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id'  && $column_name != 'datemod' && $column_name != 'camadas'){
                            ?>
                            <label>
                                <span class="legend">&#10143; <?= $column_name; ?>:</span>
                                <input type="text" name="<?= $column_name; ?>" id="clearForm" placeholder="<?= $column_name; ?>"  class="inF">
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

                <a class="closeForm icon-cancel-circle icon-notext" style="color: #333; cursor: pointer; bottom: 20px;"></a>
                <img class="form_load" style="float: right; margin-top: 25px; margin-left: 10px; position: relative; display: none;" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/load.gif"/>
                <button class="btn">Inserir</button>
                </form>
                <div class="clear"></div>
        </article>