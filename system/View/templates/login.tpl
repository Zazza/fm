<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="fm" />
<meta name="keywords" content="fm" />
<title>OTMS</title>
<style>
@charset "utf-8";
/* CSS Document */
html, body {
    font-family: Verdana, sans-serif;
    font-size: 11px;
    font-style: normal;
    line-height: normal;
    font-weight: normal;
    font-variant: normal;
    text-align: center;
    background-color: #FFF;
    height: 100%;
    margin: 0;
    padding: 0;
    color: black;
}

input {
    border: 1px solid #CCCCCC;
    border-radius: 3px 3px 3px 3px;
    color: #808080;
    display: inline-block;
    font-size: 13px;
    line-height: 18px;
    padding: 4px;
}

button {
	height: 27px;
}

p {
	margin: 0;
	padding: 3px 0;
}

#dl {
	margin: 10px auto 0;
	width: 185px;
	color: black;
}
</style>
</head>
<body>

<div style="margin: 100px auto 0">

{% if err %}
<div style="color: red; background-color: white; padding: 2px 4px; width: 200px; margin: 0 auto">Неверный логин/пароль</div>
{% endif %}

</div>

<div id="dl">
<form action="{{ registry.uri }}" method="post">
<p style="font-weight: bold; text-align: left">Логин</p>
<p><input type="text" name="login" /></p>
<p style="font-weight: bold; text-align: left">Пароль</p>
<p><input type="password" name="password" /></p>
<p style="text-align: left"><input type="submit" name="submit" value="Войти" /></p>
</form>
</div>

</div>

</body>
</html>