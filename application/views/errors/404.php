<!DOCTYPE html>
<html>
<head>
	<title>Ошибка 404 | MPEICentral.ru</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<h1>404</h1>
<?php if (!empty($message))
{
	printf('<p>%s</p>',$message);
}
else
{
	printf('<p>Страница не найдена</p>');
} ?>
</body>
</html>