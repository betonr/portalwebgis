<article class="editStyle complex">

    <input type="hidden" name="map">
    <input type="hidden" name="id">

    <label>
        <span class="legend">&#10143; Fundo:</span>
        <input type="color" name="background" value="#ffffff" required>
    </label>

    <label>
        <span class="legend">&#10143; Transparência:</span>
        <input type="range" name="alpha" min="0" max="1" value="1" step="0.1" required>
    </label>

    <label>
        <span class="legend">&#10143; Borda:</span>
        <input type="color" name="stroke" value="#000000" required>
    </label>

    <center>
        <button class="btn actEditStyleC">Aplicar</button>
        <button class="btn closeStyle" style="background: gray;">Fechar</button>
    </center>

    <div class="clear"></div>
</article>
<article class="editStyle simple">

    <input type="hidden" name="map">
    <input type="hidden" name="id">

    <label>
        <span class="legend">&#10143; Transparência:</span>
        <input type="range" name="alpha" min="0" max="1" value="1" step="0.1" required>
    </label>

    <center>
        <button class="btn actEditStyle">Aplicar</button>
        <button class="btn closeStyle" style="background: gray;">Fechar</button>
    </center>

    <div class="clear"></div>
</article>