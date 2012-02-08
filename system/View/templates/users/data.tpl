<div style="overflow: hidden">
<label>

<div style="float: left">

<p>
<a style="cursor: pointer; color: black; font-weight: bold">{{ data.login }}</a>
{% if registry.ui.admin %}
<br />
<a style="font-size: 10px; text-decoration: none" href="{{ registry.uri }}users/edituser/{{ data.uid }}/" style="margin-left: 10px">
[правка]
</a>
<a style="font-size: 10px; text-decoration: none" onclick="delUserConfirm({{ data.uid }})" style="cursor: pointer; margin-left: 10px">
[удалить]
</a>
{% endif %}
</p>

</div>

</label>
</div>