<article class="editStyle">
    <form action="" name="create_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="map" value="<?= $name ?>">

    <label>
        <span class="legend">&#10143; Fundo:</span>
        <input type="color" name="background" value="#ffffff" required>
    </label>

    <label>
        <span class="legend">&#10143; Transparência:</span>
        <input type="range" name="alpha" min="0" max="100" value="100" required>
    </label>

    <label>
        <span class="legend">&#10143; Borda:</span>
        <input type="color" name="background" value="#000000" required>
    </label>

    <center>
        <button class="btn">Aplicar</button>
        <button class="btn closeStyle" style="background: gray;">Fechar</button>
    </center>

    </form>
    <div class="clear"></div>
</article>