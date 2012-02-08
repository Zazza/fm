<div id="fm">
<div style="margin: 10px 40px 0 10px">
	<div id="file-uploader-demo1">
	    <noscript>
	        <p>Please enable JavaScript to use file uploader.</p>
	    </noscript>
	</div>
	
	<div style="height: 13px; border: 1px solid #DEDEDE; font-size: 11px; font-weight: bold; margin: 0 0 10px 0; padding: 4px; text-align: left">
	<div style="float: left; position: relative; bottom: 3px"><span id="fm_sel">+</span>/<span id="fm_unsel">-</span></div>
	<div style="float: right; position: relative; bottom: 3px"><span style="font-size: 10px; font-weight: bold"><a onclick="empty()" style="cursor: pointer; text-decoration: underline">очистить лог загрузки</a></span></div>
	</div>
	
	<p style="font-size: 11px; font-weight: bold; padding: 0; margin: 4px 0 10px 0">{{ shPath }}</p>

	<div id="fm_filesystem">
		<div style="text-align: center; margin-top: 30px"><img src="{{ registry.uri }}img/ajax-loader.gif" alt="ajax-loader.gif" border="0" /></div>
	</div>

	
	<!-- FILE DIALOG  -->
	<div id="fDialog" style="display: none" title="Параметры файла">
		<div id="tabs">
		
		<ul>
		<li><a href="#fdmain">Главное</a></li>
		<li><a href="#fdhistory">История</a></li>
		<li><a href="#fdchmod">Права</a></li>
		
		</ul>
		
		
		<div id="fdmain" class="tabcont" style="text-align: left">
			<input type="hidden" id="fid" />
			<div id="dopenfile" class="button" style="background-color: #E5E5D7"></div>
			<div id="dfiletext" style="margin-top: 10px"></div>
			<div style="margin-top: 20px">
				<textarea id="fText" style="height: 100px; width: 340px"></textarea>
				<p><input type="button" name="addFileText" value="Добавить запись" onclick="addFileText()"></p>
			</div>
		</div>
		
		<div id="fdhistory" class="tabcont" style="text-align: left"></div>
		
		<div id="fdchmod" class="tabcont" style="text-align: left"></div>
		
		</div>
	</div>
	<!-- END FILE DIALOG  -->
	
	<!-- DIR DIALOG  -->
	<div id="dirDialog" style="display: none; text-align: left" title="Права доступа к директории"></div>
	<!-- END DIR DIALOG  -->
	
	</div>	
</div>