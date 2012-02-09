<form method="post" action="{{ registry.uri }}users/adduser/">

{% if err %}
{% for part in err %}
<p style="color: red">{{ part }}</p>
{% endfor %}
{% endif %}

<div style="margin-bottom: 50px">
<h2>Регистрация нового пользователя</h2>
<p><b>Логин</b></p>
<p><input name='login' type='text' size='60' value="{{ post.login }}" /></p>

<div style="border: 1px solid #CECECE; padding: 4px 8px; margin: 20px 0; width: 500px">
<p style="margin: 7px 0">
    <label><input name='priv' value="admin" type='radio' {% if post.priv == "admin" %} checked {% endif %} />&nbsp;<b>Администратор</b></label>
    <label><input name='priv' value="null" type='radio' {% if post.priv == FALSE  %} checked {% endif %} />&nbsp;<b>Обычный пользователь</b></label>
</p>

<p style="margin: 7px 0"><b>Группа</b>&nbsp;
<select name="gid">
{% for part in group %}
<option value="{{ part.sid }}" {% if post.gid == part.sid %} selected="selected" {% endif %}>{{ part.sname }}</option>
{% endfor %}
</select>
</p>
</div>

<p><b>Пароль</b></p><p><input name='pass' type='password' /></p>
<p style="margin-top: 20px"><input type="submit" name='adduser' value='Готово' /></p>
</div>

</form>