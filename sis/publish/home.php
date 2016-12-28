<?php
$AdminLevel = 2;
if (empty($DashboardLogin) || empty($Admin) || $Admin['level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>

<section class="create_content">
    <div class="content">
        <h1>Publish Maps</h1>

        <?php
            $service = "http://localhost:8080/geoserver/"; //url do geoserver
            $request = "rest/workspaces/pauliceia"; // Local dos workspaces
            $url = $service . $request;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //option to return string

            //AUTENTICAÇÃO
            $passwordStr = "admin:geoserver"; // replace with your username:password
            curl_setopt($ch, CURLOPT_USERPWD, $passwordStr);

            //DELETE data
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/atom+xml"));

            //DELETE return code
            $successCode = 200;

            //tratando o resultado
            $buffer = curl_exec($ch);
            $buffer = strip_tags(trim($buffer));
            $pos1 = strpos($buffer, '"')+1;
            $buffer = substr($buffer, $pos1);
            $pos2 = strpos($buffer, '"')+1;
            $buffer = substr($buffer, $pos2);

            $buffer = trim($buffer);
            $newbuffer = explode("\n", $buffer);

            curl_close($ch);

        ?>
        </form>
        <div class="clear"></div>
    </div>
</section>