<div style="overflow: hidden">
<label>

<div style="float: left; margin-right: 10px">
<input type="checkbox" name="fruser[]" id="user{{ data.uid }}" value="{{ data.uid }}" class="fg{{ data.gid }} fcusers" />
</div>

<div style="float: left">

<p>
<a style="cursor: pointer; color: black; font-weight: bold"">{{ data.login }}</a>
{% if registry.ui.admin %}
<br />

{% endif %}
</p>

</div>

</label>
</div>