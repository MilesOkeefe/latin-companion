<?php
	function setFullPoS($html, $pos){
		$short_parts = array('N', 'PRON', 'ADV', 'ADJ', 'V', 'VPAR', 'SUPINE', 'PREP', 'CONJ', 'INTERJ', 'NUM');
		$long_parts = array('Noun', 'Pronoun', 'Adverb', 'Adjective', 'Verb', 'Participle Verb', 'Supine', 'Preposition', 'Conjunction', 'Interjection', 'Number');
		$index = array_search($pos, $short_parts);
		if($index != -1){
			$long = $long_parts[$index];
			return str_replace(" $pos ", " $long ", $html);
		}else{
			return $html;
		}
	}
	function setFullGrammar($html){
		$html = str_replace(" FUT ", " Future ", $html);
		$html = str_replace(" IND ", " Indicative ", $html);
		$html = str_replace(" SUB ", " Subjunctive ", $html);
		$html = str_replace(" NOM ", " <span class='highlight'>Nominative</span> ", $html);
		$html = str_replace(" ABL ", " <span class='highlight'>Ablative</span> ", $html);
		$html = str_replace(" PASSIVE ", " Passive ", $html);
		$html = str_replace(" PERF ", " <span class='highlight'>Perfect</span> ", $html);
		$html = str_replace(" S ", " Singular ", $html);
		//$html = str_replace(" PPL ", "  ", $html);
		$html = str_replace(" M ", " Masculine ", $html);
		$html = str_replace(" F ", " Feminine ", $html);
		$html = str_replace(" N ", " Neuter ", $html);
		$html = str_replace(" VOC ", " Vocative ", $html);
		$html = str_replace(" GEN ", " Genative ", $html);
		$html = str_replace(" DAT ", " <span class='highlight'>Dative</span> ", $html);
		$html = str_replace(" LOC ", " Locative ", $html);
		$html = str_replace(" ACC ", " <span class='highlight'>Accusative</span> ", $html);
		$html = str_replace(" PRES ", " Present ", $html);
		$html = str_replace(" ACTIVE ", " Active ", $html);
		$html = str_replace(" POS ", " Positive ", $html);
		$html = str_replace(" FUTP ", " Future Perfect ", $html);
		$html = str_replace(" PLUP ", " <span class='highlight'>Pluperfect</span> ", $html);
		$html = str_replace(" IMPF ", " <span class='highlight'>Imperfect</span> ", $html);
		return $html;
		//$html = str_replace("  ", "  ", $html);
	}
	function removeDottedWord($html){
		$matches;
		preg_match('/(?P<theword>(\w*)((\.)(\w+))*)(\W+)(\w+)(\W+)/', $html, $matches);
		$word = $matches['theword'];
		if(strlen($word) > 2){
			return str_replace($word, '', $html);
		}else{
			return $html;
		}
	}
	function getPartOfSpeech($data){
		//$parts = array('N', 'PRON', 'ADV', 'ADJ', 'V', 'VPAR', 'SUPINE', 'PREP', 'CONJ', 'INTERJ', 'NUM');
		$matches;
		preg_match('/(\w*)((\.)(\w+))*(\W+)(?P<pos>\w+)(\W+)/', $data, $matches);
		return $matches['pos'];
	}
	$word = $_GET['word'];
	$html = "";
	$html = @file_get_contents("http://www.archives.nd.edu/cgi-bin/wordz.pl?keyword=" . $word);
	if(strlen($html) > 0){
		if(strpos($html, "UNKNOWN") == false){
			$html = substr($html, strpos($html, "<pre>") + strlen("<pre>"));
			$html = substr($html, 0, strpos($html, "</pre>"));
			$html = preg_replace('/\n/', '', $html, 1); //delete the first line break only
			$html = preg_replace('/\[\w+\]/', '', $html);
			$html = trim($html);
			$html = nl2br($html);
			$pos = getPartOfSpeech($html);
			$html = removeDottedWord($html);
			$html = setFullPoS($html, $pos);
			$html = setFullGrammar($html, $pos);
			$pos = strtolower($pos);
			/*$definition = substr($html, strpos($html, "]") +1);
			$definition = substr($definition, 0, strpos($definition, ";"));
			$definition = strip_tags($definition);*/
			
			$html = '<div class="result"><div class="top ' . $pos . '">' . $word . '</div><div class="description ' . $pos . '-d">' . $html . '</div></div>'; 
			echo $html;
		}else{
			echo '<div class="result"><div class="top x">' . $word . '</div><div class="description x-d">unknown word</div></div>';
		}
	}else{
		echo '<div class="result"><div class="top x">' . $word . '</div><div class="description x-d">Error: Couldn\'t get word info, try again.</div></div>';
	}
?>