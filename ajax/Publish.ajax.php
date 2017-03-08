<?php
session_start();
require '../config/infoBase.php';

usleep(50000);

/* DEFINE CALLBACK (PUBLISH) E RECUPERA POST
* página reponsável por receber os dados enviados pelos formulários,
* tratar os dados, executar as ações necessárias e enviar uma resposta ao usuário
*
* @author Beto Noronha
*/
$jSON = null;
$CallBack = 'Publish';
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
        /* DESPUBLICAÇÃO DE MAPAS NO GEOSERVER
        * case responsável por EXCLUIR o mapa desejado, do GEOSERVER
        * e alterar o status do mapa no BD, para 0
        */
        case 'publish_delete':
            if (in_array('', $PostData)){
                $jSON['trigger'] = AjaxErro('Complete all fields!', E_USER_NOTICE);
            }else{
                if($conn->getConn()){

                    $sql = "SELECT * FROM tb_maps WHERE id='{$PostData['del_id']}' AND rep_id='{$PostData['rep_id']}'";
                    $result = pg_query($conn->getConn(), $sql);
                    if(pg_num_rows($result) < 0){
                        $jSON['trigger'] = AjaxErro('You do not have permission!', E_USER_ERROR);
                    }else{

                        $MapSelect = pg_fetch_all($result)[0];
                        extract($MapSelect);

                        $Geoserver = new Wrapper('http://localhost:8080/geoserver/', 'admin', 'geoserver');
                        $Geoserver->deleteLayer($name, 'pauliceia', 'Postgis');

                        $sql = "UPDATE tb_maps SET status=0 WHERE id={$PostData['del_id']}";
                        $result = pg_query($conn->getConn(), $sql);
                        $jSON['redirect'] = 'dashboard.php?p=publish/home';

                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Database not conected!', E_USER_ERROR);
                }
            }
            break;

         /* PUBLIÇÃO DE MAPAS NO GEOSERVER
        * case responsável por publicar o mapa desejado no GEOSERVER
        * e alterar o status do mapa no BD, para 1
        */
        case 'publish_create':
        if (in_array('', $PostData)){
            $jSON['trigger'] = AjaxErro('Complete all fields!', E_USER_NOTICE);
        }else{
            if($conn->getConn()){

                $sql = "SELECT * FROM tb_maps WHERE id='{$PostData['del_id']}' AND rep_id='{$PostData['rep_id']}'";
                $result = pg_query($conn->getConn(), $sql);
                if(pg_num_rows($result) < 0){
                    $jSON['trigger'] = AjaxErro('You do not have permission!', E_USER_ERROR);
                }else{

                    $MapSelect = pg_fetch_all($result)[0];
                    extract($MapSelect);
                    $description = Modify::remCaracter($description);
                    if($type == 'Point'){
                        $projectSrs = 4326;
                    }else{
                        $projectSrs = 3857;
                    }

                    $Geoserver = new Wrapper('http://localhost:8080/geoserver/', 'admin', 'geoserver');
                    $Geoserver->createLayer($name, 'pauliceia', 'Postgis', $projectSrs, $description);

                    $sql = "UPDATE tb_maps SET status=1 WHERE id={$PostData['del_id']}";
                    $result = pg_query($conn->getConn(), $sql);
                    $jSON['redirect'] = 'dashboard.php?p=publish/home';
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
