//RETIRA AS INTERAÇÕES DOS MAPAS
function divLine(fOriginal, fCorte){
    var wktdiv = new ol.format.WKT();
    var coordsdiv = fCorte.getGeometry().getCoordinates();
          coordsdiv = coordsdiv.toString().split(",");
    var geomLine = wktdiv.writeFeature(fOriginal);
        var valTextI = geomLine.indexOf('(');
        var valTextF = geomLine.indexOf(')');
    var AlterGeomLine = geomLine.substring(valTextI+1, valTextF);
    var arrayGeom = AlterGeomLine.split(",");

    var i = 0;
    var actDiv = 0;
    while(i<arrayGeom.length-1){
        var vInicial = arrayGeom[i].split(" ");
        var vFinal = arrayGeom[i+1].split(" ");

        vInicial[0] = parseFloat(vInicial[0]);
        vInicial[1] = parseFloat(vInicial[1]);
        vFinal[0] = parseFloat(vFinal[0]);
        vFinal[1] = parseFloat(vFinal[1]);

        coordsdiv[0] = parseFloat(coordsdiv[0]);
        coordsdiv[1] = parseFloat(coordsdiv[1]);
        coordsdiv[2] = parseFloat(coordsdiv[2]);
        coordsdiv[3] = parseFloat(coordsdiv[3]);

        //calculo da primeira equação
        var mFirst = (vFinal[1]-vInicial[1])/(vFinal[0]-vInicial[0]);
        var cFirst = -(mFirst*vInicial[0])+vInicial[1];

        //calculo da segunda equação
        var mSecond = (coordsdiv[3]-coordsdiv[1])/(coordsdiv[2]-coordsdiv[0]);
        var cSecond = -(mSecond*coordsdiv[0])+coordsdiv[1];

        //soma das equações
        if(mFirst != mSecond){

            var somaM = mFirst-mSecond;
            var somaC = -cFirst+cSecond;

            var x = somaC/somaM;
            var y = (mFirst*x)+cFirst;

            //verifica se o ponto criado está localizado em cima de um reta desenhada
            if(((vInicial[0]<=x) && (vFinal[0]>=x)) || ((vInicial[0]>=x) && (vFinal[0]<=x))){
                if(((vInicial[1]<=y) && (vFinal[1]>=y)) || ((vInicial[1]>=y) && (vFinal[1]<=y))) {

                    if(((coordsdiv[0]<=x) && (coordsdiv[2]>=x)) || ((coordsdiv[0]>=x) && (coordsdiv[2]<=x))){
                        if(((coordsdiv[1]<=y) && (coordsdiv[3]>=y)) || ((coordsdiv[1]>=y) && (coordsdiv[3]<=y))) {

                            var confDiv = confirm("Confirmar Divisão? ");

                            if(confDiv){
                                //primeira linha da divisão
                                var firstLine = geomLine;
                                var vDiv = firstLine.indexOf(vFinal[0]+' '+vFinal[1]);
                                firstLine = firstLine.substring(0, vDiv);
                                firstLine = firstLine+x+' '+y+')';

                                //segunda linha da divisão
                                var secondLine = geomLine;
                                secondLine = secondLine.substr(vDiv);
                                secondLine = 'LINESTRING('+x+' '+y+','+secondLine;

                                //executando ação no banco de dados
                                var DivId = fOriginal.get('id');
                                var DivTabName = fOriginal.get('tabName');
                                var DivLinesGeom = firstLine+'+'+secondLine;
                                var DivAutor = fOriginal.get('rep_id');
                                var DivCallback = 'Draw';
                                var DivCallback_action = 'draw_dividir';
                                $.post('ajax/' + DivCallback + '.ajax.php', {callback: DivCallback, callback_action: DivCallback_action, div_id: DivId, tb_name: DivTabName, lines: DivLinesGeom, autor: DivAutor}, function (data) {}, 'json');
                                actDiv = 1;

                            }
                            $i=1000000;
                            break;

                        }
                    }

                }
            }

        }

        i++;
    }

    return actDiv;

}