<?php
session_start();
require '../config/infoBase.php';

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Create';
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
        case 'create_submit':
            if (in_array('', $PostData)){
                $jSON['trigger'] = AjaxErro('Complete all fields!', E_USER_NOTICE);
            }else{
                if($conn->getConn()){

                    //$PostData['name'] = Modify::Name($PostData['name']);
                    $sql = "SELECT * FROM tb_maps WHERE name='{$PostData['name']}'";
                    $result = pg_query($conn->getConn(), $sql);
                    if(pg_num_rows($result) > 0){
                        $jSON['trigger'] = AjaxErro('This name already exists!', E_USER_ERROR);
                    }else{

                        $date = date("Y/m/d");
                        $PostData['atribs'] = str_replace(" ", "", $PostData['atribs']);
                        $sql = "INSERT INTO tb_maps (title, name, description, type, atribs, rep_id, datestart, status) VALUES ('{$PostData['title']}', '{$PostData['name']}', '{$PostData['description']}', '{$PostData['type']}', '{$PostData['atribs']}', '{$PostData['responsavel']}', '{$date}', 0)";
                        $result = pg_query($conn->getConn(), $sql);

                        $sql = "CREATE TABLE public.{$PostData['name']} (
                            id serial NOT NULL UNIQUE PRIMARY KEY,
                            geom geometry({$PostData['type']},4326)
                        )";
                        $result = pg_query($conn->getConn(), $sql);

                        $atribs = str_replace(" ", "", $PostData['atribs']);
                        $atribs = explode(",", $atribs);
                        for($i=0; $i<(count($atribs)); $i++){
                            //$atributo = Modify::Name($atribs[$i]);
                            $atributo = $atribs[$i];
                            $sql = "ALTER TABLE public.{$PostData['name']} ADD {$atributo} character varying NOT NULL";
                            $result = pg_query($conn->getConn(), $sql);
                        }

                        $jSON['trigger'] = AjaxErro('Map created successfully!');
                        $jSON['redirect'] = 'dashboard.php?p=create/edit';
                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Database not conected!', E_USER_ERROR);
                }
            }
            break;

        case 'edit_submit':
            if(isset($PostData['addAtribs']) && (!empty($PostData['addAtribs']))){
                $addAtribs = $PostData['addAtribs'];
            }
            if(isset($PostData['delAtribs']) && (!empty($PostData['delAtribs']))){
                $delAtribs = $PostData['delAtribs'];
            }
            unset($PostData['addAtribs'], $PostData['delAtribs']);

            if (in_array('', $PostData)){
                $jSON['trigger'] = AjaxErro('Complete all fields!', E_USER_NOTICE);
            }else{
                if($conn->getConn()){

                    //$PostData['name'] = Modify::Name($PostData['name']);
                    $sql = "SELECT * FROM tb_maps WHERE (name='{$PostData['name']}' AND id<>{$PostData['id']}) OR (name='{$PostData['name']}' AND rep_id<>'{$PostData['responsavel']}')";
                    $result = pg_query($conn->getConn(), $sql);
                    if(pg_num_rows($result) > 0){
                        $jSON['trigger'] = AjaxErro('This name already exists or you do not have permission!', E_USER_ERROR);
                    }else{

                        if($PostData['status'] == 0){
                            $sql = "SELECT name FROM tb_maps WHERE id={$PostData['id']}";
                            $result = pg_query($conn->getConn(), $sql);
                            if(pg_num_rows($result) > 0){
                                $nameMap = pg_fetch_all($result)[0];
                                extract($nameMap);
                                if($name != $PostData['name']){
                                    $sql = "ALTER TABLE {$name} RENAME TO {$PostData['name']}";
                                    $result = pg_query($conn->getConn(), $sql);
                                }
                            }
                            $sql = "UPDATE tb_maps SET title='{$PostData['title']}', name='{$PostData['name']}', description='{$PostData['description']}' WHERE id={$PostData['id']}";
                            $result = pg_query($conn->getConn(), $sql);
                        }else{
                            $sql = "UPDATE tb_maps SET title='{$PostData['title']}', description='{$PostData['description']}' WHERE id={$PostData['id']}";
                            $result = pg_query($conn->getConn(), $sql);
                        }


                        if(isset($addAtribs) && $PostData['status'] == 0){
                            $sql = "SELECT atribs FROM tb_maps WHERE id={$PostData['id']}";
                            $result = pg_query($conn->getConn(), $sql);
                            $resMap = pg_fetch_all($result)[0];
                            extract($resMap);
                            if((substr($atribs, -1)) != ','){
                                $atribs = $atribs.',';
                            }

                            $newAtribs = str_replace(" ", "", $addAtribs);
                            $newAtribs = explode(",", $newAtribs);
                            for($i=0; $i<(count($newAtribs)); $i++){
                                //$atributo = Modify::Name($atribs[$i]);
                                $atributo = $newAtribs[$i];
                                if($atributo != 'geom' && $atributo != 'id'){
                                    $verifyAtribs = strpos($atribs, $atributo.',');
                                    if($verifyAtribs === false){
                                        $sql = "ALTER TABLE public.{$PostData['name']} ADD {$atributo} character varying";
                                        $result = pg_query($conn->getConn(), $sql);
                                        $atribs = $atribs.' '.$atributo.',';
                                    }
                                }
                            }

                            $sql = "UPDATE tb_maps SET atribs='{$atribs}' WHERE id={$PostData['id']}";
                            $result = pg_query($conn->getConn(), $sql);
                        }

                        if(isset($delAtribs) && $PostData['status'] == 0){
                            $sql = "SELECT atribs FROM tb_maps WHERE id={$PostData['id']}";
                            $result = pg_query($conn->getConn(), $sql);
                            $resMap = pg_fetch_all($result)[0];
                            extract($resMap);
                            if((substr($atribs, -1)) != ','){
                                $atribs = $atribs.',';
                            }

                            $newAtribs = str_replace(" ", "", $delAtribs);
                            $newAtribs = explode(",", $newAtribs);
                            for($i=0; $i<(count($newAtribs)); $i++){
                                //$atributo = Modify::Name($atribs[$i]);
                                $atributo = $newAtribs[$i];
                                if($atributo != 'geom' && $atributo != 'id'){
                                    $verifyAtribs = strpos($atribs, $atributo.',');
                                    if($verifyAtribs !== false){
                                        $sql = "ALTER TABLE public.{$PostData['name']} DROP COLUMN {$atributo}";
                                        $result = pg_query($conn->getConn(), $sql);
                                        $atribs = str_replace($atributo.",", "", $atribs);
                                    }
                                }
                            }

                            $sql = "UPDATE tb_maps SET atribs='{$atribs}' WHERE id={$PostData['id']}";
                            $result = pg_query($conn->getConn(), $sql);
                        }

                        $jSON['trigger'] = AjaxErro('Map updated successfully!');
                        $jSON['redirect'] = 'dashboard.php?p=create/edit';
                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Database not conected!', E_USER_ERROR);
                }
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
