<?php
header( 'Content-Type: text/html; charset=utf8' );
?><!DOCTYPE html>
<html lang="en">
<head>
	<title>IF Name Generator</title>
	<meta charset="UTF-8">
	<meta description="IF Name Generator mashes two existing Interactive Fiction titles together to create a new one.">
	<meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

<?php
if( $error ) {
    die( $error.'</body></html>' );
}
?>

<div class="container">
<p>
Below is a<?php if( $n > 1 ) {
    echo " list of " . $n;
} ?> randomized IF
title<?php if( $n > 1 ) { echo 's'; } ?> created by
joining the beginning and end parts of random existing titles.
</p>

<p>
Reload the page to get a new <?php if( $n > 1 ) { echo 'set of titles'; } else { echo 'title'; } ?>.
</p>

<form action="" method="get" id="ifcomp-form" class="form-inline">
<p class="form-group">
Try the <a href="http://www.ifcomp.org/">IFComp</a> editions:
<select name="ifcomp" id="ifcomp-selection" class="form-control">
<option></option>
<?php
$years = array_keys( $comps );
foreach( $years as $year ) {
?>
    <option value="<?php echo $year; ?>"<?php if( $compEdition == $year ) { echo ' selected'; } ?>><?php echo $year; ?></option>
<?php
}
?>
<option value="all"<?php if( $compEdition == 'all' ) { echo ' selected'; } ?>>All IFComps</option>
</select>
<button type="submit" class="btn btn-primary" id="ifcomp-go">Go</button>
</p>
</form>
<script>
    document.getElementById( 'ifcomp-go' ).style.display = 'none';
    document.getElementById( 'ifcomp-selection' ).addEventListener( 'change', function() {
        document.getElementById( 'ifcomp-form' ).submit();
    }, false );
</script>
<hr />

<ul id="list">
<?php
for( $i = 0; $i < count( $generatedNames ); ++$i ) {
    echo '<li>';
    echo $generatedNames[ $i ];
	echo "</li>\n";
}
?>
</ul>

</div>

<footer class="text-right small container">
<a href="https://github.com/juhana/name-generator">Source & API</a>
</footer>
</body>
</html>