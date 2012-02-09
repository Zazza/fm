<div style="font-weight: bold; margin-bottom: 20px">Текущая папка: [{{ shPath }}]</div>

<div style="overflow: hidden">

{% for part in dirs %}
{% if part.name != ".." %}

{% if part.close %}{% set opacity = "; opacity: 0.2;" %}{% else %}{% set opacity = "; opacity: 1.0;" %}{% endif %}

<div id="fm_dirs" style="text-align: center; cursor: pointer">
	<div onclick="chdir('{{ part.name }}')"><img src="{{ registry.uri }}img/ftypes/folder.png" style="width: 50px{{ opacity }}" alt="[DIR]" /></div>
	<div onclick="chdir('{{ part.name }}')" style="font-weight: bold">{{ part.name }}</div>
	
	<div>
		<a style="color: #777" onclick="shDirRight('{{ part.id }}')">[права]</a>
		<a style="color: #777" href="#" onclick="rmDirDialog('{{ part.name }}')">[удалить]</a>
	</div>
</div>
{% else %}
<div id="fm_dirs" style="text-align: center; cursor: pointer">
	<div onclick="chdir('{{ part.name }}')"><img src="{{ registry.uri }}img/ftypes/folder.png" style="width: 50px{{ opacity }}" alt="[DIR]" /></div>
	<div onclick="chdir('{{ part.name }}')" style="font-weight: bold">[..]</div>
</div>
{% endif %}
{% endfor %}

<div id="fm_uploadDir">
{% set i = 0 %}
{% for part in files %}
{% set i = i + 1 %}

{% if part.close %}{% set opacity = "; opacity: 0.2;" %}{% else %}{% set opacity = "; opacity: 1.0;" %}{% endif %}

<div id="fm_file{{ i }}" class="fm_unsellabel" style="text-align: center; cursor: pointer">
	<a class="fm_pre" name="{{ part.name }}" id="fm_filename{{ i }}"><img src="{{ registry.uri }}img/ftypes/{{ part.ico }}" style="width: 50px{{ opacity }}" alt="[FILE]" /></a>
	<div style="font-weight: bold">{{ part.shortname }}</div>
	
	<div style="color: #777">{{ part.date }}</div>
	<div style="color: #777">размер: {{ part.size }}</div>
	<div id="fs_{{ part.id }}" style="color: green; font-weight: bold; {% if part.share %}display: block{% else %}display: none{% endif %}">Share</div>
</div>
{% else %}
<div id="fm_empty" style="text-align: center; width: 100%">пусто</div>
{% endfor %}
</div>

</div>

<p style="padding: 0; margin: 4px 0"><b>Итого:</b> <span id="fm_total">{{ totalsize }}</span></p>

<input name="lastIdRow" id="fm_lastIdRow" value="{{ i }}" type="hidden" />
<input name="max" id="fm_max" value="{{ i }}" type="hidden" />

<script type="text/javascript">
xOffset = -15;
yOffset = 15;

pre();

$(function(){
	$("#tabs").tabs();
	$("#clip").html('{{ clip }}');
	
	{% if admin %}
	$("#admbtn").addClass("fmadmbtn_en");
	$("#admbtn").removeClass("fmadmbtn_dis");
	{% else %}
	$("#admbtn").addClass("fmadmbtn_dis");
	$("#admbtn").removeClass("fmadmbtn_en");
	{% endif %}
});

$("#fm_sel").click(function() {
    $(".fm_unsellabel").removeClass("fm_unsellabel").addClass("fm_sellabel");
});

$("#fm_unsel").click(function() {
    $(".fm_sellabel").removeClass("fm_sellabel").addClass("fm_unsellabel");
});

$(".fm_unsellabel").live("click", function(){
    $(this).removeClass("fm_unsellabel").addClass("fm_sellabel");
});

$(".fm_sellabel").live("click", function(){
    $(this).removeClass("fm_sellabel").addClass("fm_unsellabel");
});

function delmany() {
    for (i = 1; i <= parseInt($("#fm_max").val()); i++){
        if ($("#fm_file" + i).attr("class") == "fm_sellabel") {
            del($("#fm_filename" + i + "").attr("name"), "fm_file" + i);
        }
    };
    
    update();
};

function copyFiles() {
    var selfiles = "";
    for (i = 1; i <= parseInt($("#fm_max").val()); i++){
        if ($("#fm_file" + i).attr("class") == "fm_sellabel") {
            selfiles += "&file[]" + "=" + encodeURIComponent($("#fm_filename" + i + "").attr("name"));
        }
    };

	$.ajax({
		type: "POST",
		url: '{{ registry.uri }}/ajax/fm/',
		data: "action=copyFiles&" + selfiles,
		success: function(res) {
	        $("#clip").html(res);
		}
	});
}

function pre() {
	$(".fm_pre").click(function(){
		var fname = this.name;
		
		var md5 = getMD5Name(fname);
		
		$("#share").attr("onchange", "share('" + md5 + "')");
		
		var data = getShare(md5);
		if (data) {
			$("#share").attr("checked", "checked");
			$("#shareName").show();
			$(".fname").text(data);
		} else {
			$("#share").removeAttr("checked");
			$("#shareName").hide();
		}

		$("#fid").val(md5);

		var text = getFileText(md5);
		$("#dfiletext").html(text);
		
		$("#dopenfile").html("<b>Скачать файл: </b><a style='color: blue' href='{{ registry.uri }}attach/?filename=" + fname + "' id='fdname'>" + fname + "</a>");

		var history = getFileHistory(md5);
		$("#fdhistory").html(history);

		var chmod = getFileChmod(md5);
		$("#fdchmod").html(chmod);

		$('#fDialog').dialog({
		    buttons: {
				"Закрыть": function() { $(this).dialog("close"); }
			},
			width: 450,
			height: 470
		});
	});
		
    $(".fm_pre").hover(function(e){
    	var fname = encodeURIComponent(this.name);
    	
    	var md5 = getMD5Name(fname);

        var ext = fname.substr(fname.lastIndexOf(".") + 1, fname.length-fname.lastIndexOf(".") - 1);
        ext = ext.toLowerCase();
        
        if ( (ext == "gif") || (ext == "png") || (ext == "jpg") || (ext == "jpeg") ) {
            
    	$("body").append("<p id='fm_preview_t'><img src='{{ registry.uri }}/{{ _thumb }}" + md5 + "' alt='просмотр изображения' id='fm_t_img_pre' /></p>");
    	
    	var img = new Image();
    	img.src = "{{ _thumb }}" + md5;
    
    	$("#fm_preview_t")
    		.css("top",(e.pageY - xOffset) + "px")
    		.css("left",(e.pageX - yOffset) + "px")
    		.css("border", "0px")
    		.fadeIn("fast");
      }
    },
    function(){
    	$("#fm_preview_t").remove();
    });
};
</script>