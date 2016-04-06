<?php

	$word = $_GET['word'];
	$html = file_get_contents("http://www.archives.nd.edu/cgi-bin/wordz.pl?keyword=" . $word);
	if(strpos($html, "UNKNOWN") == false){
		$html = substr($html, strpos($html, "<pre>") + strlen("<pre>"));
		$html = substr($html, 0, strpos($html, "</pre>"));
		$html = preg_replace('/\n/', '', $html, 1); //delete the first line break only
		$html = nl2br($html);

		/*$definition = substr($html, strpos($html, "]") +1);
		$definition = substr($definition, 0, strpos($definition, ";"));
		$definition = strip_tags($definition);*/
		$html = '<div class="result"><div class="top">' . $word . '</div><div class="description">' . $html . '</div></div>'; 
		echo $html;
	}else{
		echo '<div class="result"><div class="top">' . $word . '</div><div class="description">unknown word</div></div>';
	}
?>