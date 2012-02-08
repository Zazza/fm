<div class="sel" style="width: 150px; margin-bottom: 10px; font-weight: bold"><img border="0" style="vertical-align: middle;" alt="plus" src="{{ registry.uri }}img/plus-button.png" />&nbsp;<a href="{{ registry.uri }}users/addgroup/" style="text-decoration: none">Новая группа</a></div>

<table cellpadding="3" cellspacing="3" style="margin-bottom: 20px">
<tr>
<td align="center" style="font-weight: bold; font-size: 10px">удалить</td>
<td align="center" style="font-weight: bold; font-size: 10px">изменить</td>
<td align="center" style="font-weight: bold; font-size: 10px">структура</td>
<td align="center" style="font-weight: bold; font-size: 10px">имя группы</td>
{% for part in group %}
<tr>
<td align="center" style="border: 1px solid #ccc">
    <a style="cursor: pointer" onclick="delGroupConfirm({{ part.pid }})"><img style="vertical-align: middle" src="{{ registry.uri }}img/delete.png" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    <a href="{{ registry.uri }}users/editgroup/{{ part.pid }}/"><img style="vertical-align: middle" src="{{ registry.uri }}img/edititem.gif" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    <a href="{{ registry.uri }}users/structure/list/{{ part.pid }}/"><img style="vertical-align: middle" src="{{ registry.uri }}img/document-tree.png" alt="" border="0" /></a>
</td>

<td align="center" style="border: 1px solid #ccc">
    {{ part.pname }}
</td>
</tr>
{% else %}
<tr><td colspan="3" align="center" style="border: 1px solid #ccc">Пусто</td></tr>
{% endfor %}
</table>

<div class="sel" style="width: 170px; margin-bottom: 10px; font-weight: bold"><img border="0" style="vertical-align: middle;" alt="plus" src="{{ registry.uri }}img/plus-button.png" /> <a href="{{ registry.uri }}users/adduser/" style="text-decoration: none">Новый пользователь</a></div>



<div style="margin-top: 50px">
	<h3>Структура пользователей</h3>
	<div id="utree">
		<ul id="Pstructure" class="filetree">{{ list }}</ul>
	</div>
</div>