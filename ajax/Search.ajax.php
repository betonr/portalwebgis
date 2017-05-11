<?php
session_start();
require '../config/infoBase.php';

usleep(50000);

/* DEFINE CALLBACK (DRAW) E RECUPERA POST
* página reponsável por receber os dados enviados pelos formulários,
* tratar os dados, executar as ações necessárias e enviar uma resposta ao usuário
*
* @author Beto Noronha
*/
$jSON = null;
$CallBack = 'Search';
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
        /* INSERÇÃO DE CONTEÚDOS NOS MAPAS (tabelas) NO BD
        * case responsável por inserir os dados espaciais na tabela
        */
        case 'search_end':
                if($conn->getConn()){

                    $searchInput = $PostData['searchInput'];
                    $sinal=""; 
                    //verifica se algum numero foi informado
                    if(strpos($searchInput, "-") !== false){ $sinal = "-"; }
                    if(strpos($searchInput, ",") !== false){ $sinal = ","; }
                    if(strpos($searchInput, ".") !== false){ $sinal = "."; }
                    if(strpos($searchInput, "/") !== false){ $sinal = "/"; }

                    //divide a string. nome da rua e o número da casa em variavéis diferentes
                    $numSearch=-1;
                    if(!empty($sinal)){
                        $numSearch = intval(substr($searchInput, strpos($searchInput, $sinal)+1));
                        $searchInput = substr($searchInput, 0, strpos($searchInput, $sinal));
                    }

                    //limpa a string nome - retirando os espaços
                    $searchInput = str_replace("    ", " ", $searchInput);
                    $searchInput = str_replace("   ", " ", $searchInput);
                    $searchInput = str_replace("  ", " ", $searchInput);
                    $searchInput = trim($searchInput);

                    //realiza as buscas no banco de dados
                    $resultado = "";
                    $anoI = (intval(intval($PostData['anoI'])/10))*10;
                    $anoF = (intval(intval($PostData['anoF'])/10))*10;
                    for($j=$anoF;$j>=$anoI;$j-=10){
                        $camadaAtual = $j.',';

                        $sql="SELECT * FROM map_ruas_betonoronha WHERE upper(nome) LIKE upper('%{$searchInput}%') AND camadas LIKE '%{$camadaAtual}%' ORDER BY nome ASC limit 7";
                        $result = pg_query($conn->getConn(), $sql);

                        if(pg_num_rows($result) > 0){
                            $ruas = pg_fetch_all($result);
                            foreach ($ruas as $rua){
                                extract($rua);
                                if(strpos($resultado, $id." -") === false){
                                    if($numSearch>0){
                                        if($numSearch%2==0){
                                            if($numSearch>=$nipar && $numSearch<=$nfpar){
                                                $resultado .= "<p><span style='display:none;'>".$id." - (".$niimpar.",".$nfimpar.",".$nipar.",".$nfpar.")</span> ".$nome."
                                                <span style='color:#454545; font-size:0.75em'> ".$bairro."</span><button id=".$id." geom='geomeria'>&#10146;</button></p>";
                                            }
                                        }else{
                                            if($numSearch>=$niimpar && $numSearch<=$nfimpar){
                                                $resultado .= "<p><span style='display:none;'>".$id." - (".$niimpar.",".$nfimpar.",".$nipar.",".$nfnfparright.")</span> ".$nome."
                                                <span style='color:#454545; font-size:0.75em'> ".$bairro."</span><button id=".$id." geom='geomeria'>&#10146;</button></p>";
                                            }
                                        }
                                    }else{
                                        $resultado .= "<p><span style='display:none;'>".$id." - (".$niimpar.",".$nfimpar.",".$nipar.",".$nfpar.")</span> ".$nome."
                                        <span style='color:#454545; font-size:0.75em'> ".$bairro."</span><button id=".$id." geom='geomeria'>&#10146;</button></p>";
                                    }
                                    
                                }
                            }
                        }
                    }
                    $jSON['sucess'] = $resultado;

                }else{
                    $jSON['trigger'] = AjaxErro('Database not conected!', E_USER_ERROR);
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
