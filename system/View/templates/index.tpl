<div id="fm">
<div style="margin: 10px 40px 0 10px">
	<div id="file-uploader-demo1">
	    <noscript>
	        <p>Please enable JavaScript to use file uploader.</p>
	    </noscript>
	</div>
	
	<div id="servTop">
		<div id="servSel"><span id="fm_sel">+</span>/<span id="fm_unsel">-</span></div>
		<div id="servClear"><a onclick="empty()" style="cursor: pointer">очистить лог загрузки</a></div>
	</div>

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
			<div id="dopenfile"></div>
			
			<div id="wndShare">
				<div class="button"><label><input id="share" type="checkbox" name="share" />&nbsp;Share</label></div>
				<div style="display: none; padding-top: 10px" id="shareName"><b>URL: </b>{{ registry.siteName }}{{ registry.uri }}download/?filename=<span class="fname"></span></div>
			</div>
			
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