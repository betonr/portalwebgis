# PORTAL WEB GIS

O portal WEB-GIS é um sistema web, desenvolvido nas tecnologias HTML5, CSS3, JAVASCRIPT(openlayers3 e jQuery) e PHP.
Este se conecta a base de dados postgresql, para utilizar-se da extensão POSTGIS (referente a dados espaciais);

O portal possui 3 níveis de usuário:
  - Administrador, Responsável e Colaborador;

Suas funcionalidades são:
 - Gerenciar os usuários do sistema;
 - Gerenciar mapas (tabelas) no banco de Dados;
 - Publicar mapas criados pelo portal no GEOSERVER;
 - Inserir, editar e deletar conteúdos dos mapas gráficamente;
 - Visualizar mapas publicados no GEOSERVER;

=================================================================================
=================================================================================
 # Instalação do portal em uma máquina local


Pré-requisitos, ter os seguintes softwares instalados:
 - tomcat 7 ou superior
 - GeoServer
 - postgresql, com extensão POSTGIS habilitada
 - apache
 - php 5.6


Passo-a-Passo INSTALAÇÃO:

 1° -> fazer o download do GEOSERVER e dos mapas default no link( link )

 2° -> extrair os arquivos em uma pasta do seu computador

 3° -> copiar os arquivos da pasta geoserver_tpl e colar dentro da pasta de workspace do seu geoserver, geralmente em: geoserver/data_dir/workspace/

 4° -> copiar os arquivos da pasta dados_geo e colar dentro da pasta 'documents' em seu sistema operacional

 5° -> fazer um clone do projeto, dentro do seu servidor local(apache), geralmente em: c:var/www/html/

 6° -> criar um banco de dados ESPACIAL (com extensão postgis ativada) chamado 'db_pauliceia', no postgresql

 7° -> importar as tabelas ( https://drive.google.com/open?id=0B_HLDpnyXInqZmY3dFpwb3pOSkE ) no banco de dados criado no tópico acima

 8° -> executar o geoserver (geralmente na pasta: geoserver/bin/. Execute o comando sh startup.sh)

 9° -> abrir o navegador e acessar: 'localhost/portalwebgis' ou 'localhost:(porta do seu servidor)/portalwebgis'
    obs: o login e senha do usuário admin está abaixo, com ele é possível logar e criar os demais usuários
    - login: admin@pauliceia.com.br
    - senha: admin


 ** Tutorial para o entendimento da estrutura de pastas do portal (https://drive.google.com/file/d/0B_HLDpnyXInqcnNHSXVUMmc3Wk0/view?usp=sharing)
