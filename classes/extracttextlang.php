<?php

$bln = false;

if ($this->langseparator !== "") {
	$languages = explode($this->langseparator, $wikitext);

	foreach ($languages as $value) {
		if (strpos($value, $this->langheader) !== false) {
			$this->wikitext = $value;
			$bln = true;
		}
	}
	if ($bln !== true) {
		die("No such word for specified language.");
	}
}

else {
	$this->wikitext = $wikitext;
}

?>