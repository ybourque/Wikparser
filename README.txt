Wikparser
=========

Wiktionary Parser
http://www.igrec.ca/projects/wiktionary-text-parser/


DESCRIPTION
===========
The Wiktionary Parser (or Wikparser) is a small tool written in PHP that allows users to extract specific information from the Wiktionary API or a local copy of Wiktionary's database in MySQL.

Currently, this software is able to extract the following information:
- Part of speech/Lexical categories
- Synonyms
- Hypernyms
- Definitions

Moreover, the software currently supports queries to either the English or French Wiktionary. Additional language support may be added by following the guide at http://www.igrec.ca/projects/wiktionary-text-parser/

REQUIREMENTS
============
You will need:
- Apache or some other web server platform
- PHP 5
- cURL

INSTALLATION
============
Simply download and copy files and folders to a location accessible from your Web server.

USAGE
=====
To use Wikparser, you need to call or point your browser to the wikparser.php file with the following parameters and values (* indicates a mandatory parameter):

- *word: any string (e.g. /wikparser.php?word=dog)
- *query: the type of query; "def" for definitions, "syn" for synonyms, "pos" for parts of speech, and "hyper" for hypernyms (e.g. /wikparser.php?query=pos)
- lang: Wiktionary language code. Script currently supports english ("en") and french ("fr") natively [default: en].
- count: number of items to return [default: 100]
- source: location of Wiktionary data; "local" for a local MySql copy of Wiktionary; "api" for Wiktionary’s API [default: api]

EXAMPLES
========
The examples below use the Wikparser hosted at www.igrec.ca.

- Get first 2 definitions of the word "table" in English directly from Wiktionary:
http://www.igrec.ca/project-files/wikparser/wikparser.php?word=table&query=def&count=2

- Get all parts of speech for the word "puissance" in French directly from Wiktionary:
http://www.igrec.ca/project-files/wikparser/wikparser.php?word=puissance&query=pos&lang=fr
