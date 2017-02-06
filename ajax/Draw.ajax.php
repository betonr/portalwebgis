<?php
session_start();
require '../config/infoBase.php';

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Draw';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] = $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    $conn = new Connection();

    //ELIMINA CÓDIGOS
    $PostData = array_map('strip_tags', $PostData);

    //SELECIONA AÇÃO
    switch ($Case):
        //LOGIN
        case 'draw_insert':
            if (in_array('', $PostData)){
                $jSON['trigger'] = AjaxErro('Complete all fields!', E_USER_NOTICE);
            }else{
                if($conn->getConn()){

                    $date = date("Y/m/d");
                    $PostData['camadas']='';
                    for($j=1;$j<8;$j++){
                        if(isset($PostData[$j]) && !empty($PostData[$j])){
                            $PostData['camadas'] .= $PostData[$j].', ';
                        }
                    }
                    $sqlkeys = "INSERT INTO {$PostData['map']} (geom, rep_id, datemod, camadas";
                    $sqlvalues = " VALUES (st_GeomFromText('{$PostData['geom']}', 4326), {$PostData['responsavel']}, '{$date}', '{$PostData['camadas']}'";

                    $mapname = $PostData['map'];
                    $sqlcolumn = "SELECT column_name FROM information_schema.columns WHERE table_name ='{$mapname}'";
                    $result = pg_query($conn->getConn(), $sqlcolumn);
                    if(pg_num_rows($result) > 0){
                        $atributos = pg_fetch_all($result);
                        foreach ($atributos as $columns){
                            extract($columns);
                            if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id' && $column_name != 'datemod' && $column_name != 'camadas'){
                                $sqlkeys .= ",{$column_name}";
                                $atributo = $PostData[$column_name];
                                $sqlvalues .= ", '{$atributo}'";
                            }
                        }
                    }
                    $sqlkeys .= ")";
                    $sqlvalues .= ")";
                    $sql = $sqlkeys.$sqlvalues;
                    $result = pg_query($conn->getConn(), $sql);
                    if($result){
                        $jSON['trigger'] = AjaxErro('Data inserted successfully');
                        $jSON['clearInput'] = true;
                    }else{
                        $jSON['trigger'] = AjaxErro('Error: verify your data, obs: do not use quotation marks', E_USER_ERROR);
                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Database not conected!', E_USER_ERROR);
                }
            }
            break;

            case 'draw_delete':
                $sql = "DELETE FROM {$PostData['tb_name']} WHERE id={$PostData['del_id']}";
                $result = pg_query($conn->getConn(), $sql);
                if(pg_affected_rows($result) <= 0){
                    $jSON['erro'] = true;
                }
            break;

            case 'draw_editar':
                if($conn->getConn()){

                    $date = date("Y/m/d");
                    $mapname = $PostData['map'];
                    $camadasSelect='';
                    for($z=1;$z<8;$z++){
                        if(isset($PostData[$z])){
                            $camadasSelect .= $z.', ';
                        }
                    }
                    $sql = "UPDATE {$mapname} SET datemod='{$date}', camadas='{$camadasSelect}', rep_id='{$PostData['responsavel']}'";

                    $sqlcolumn = "SELECT column_name FROM information_schema.columns WHERE table_name ='{$mapname}'";
                    $result = pg_query($conn->getConn(), $sqlcolumn);
                    if(pg_num_rows($result) > 0){
                        $atributos = pg_fetch_all($result);
                        foreach ($atributos as $columns){
                            extract($columns);
                            if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id' && $column_name != 'datemod' && $column_name != 'camadas'){
                                $sql .= ", {$column_name}=";
                                $atributo = $PostData[$column_name];
                                $sql .= "'{$atributo}'";
                            }
                        }
                    }
                    $sql .= "WHERE id={$PostData['id']}";
                    $result = pg_query($conn->getConn(), $sql);
                    if($PostData['geom'] != ''){
                        $sql = "UPDATE {$mapname} SET geom=st_GeomFromText('{$PostData['geom']}', 4326) WHERE id={$PostData['id']}";
                    }
                    $result = pg_query($conn->getConn(), $sql);
                    if($result){
                        $jSON['trigger'] = AjaxErro('Data updated successfully');
                        $jSON['none'] = true;
                    }else{
                        $jSON['trigger'] = AjaxErro('Error: verify your data, obs: do not use quotation marks', E_USER_ERROR);
                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Database not conected!', E_USER_ERROR);
                }
            break;

            case 'draw_duplic':
                 if($conn->getConn()){

                    $date = date("Y/m/d");
                    $mapname = $PostData['map'];
                    $camadasSelect='';
                    for($z=1;$z<8;$z++){
                        if(isset($PostData[$z])){
                            $camadasSelect .= $z.', ';
                        }
                    }
                    $sql = "SELECT id, st_astext(geom) geom FROM {$mapname} WHERE id={$PostData['id']}";
                    $result = pg_query($conn->getConn(), $sql);
                    $wktgeom = pg_fetch_all($result)[0];
                    $geom = $wktgeom['geom'];

                    $sqlkeys = "INSERT INTO {$mapname} (geom, rep_id, datemod, camadas";
                    $sqlvalues = " VALUES (st_GeomFromText('{$geom}', 4326), {$PostData['responsavel']}, '{$date}', '{$camadasSelect}'";

                    $sqlcolumn = "SELECT column_name FROM information_schema.columns WHERE table_name ='{$mapname}'";
                    $result = pg_query($conn->getConn(), $sqlcolumn);
                    if(pg_num_rows($result) > 0){
                        $atributos = pg_fetch_all($result);
                        foreach ($atributos as $columns){
                            extract($columns);
                            if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id' && $column_name != 'datemod' && $column_name != 'camadas'){
                                $sqlkeys .= ",{$column_name}";
                                $atributo = $PostData[$column_name];
                                $sqlvalues .= ", '{$atributo}'";
                            }
                        }
                    }
                    $sqlkeys .= ")";
                    $sqlvalues .= ")";
                    $sql = $sqlkeys.$sqlvalues;

                    $result = pg_query($conn->getConn(), $sql);
                    if($result){
                        $jSON['trigger'] = AjaxErro('Data updated successfully');
                        //$jSON['none'] = true;
                    }else{
                        $jSON['trigger'] = AjaxErro('Error: verifique seus dados, não é possível utilizar aspas.', E_USER_ERROR);
                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Database not conected!', E_USER_ERROR);
                }
            break;

            case 'draw_dividir':
                $date = date("Y/m/d");
                $newGeom = $PostData['lines'];
                $geomlines = explode('+',  $newGeom);
                $map_name = $PostData['tb_name'];

                $sql = "SELECT * FROM {$map_name} WHERE id={$PostData['div_id']}";
                $result = pg_query($conn->getConn(), $sql);
                $feature = pg_fetch_all($result)[0];

                $i=0;
                while($i<2){
                    $geom = $geomlines[$i];
                    $camadas = $feature['camadas'];

                    $sqlkeys = "INSERT INTO {$map_name} (geom, rep_id, datemod, camadas";
                    $sqlvalues = " VALUES (st_GeomFromText('{$geom}', 4326), {$PostData['autor']}, '{$date}', '{$camadas}'";

                    $sqlcolumn = "SELECT column_name FROM information_schema.columns WHERE table_name ='{$map_name}'";
                    $result = pg_query($conn->getConn(), $sqlcolumn);
                    if(pg_num_rows($result) > 0){
                        $atributos = pg_fetch_all($result);
                        foreach ($atributos as $columns){
                            extract($columns);
                            if($column_name != 'id' && $column_name != 'geom' && $column_name != 'rep_id' && $column_name != 'datemod' && $column_name != 'camadas'){
                                $sqlkeys .= ",{$column_name}";
                                $atributo = $feature[$column_name];
                                $sqlvalues .= ", '{$atributo}'";
                            }
                        }
                    }
                    $sqlkeys .= ")";
                    $sqlvalues .= ")";
                    $sql = $sqlkeys.$sqlvalues;

                    $result = pg_query($conn->getConn(), $sql);
                    $i++;
                }

                $sql = "DELETE FROM {$map_name} WHERE id={$PostData['div_id']}";
                $result = pg_query($conn->getConn(), $sql);

                $jSON['sucess'] = true;

            break;

    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['trigger'] = AjaxErro('<b>Desculpe.</b> Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
