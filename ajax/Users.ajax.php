<?php
session_start();
require '../config/infoBase.php';

usleep(50000);

/* DEFINE CALLBACK (INFO) E RECUPERA POST - usuarios
* página reponsável por receber os dados enviados pelos formulários,
* tratar os dados, executar as ações necessárias e enviar uma resposta ao usuário
*
* @author Beto Noronha
*/
$jSON = null;
$CallBack = 'Info';
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
            /* EDIÇÃO DE USUÁRIO NO BD
            * case responsável por verificar se o usuário existe no BD
            * e posteriormente editar seu conteúdo
            */
        case 'user_edit':
                if($conn->getConn()){

                    if(empty($PostData['passAtual']) && empty($PostData['newPass']) && empty($PostData['repNewPass'])){
                        $sql = "UPDATE tb_users SET name='{$PostData['name']}', phone='{$PostData['phone']}', institution='{$PostData['institution']}' WHERE id={$PostData['id']}";
                        $result = pg_query($conn->getConn(), $sql);
                        $jSON['trigger'] = AjaxErro('Usuário editado com sucesso!');
                    }else{
                        if(empty($PostData['passAtual']) || empty($PostData['newPass']) || empty($PostData['repNewPass'])){
                            $jSON['trigger'] = AjaxErro('Digite todos os campos!', E_USER_ERROR);
                        }else{
                            $passAtual = hash('sha512', $PostData['passAtual']);
                            $sql = "SELECT * FROM tb_users WHERE id={$PostData['id']} AND password='{$passAtual}'";
                            $result = pg_query($conn->getConn(), $sql);
                            if(pg_num_rows($result) > 0){
                                $jSON['trigger'] = AjaxErro('Usuário editado com sucesso!');
                                if($PostData['newPass'] == $PostData['repNewPass']){
                                    $newPass = hash('sha512', $PostData['newPass']);
                                    $sql = "UPDATE tb_users SET name='{$PostData['name']}', phone='{$PostData['phone']}', institution='{$PostData['institution']}', password='{$newPass}' WHERE id={$PostData['id']}";
                                    $result = pg_query($conn->getConn(), $sql);
                                    $jSON['trigger'] = AjaxErro('Usuário editado com sucesso!');
                                    $jSON['redirect'] = 'dashboard.php?p=info/home';
                                }else{
                                    $jSON['trigger'] = AjaxErro('A nova senha e a repetição dela, estão diferentes!', E_USER_ERROR);
                                }
                             }else{
                                $jSON['trigger'] = AjaxErro('Senha Atual Incorreta!', E_USER_ERROR);
                             }
                        }
                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Banco de Dados não conectado, contate o administrador!', E_USER_ERROR);
                }
            break;

            /* ATIVAÇÃO DE USUÁRIO EM SUA REDE DE COLABORADORES
            * case responsável por verificar se o usuário existe ou se o mesmo está em sua rede de colabordores
            * e caso não esteja, adiciona-se o usuário como seu colaborador
            */
            case 'user_active':
                if($conn->getConn()){

                    if(empty($PostData['responsavel']) || empty($PostData['email'])){
                        $jSON['trigger'] = AjaxErro('Digite o e-mail do usuário!', E_USER_ERROR);
                    }else{
                        $sql = "SELECT * FROM tb_users WHERE email='{$PostData['email']}'";
                        $result = pg_query($conn->getConn(), $sql);
                            if(pg_num_rows($result) > 0){
                            $user = pg_fetch_all($result)[0];
                                $sql = "SELECT * FROM tb_users WHERE id={$user['id']} AND rep_id LIKE '%{$PostData['responsavel']},%'";
                                $result = pg_query($conn->getConn(), $sql);
                                if(pg_num_rows($result) > 0){
                                    $jSON['trigger'] = AjaxErro('O usuário desejado já é seu colaborador!', E_USER_ERROR);
                                }else{
                                    if($user['rep_id'] == ', ' || $user['rep_id'] == '' || $user['rep_id'] == ','){
                                        $user['rep_id'] = $PostData['responsavel'].',';
                                    }else{
                                        $user['rep_id'] = $user['rep_id'].$PostData['responsavel'].',';
                                    }
                                    $sql = "UPDATE tb_users SET rep_id='{$user['rep_id']}' WHERE id={$user['id']}";
                                    $result = pg_query($conn->getConn(), $sql);
                                    $jSON['trigger'] = AjaxErro('Colaborador adicionado com sucesso!');
                                    $jSON['redirect'] = 'dashboard.php?p=info/add';
                                }
                             }else{
                                $jSON['trigger'] = AjaxErro('E-mail do usuário não foi encontrado no nosso sistema!', E_USER_ERROR);
                             }
                        }

                }else{
                    $jSON['trigger'] = AjaxErro('Banco de Dados não conectado, contate o administrador!', E_USER_ERROR);
                }
            break;

            /* ADICIONAR USUÁRIO NO BD
            * case responsável por verificar os dados inseridos
            * e salvar estes no BD, criando assim um novo usuário
            */
            case 'user_add':
            if (in_array('', $PostData)){
                $jSON['trigger'] = AjaxErro('Complete todos os campos!', E_USER_NOTICE);
            }else{
                if($conn->getConn()){
                    $sql = "SELECT * FROM tb_users WHERE email='{$PostData['email']}'";
                    $result = pg_query($conn->getConn(), $sql);
                    if(pg_num_rows($result) > 0){
                        $jSON['trigger'] = AjaxErro('E-mail já cadastrado em nosso sistema!', E_USER_ERROR);
                    }else{
                        $date = date("Y/m/d");
                        if($PostData['pass'] == $PostData['rePass']){
                            $PostData['pass'] = hash('sha512', $PostData['pass']);
                            if(isset($PostData['responsavel']) && !empty($PostData['responsavel'])){
                                $sql = "INSERT INTO tb_users (name, email, phone, institution, password, level, datestart, status, rep_id) VALUES ('{$PostData['name']}', '{$PostData['email']}', '{$PostData['phone']}', '{$PostData['institution']}', '{$PostData['pass']}', 1, '{$date}', 1, '{$PostData['responsavel']},')";
                            }else{
                                $sql = "INSERT INTO tb_users (name, email, phone, institution, password, level, datestart, status, rep_id) VALUES ('{$PostData['name']}', '{$PostData['email']}', '{$PostData['phone']}', '{$PostData['institution']}', '{$PostData['pass']}', 2, '{$date}', 1, '1')";
                            }
                            $result = pg_query($conn->getConn(), $sql);
                            $jSON['trigger'] = AjaxErro('Usuário adicionado com sucesso!');
                            if(isset($PostData['responsavel']) && !empty($PostData['responsavel'])){
                                $jSON['redirect'] = 'dashboard.php?p=info/add';
                            }else{
                                $jSON['redirect'] = 'dashboard.php?p=info/addresp';
                            }
                        }else{
                            $jSON['trigger'] = AjaxErro('A senha e a repetição dela, estão diferentes!', E_USER_ERROR);
                        }
                    }

                }else{
                    $jSON['trigger'] = AjaxErro('Banco de Dados não conectado, contate o administrador!', E_USER_ERROR);
                }
            }
            break;

            /* DELETAR USUÁRIO DO TIPO colaborador (nível 2) DO BD
            * case responsável por verificar se o usuário(id) existe no BD
            * e posteriormente deleta-lo
            */
            case 'user_del':
                if($conn->getConn()){
                    if(isset($PostData['del_id']) && !empty($PostData['del_id'])){
                        $userId = $PostData['del_id'];
                        $userResp = 0;
                    }else{
                        $userId = $PostData['responsavel'];
                        $userResp = 1;
                    }
                    $sql = "SELECT * FROM tb_maps WHERE rep_id='{$userId}'";
                    $result = pg_query($conn->getConn(), $sql);
                    if(pg_num_rows($result) > 0){
                        $jSON['trigger'] = AjaxErro('Você possui mapas na sua conta, exclua-os primeiro!', E_USER_ERROR);
                    }else{
                        if($userResp==0){
                            $sql = "SELECT rep_id FROM tb_users WHERE id='{$userId}'";
                            $result = pg_query($conn->getConn(), $sql);
                            $idRep = pg_fetch_all($result)[0];
                            $newIdRep = str_replace($PostData['rep_id'].',', '', $idRep['rep_id']);
                            $sql="UPDATE tb_users SET rep_id='{$newIdRep}' WHERE id='{$userId}'";
                            $result = pg_query($conn->getConn(), $sql);
                            $jSON['redirect'] = 'dashboard.php?p=info/delcolaborador';
                        }else{
                            $sql = "DELETE FROM tb_users WHERE id={$userId}";
                            $result = pg_query($conn->getConn(), $sql);
                            unset($_SESSION['userLogin']);
                            $jSON['trigger'] = AjaxErro('Sua conta foi excluída com Sucesso!');
                            $jSON['redirect'] = './index.php';
                        }
                    }
                }else{
                    $jSON['trigger'] = AjaxErro('Banco de Dados não conectado, contate o administrador!', E_USER_ERROR);
                }
            break;

            /* DELETAR USUÁRIO DO TIPO responsável (nivel3) DO BD
            * case responsável por verificar se o usuário(id) existe no BD
            * e posteriormente deleta-lo
            */
            case 'user_delresp':
                if($conn->getConn()){
                    $sql = "DELETE FROM tb_maps WHERE rep_id='{$PostData['del_id']}'";
                    $result = pg_query($conn->getConn(), $sql);

                    $sql = "DELETE FROM tb_users WHERE id={$PostData['del_id']}";
                    $result = pg_query($conn->getConn(), $sql);
                    $jSON['redirect'] = 'dashboard.php?p=info/delresp';
                }else{
                    $jSON['trigger'] = AjaxErro('Banco de Dados não conectado, contate o administrador!', E_USER_ERROR);
                }
            break;

            /* PESQUISA DINÂMICA DE USUÁRIO NO BD
            * case responsável realizar uma pesquisa dos usuário cadastrados no BD, de acordo com as especificações passadas pelo usuário.
            * caso seja encontrado algum usuário, redireciona-se a uma url com os parametros escolhidos
            */
            case 'search_user':
                if(empty($PostData['search'])){
                    $jSON['redirect'] = 'dashboard.php?p=info/colaboradores';
                }else{
                    if($conn->getConn()){
                        if(isset($PostData['id']) && !empty($PostData['id'])){
                            $sql = "SELECT * FROM tb_users WHERE rep_id LIKE '%{$PostData['id']},%' AND (name  ~*  '{$PostData['search']}' OR email ~* '{$PostData['search']}' OR institution ~* '{$PostData['search']}')";
                            $result = pg_query($conn->getConn(), $sql);
                            if(pg_num_rows($result) <= 0){
                                $jSON['trigger'] = AjaxErro('Não foi encontrado nenhum colaborador com <b>'.$PostData['search'].'</b>', E_USER_ERROR);
                                $jSON['redirect'] = 'dashboard.php?p=info/colaboradores';
                            }else{
                                $jSON['redirect'] = 'dashboard.php?p=info/colaboradores&search='.$PostData['search'];
                            }
                        }else{
                            $sql = "SELECT * FROM tb_users WHERE level<3 AND (name  ~*  '{$PostData['search']}' OR email ~* '{$PostData['search']}' OR institution ~* '{$PostData['search']}')";
                            $result = pg_query($conn->getConn(), $sql);
                            if(pg_num_rows($result) <= 0){
                                $jSON['trigger'] = AjaxErro('Não foi encontrado nenhum usuário com <b>'.$PostData['search'].'</b>', E_USER_ERROR);
                                $jSON['redirect'] = 'dashboard.php?p=info/delresp';
                            }else{
                                $jSON['redirect'] = 'dashboard.php?p=info/delresp&search='.$PostData['search'];
                            }
                        }
                    }else{
                        $jSON['trigger'] = AjaxErro('Banco de Dados não conectado!', E_USER_ERROR);
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