<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
	<form action="{{ url('/upload/do_upload') }}" method="post">
        {{csrf_field()}}
        <input type="file" name="userfile" size="20" />
		<br /><br />
		<input type="submit" value="Upload" />
    </form>

</body>
</html>
