<p><b>Укажите пользователей, имеющих доступ к директории</b></p>

<div id="fchmod">

<p style="font-size: 11px"><label><input type="checkbox" name="frall" value="1" id="frall" />Выбрать всех</label></p>

<ul id="fdstructure" class="filetree">{{ list }}</ul>

<div id="setRight" style="color: green; margin-top: 10px"></div>

<p style="margin-top: 20px"><input type="button" value="Сохранить" onclick="setDirChmod()" name="setChmod" /></p>

</div>

<script type="text/javascript">
$(document).ready(function(){
    $("#fdstructure").treeview({
		persist: "location",
		collapsed: true
    });

    $.ajax({
    	type: "POST",
    	async: false,
    	url: '{{ registry.uri }}ajax/fm/',
    	data: "action=getUsersDirChmod&did={{ did }}",
    	dataType: 'json',
    	success: function(res) {
    		$.each(res, function(key, val) {
                if (val == "true") {
                    $("#" + key).attr("checked", "checked");

                    if (key == "frall") {
                    	$('#fdstructure input:checkbox').each(function(){this.disabled = !this.disabled});
                        $('#frall').removeAttr("disabled");
                    }

                    if (key.indexOf("fg") == 0) {
                    	$('input.' + key + ':checkbox').each(function(){this.disabled = !this.disabled});
                    }
                }
            });
    	}
    });

	$('#frall').click(function(){
		if ($("#frall").attr("checked")) {
			$('#fdstructure input:checkbox:enabled').each(function(){this.disabled = !this.disabled});
			$('#fdstructure input:checkbox').removeAttr("checked");
		} else {
			$('#fdstructure input:checkbox:disabled').each(function(){this.disabled = !this.disabled});
			$('#fdstructure input:checkbox').removeAttr("checked");
		}
		
		$('#frall').removeAttr("disabled");
	});

	$('.fgruser').click(function(){
		$('input.fg' + $(this).val() + ':checkbox').each(function(){this.disabled = !this.disabled});
		$('#frall').removeAttr("checked");
	});

	$('.fcusers').click(function(){
		$('#frall').removeAttr("checked");
	});
});

function setDirChmod() {
	var formData = new Array(); var i = 0;
	$("#fchmod input:checkbox").each(function(n){
		id = this.id;
		attr = this.checked;

		formData[i] = ['"' + id + '"', '"' + attr + '"'].join(":");

		i++;
	});

	var json = "{" + formData.join(",") + "}";

	$.ajax({
    	type: "POST",
    	async: false,
    	url: '{{ registry.uri }}ajax/fm/',
    	data: "action=addDirRight&json=" + json + "&did={{ did }}",
    	success: function(res) { $("#setRight").html("Права успешно изменены!"); }
	});
}
</script>