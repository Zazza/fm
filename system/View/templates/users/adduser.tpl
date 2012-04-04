<form method="post" action="{{ registry.uri }}users/adduser/">

{% if err %}
{% for part in err %}
<p style="color: red">{{ part }}</p>
{% endfor %}
{% endif %}

<div style="margin-bottom: 50px">
<h2>Registration</h2>

<p><b>Login</b></p>
<p><input name='login' type='text' size='60' value="{{ post.login }}" /></p>

<p><b>Password</b></p><p><input name='pass' type='password' /></p>

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
<input class="span1" type="text" name="quota_val" value="100">
<select class="span1" name="quota_unit">
	<option value="mb">Mb</option>
	<option value="gb">Gb</option>
</select>
</div>

</div>

<p style="margin-top: 20px"><input type="submit" name='adduser' value='OK' /></p>

</div>

</form>