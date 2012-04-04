<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" type="image/gif" href="{{ registry.uri }}favicon.png" />
<meta name="description" content="Ostora filemanager" />
<meta name="keywords" content="Ostora filemanager" />
<title>Ostora filemanager</title>
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

.p {
	overflow: hidden;
	height: 30px;
}

.pn {
    float: left;
    font-size: 14px;
    margin-top: 2px;
    text-align: left;
    width: 80px;
}

#dl {
    border: 1px solid #DDD;
    border-radius: 5px 5px 5px 5px;
    margin: 10px auto 0;
    padding: 10px 40px;
    display: table;
}
.legend {
    background-color: #FFF;
    color: #73B304;
    font: 700 20px Arial,Helvetica,sans-serif;
    padding: 3px 20px;
    position: relative;
    top: -24px;
}
</style>
</head>
<body>

<div style="margin: 100px auto 0">

<div><img alt="logo" src="{{ registry.uri }}img/ostora_logo.png" style="margin-bottom: 20px" /></div>

{% if err %}
<div style="color: red; background-color: white; padding: 10px 4px; width: 200px; margin: 0 auto">Access denied!</div>
{% endif %}

<div id="dl">
<span class="legend">Authorization</span>
<form action="{{ registry.uri }}" method="post">
<div class="p">
	<div class="pn">Login</div>
	<div style="float: left"><input type="text" name="login" /></div>
</div>
<div class="p">
	<div class="pn">Password</div>
	<div style="float: left"><input type="password" name="password" /></div>
</div>
<div class="p" style="text-align: right; margin-top: 10px"><input type="submit" name="submit" value="OK" /></div>
</form>
</div>

</div>

</body>
</html>
