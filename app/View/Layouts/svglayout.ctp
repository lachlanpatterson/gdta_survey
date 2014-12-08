<?php
echo('<?xml version="1.0" encoding="utf-8"?>');
echo('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">');
echo($this->Html->script('svg'));
?>
<style>
body {
	background-color: #FFFFFF;
	width: 100%;
	height: 100%;
	font-family: 'lucida grande', verdana, helvetica, arial, sans-serif;
	text-align: center;
	word-wrap: break-word;
}

button,input[type=submit] {
	font-size: 90%;
	padding: 2px;
	margin: 8px;
	width: 200px;
}
</style>
<head>
<title>GDTA Survey Results</title>
</head>
<body>
	<?php
	echo($content_for_layout);
	?>
</body>
