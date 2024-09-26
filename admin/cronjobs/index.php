<?php
	$redirect = 'https://commissionercollege.com';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Commissioner College</title>

	<?php
		// $file = 'counter.txt';
		// // default the counter value to 1
		// $counter = 1;
		// // add the previous counter value if the file exists
		// if (file_exists($file)) {
		// 	$counter += file_get_contents($file);
		// }
		// // write the new counter value to the file
		// file_put_contents($file, $counter);
	?>

		<meta http-equiv="refresh" content="100; url=<?php echo $redirect; ?>" />
	</head>
	<body>
		<script>
			setTimeout(function(){
				window.location.href = '<?php echo $redirect; ?>';
			}, 500);
		</script>

			<a href="<?php echo $redirect; ?>"> PAGE HAS MOVED HERE [<?php $final = file_get_contents($file); echo $final; ?>]</a>
	</body>
</html>
