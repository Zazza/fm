$(document).ready(function(){
	$("#utree > #Pstructure").treeview({
		persist: "location",
		collapsed: true
    });

	$('#Prall').click(function(){
		if ($("#Prall").attr("checked")) {
			$('#Pstructure input:checkbox:enabled').each(function(){this.disabled = !this.disabled});
			$('#Pstructure input:checkbox').removeAttr("checked");
		} else {
			$('#Pstructure input:checkbox:disabled').each(function(){this.disabled = !this.disabled});
			$('#Pstructure input:checkbox').removeAttr("checked");
		}
		
		$('#Prall').removeAttr("disabled");
	});

	$('.Pgruser').click(function(){
		$('input.Pg' + $(this).val() + ':checkbox').each(function(){this.disabled = !this.disabled});
		$('#Prall').removeAttr("checked");
	});

	$('.Pcusers').click(function(){
		$('#Prall').removeAttr("checked");
	});
});

if($("ul.dropdown").length) {
	$("ul.dropdown li").dropdown();
};

$.fn.dropdown = function() {

	return this.each(function() {

		$(this).hover(function(){
			$(this).addClass("hover");
			$('> .dir',this).addClass("open");
			$('ul:first',this).css('visibility', 'visible');
		},function(){
			$(this).removeClass("hover");
			$('.open',this).removeClass("open");
			$('ul:first',this).css('visibility', 'hidden');
		});

	});

}

var url;

function fmInit(path) {
    url = path;
    uploader();
}

function refreshurl(refreshurl) {
	document.location.href = refreshurl;
}

function uploader() {
	$.ajax({
		type: "POST",
		url: url + 'ajax/fm/',
		data: "action=files",
		success: function(res) {
			$("#fm_filesystem").html(res);
			createUploader();
		}
	});
}

function createUploader() {
	var uploader = new qq.FileUploader({
		element: document.getElementById('file-uploader-demo1'),
		action: url + 'ajax/fm/',
		params: {
			action: 'save'
		},
		onSubmit: function(id, fileName){ 
			$.ajax({
		    	type: "POST",
		    	url: url + 'ajax/fm/',
		    	data: "action=issetFile&file=" + encodeURIComponent(fileName),
		    	async: false
		    })
		},
        onComplete: function(id, fileName, responseJSON){
            $('#' + id + '').fadeOut('slow');
            
            update();
            
            addElement(parseInt($('#fm_lastIdRow').val()) + id + 1, fileName);
            
            $('#fm_empty').fadeOut('medium');
            
            if ($('#fm_max').val() < (parseInt($('#fm_lastIdRow').val()) + id + 1)) {
                $('#fm_max').val(parseInt($('#fm_lastIdRow').val()) + id + 1);
            }
        }
	})
};

function update() {
	$.ajax({
		type: "POST",
		url: url + 'ajax/fm/',
		data: "action=getTotalSize",
		success: function(res) {
			$("#fm_total").html(res);
            
            if (res == "0&nbsp;Б") {
                $("#fm_uploadDir").html('<div id="fm_empty" style="text-align: center; width: 100%">пусто</div>');
            }
		}
	});
};

function addElement(id, fileName) {
    var fname = fileName;
    var ext = fname.substr(fname.lastIndexOf(".")+1, fname.length-fname.lastIndexOf(".")-1);
    ext = ext.toLowerCase();
        
    var rowInsert = '<div id="fm_file' + id + '" class="fm_unsellabel" style="text-align: center; cursor: pointer"><a class="fm_pre" name="' + fileName + '" id="fm_filename' + id + '"><img src="' + url + 'img/ftypes/loading.png" style="width: 50px" alt="[FILE]" /></a><div style="font-weight: bold">' + fileName + '</div>';
    
    rowInsert += '<div style="color: #777; cursor: pointer" onclick="uploader()">обновить</div>';
    rowInsert += '</div>';
    
    $("#fm_uploadDir").prepend(rowInsert);
    
    pre();
};

function del(fname, id) {
    var data = "action=delfile&fname=" + encodeURIComponent(fname);
	$.ajax({
		type: "POST",
		url: url + 'ajax/fm/',
		data: data,
		success: function(res) {
			$("#" + id + "").fadeOut("fast");
			$("#" + id + "").removeClass("fm_sellabel");
            
            update();
		}
	})
};

function chdir(dir) {
    $.ajax({
    	type: "POST",
    	url: url + 'ajax/fm/',
    	data: "action=chdir&dir=" + dir,
    	success: function(res) {
    		$("#fm_filesystem").html(res);
    	}
    })
};

$('#fm_dirname').watermark('Имя папки');

function shDirRight(did) {
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=shDirRight&did=" + did,
    	success: function(res) {
    		$('#dirDialog').html(res);
    		
    		$('#dirDialog').dialog({
    		    buttons: {
    				"Закрыть": function() { $(this).dialog("close"); }
    			},
    			width: 450,
    			height: 470
    		});
    	}
    });
}

function addFileText() {
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=addFileText&text=" + encodeURIComponent($("#fText").val()) + "&md5=" + $("#fid").val(),
    	success: function(res) {
    		$("#dfiletext").prepend(res);
    	}
    });
}

function getFileText(md5) {
	var text = null;
	
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=getFileText&md5=" + md5,
    	success: function(res) {
    		text = res;
    	}
    });

    return text;
}

function getFileHistory(md5) {
	var text = null;
	
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=getFileHistory&md5=" + md5,
    	success: function(res) {
    		text = res;
    	}
    });

    return text;
}

function getCurDirName() {
	var text = null;
	
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=getCurDirName",
    	success: function(res) {
    		text = res;
    	}
    });

    return text;
}

function getFileChmod(md5) {
	var text = null;
	
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=getFileChmod&md5=" + md5,
    	success: function(res) {
    		text = res;
    	}
    });

    return text;
}

function getMD5Name(fname) {
	var md5 = null;
	
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=getFileName&name=" + encodeURIComponent(fname),
    	success: function(res) {
    		md5 = res;
    	}
    });

    return md5;
}

function getShare(md5) {
	var data = null;
	
	$.ajax({
    	type: "POST",
    	async: false,
    	url: url + 'ajax/fm/',
    	data: "action=getShare&md5=" + md5,
    	success: function(res) {
    		data = res;
    	}
    });

    return data;
}

function createDir() {
    var _dirName = encodeURIComponent($("#fm_dirname").val());
    if (_dirName.length == 0) {
    	$('<div title="Уведомление">Имя папки не задано!</div>').dialog({
    		modal: true,
    	    buttons: {
                "Закрыть": function() { $(this).dialog("close"); }
    		}
    	});
    } else {
        $.ajax({
        	type: "POST",
        	url: url + 'ajax/fm/',
        	data: "action=createDir&dirName=" + _dirName,
        	success: function(res) {
        		if (res == "error") {
        			$('<div title="Ошибка">Папка с таким именем существует!</div>').dialog({
        	    		modal: true,
        	    	    buttons: {
        	                "Закрыть": function() { $(this).dialog("close"); }
        	    		}
        	    	});
        		} else {
        			$("#fm_filesystem").html(res);
        		}
        	}
        })
    }
};

function rmDirDialog(dirName) {
	$('<div title="Уведомление">Вы действительно хотите удалить директорию <b>' + dirName + '</b>?</div>').dialog({
		modal: true,
	    buttons: {
            "Нет": function() { $(this).dialog("close"); },
			"Да": function() { rmDir(encodeURIComponent(dirName)); $(this).dialog("close"); }
		}
	});
}

function rmDir(dirName) {
    $.ajax({
    	type: "POST",
    	url: url + 'ajax/fm/',
    	data: "action=rmDir&dirName=" + dirName,
    	success: function(res) {
            $("#fm_filesystem").html(res);
    	}
    })
};

function pastFiles() {
	$.ajax({
    	type: "POST",
    	url: url + 'ajax/fm/',
    	data: "action=moveFiles",
    	success: function(res) {
            $("#fm_filesystem").html(res);
            $("#clip").html("");
    	}
    });
}

function admin() {
	$.ajax({
    	type: "POST",
    	url: url + 'ajax/fm/',
    	data: "action=admin",
    	success: function(res) {
            $("#fm_filesystem").html(res);
    	}
    });
}

function empty() {
    $(".qq-upload-list").text("");
}

function share(md5) {
	var fname = null;
	var desc = null;
	
	$.ajax({
    	type: "POST",
    	url: url + 'ajax/fm/',
    	data: "action=share&md5=" + md5,
    	dataType: 'json',
    	success: function(res) {
    		$.each(res, function(key, val) {
    			if (key == "fid") {
    				fid = val;
    			} else if (key == "desc") {
    				desc = val;
    			} else if (key == "action") {
    				if (val == "share") {
    					$("#shareName").show();
            			$(".fname").text(desc);

    					$("#fs_" + fid).show();
    				} else if (val == "unshare") {
    					$("#share").removeAttr("checked");
    	    			$("#shareName").hide();
    	    			
    					$("#fs_" + fid).hide();
    				}
    			}
    		});
    	}
    });
}

function delUserConfirm(uid) {
	$('<div title="Удаление пользователя">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delUser(uid); $(this).dialog("close"); }
		},
		width: 240
	});
}

function delUser(uid) {
    var data = "action=delUser&uid=" + uid;
	$.ajax({
		type: "POST",
		url: url + "ajax/users/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
}

function delGroupConfirm(gid) {
	$('<div title="Удаление группы">Удалить?</div>').dialog({
		modal: true,
	    buttons: {
			"Нет": function() { $(this).dialog("close"); },
			"Да": function() { delGroup(gid); $(this).dialog("close"); }
		},
		width: 240
	});
}

function delGroup(gid) {
    var data = "action=delGroup&gid=" + gid;
	$.ajax({
		type: "POST",
		url: url + "ajax/users/",
		data: data,
		success: function(res) {
            document.location.href = document.location.href;
		}
	});
}