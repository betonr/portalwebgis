function clearInteraction(){
        $("#lineOptions").find("p").removeClass('activeOptions');
        //$("#poligonsOptions").find("p").removeClass('activeOptions');
        map.getInteractions().forEach(function (interaction) {
            map.removeInteraction(interaction);
        });
}

function generationWkt(e, type){
    var featureWkt;

    var featureClone = e.clone();
    featureWkt = wkt.writeFeature(featureClone);

    if(type == "insert"){
        $(".inserirDado input[name='geom']").val(featureWkt);
    }else if(type == "edit"){
        $(".editDado input[name='geom']").val(featureWkt);
    }

}
