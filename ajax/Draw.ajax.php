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
                    $PostData['geom'] = str_replace(" LINESTRING", "", $PostData['geom']);
                    $PostData['geom'] = str_replace(",LINESTRING", ",", $PostData['geom']);
                    $PostData['geom'] = str_replace(" POLYGON", "", $PostData['geom']);
                    $PostData['geom'] = str_replace(",POLYGON", ",", $PostData['geom']);
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
                        $jSON['trigger'] = AjaxErro('Erro: confira seus dados, obs: não é permitido aspas', E_USER_ERROR);
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