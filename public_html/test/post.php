<?

if($_POST['submit'])
{
	print_r($_POST);
	echo "<br>";
}

?>

<form action="post.php" method="post">
	<textarea name="aaa" cols="50" rows="10"></textarea>
	<input type="submit" name="submit" value="submit">
</form>