<span style="float: left; margin-right: 10px" class="btn btn-primary" onclick="shUploader()">Upload files</span>

<span style="float: left" onclick="copyFiles()" class="btn" id="btnCopy">
	C Copy
</span>

<span style="float: left" onclick="pastFiles()"  class="btn" id="btnPast">
	P Past
</span>

<ul class="dropdown dropdown-horizontal dropdown-upward">
<li class="topmenubutton" style="cursor: default">
<a style="cursor: default" class="dir">Buffer</a>
<ul id="clip"></ul>
</li>
</ul>

<span style="float: left" onclick="createDirDialog()" class="btn">
	Ins Create
</span>

<span style="float: left" onclick="delmany()" class="btn">
	Del Remove
</span>

{% if registry.ui.admin %}
<span onclick="admin()" id="admbtn" class="btn btn-danger" style="float: left; margin-left: 10px">"Admin" Mode</span>


<div id="adminFunc" style="display: none; float: right">

		<span onclick="delmanyrealConfirm()" class="btn btn-danger"> <i
			class="icon-remove icon-white"></i>
			Full Remove
		</span>

		<span onclick="restore()" class="btn btn-success"> <i
			class="icon-repeat icon-white"></i>
			Repair
		</span>
</div>
{% endif %}
