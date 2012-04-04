<form method="post" action="{{ registry.uri }}users/edituser/{{ post.uid }}/">

{% if err %}
{% for part in err %}
<p style="color: red">{{ part }}</p>
{% endfor %}
{% endif %}

<div style="margin-bottom: 50px">
<h2>User edit</h2>

<p><b>Login</b></p><p><input name='login' type='text' size='60' value="{{ post.login }}" /></p>
<p><b>Password</b></p><p><input name='pass' type='password' value="{{ post.pass }}" /></p>

<div class="well">

<div class="form-inline">
    <label class='radio'><input name='priv' value="admin" type='radio' {% if post.priv == "admin" %} checked {% endif %} />&nbsp;<b>Admin</b></label>
    <label class='radio'><input name='priv' value="null" type='radio' {% if post.priv == FALSE  %} checked {% endif %} />&nbsp;<b>User</b></label>
</div>

<div class="form-inline" style="margin: 10px 0">
<b>Group</b>&nbsp;
<select name="gid">
{% for part in group %}
<option value="{{ part.sid }}" {% if post.gid == part.sid %} selected="selected" {% endif %}>{{ part.sname }}</option>
{% endfor %}
</select>
</div>

<div class="form-inline">
<b>Quota</b>&nbsp;
<input class="span1" type="text" name="quota_val" value="{{ post.quota_val }}">
<select class="span1" name="quota_unit">
	<option value="mb" {% if post.quota_unit == "mb" %}selected="selected"{% endif %}>Mb</option>
	<option value="gb" {% if post.quota_unit == "gb" %}selected="selected"{% endif %}>Gb</option>
</select>
</div>

</div>

<p style="margin-top: 20px"><input name='edituser' type='submit' value='OK' /></p>

</div>

</form>