<div class="searchEnd">
    <h1>Pesquise o endereço</h1>
    <form name="searchForm_end" id="searchForm_end" method="post">
        <input type="hidden" name="callback" value="Search" />
        <input type="hidden" name="callback_action" value="search_end" />

		<div style="width:100%; padding:0 15px;">
			<p>ano: <b>Inicial</b> / <b>Final</b>:</p>
			<input type="number" class="years" min="1868" max="1940" name="anoI" value="1868" /> / 
			<input type="number" class="years" min="1868" max="1940" name="anoF" value="1940" />
		</div>

        <div style="width:100%; position:relative">
        <input type="text" name="searchInput" value="" tabindex="1" placeholder="Pesquise aqui ... " />
        <img class="search_load" alt="Enviando Requisição!" title="Enviando Requisição!" src="images/loadSearch.gif" style="width: auto; display: none;" />
        </div>
    </form>

    <div id="searchResposta" class="searchResposta"></div>
</div>

