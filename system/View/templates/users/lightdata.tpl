<div style="overflow: hidden">
<label>

<div style="float: left; margin-right: 10px">
{% if not radio %}
<input type="checkbox" name="ruser[]" id="user{{ data.uid }}" value="{{ data.uid }}" class="g{{ data.gid }} cusers" />
{% else %}
<input type="radio" name="delegate" id="user{{ data.uid }}" value="{{ data.uid }}" class="g{{ data.gid }} cusers" />
{% endif %}
</div>

<div style="float: left; text-align:center; margin-right: 10px">
	{% if data.avatar %}
	<img class="avatar" id="ava" src="{{ data.avatar }}" alt="аватар" />
	{% else %}
	<img class="avatar" id="ava" src="{{ registry.uri }}img/noavatar.gif" alt="аватар" />
	{% endif %}
	
	{% if data.status %}
	<div style="font-size: 10px; color: green">[online]</div>
	{% else %}
	<div style="font-size: 10px; color: red">[offline]</div>
	{% endif %}
</div>

<div style="float: left">
<input type="hidden" id="hu{{ data.uid }}" value="{{ data.soname }} {{ data.name }}" />

<p>
<a style="cursor: pointer; color: black; font-weight: bold" onclick="getUserInfo('{{ data.uid }}')">{{ data.soname }} {{ data.name }}</a>
<span style="color: #777; margin-left: 10px">{{ data.signature }}</span>
</p>

</div>

</label>
</div>