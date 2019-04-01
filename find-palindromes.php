<?php
/**
 * CLI tool for finding the palindomes in an [input_file] containing lines of possible palindromes
 * and writing them to an [output_file] in JSON.
 * 
 * @author Ben Goetzinger
 * @package Palindromer
 */

// Get Dependencies
require 'inc/palindromer.php';

// Because we all need a little help sometimes.
function usage() {
	echo 'USAGE:\tphp -f find-palindromes.php [input_file] [output_file]'.PHP_EOL;
	echo "\t input_file Optional. Defaults to 'possible_palindromes.txt'".PHP_EOL;
	echo "\t output_file Optional. Defaults to 'palindromes.json'".PHP_EOL;
	echo "\t\t***Relative Paths only please!".PHP_EOL;
	die();
}

// Open the input file.
$input_file = !empty($argv[1]) ? $argv[1] : 'possible_palindromes.txt';
if(!is_file($input_file)) {
	echo 'ERROR: Could not open input file '.$input_file.PHP_EOL;
	usage();
}

// Find the palindromes
echo 'Finding Palindromes...'.PHP_EOL;
if(!$results = Palindromer::search_file($input_file)) {
	echo "No palindromes found.".PHP_EOL;
	die();
}

// Output to file or puke.
$output_file = !empty($argv[2]) ? $argv[2] : 'palindromes.json';
if(!file_put_contents($output_file, json_encode($results))) {
	echo "\t------  ERROR: Could not write to file. Results follow: ------".PHP_EOL.var_export($results, true).PHP_EOL;
	usage();
} else {
	echo 'Complete. Results written to '.$output_file.PHP_EOL;
}

// Done!
die();