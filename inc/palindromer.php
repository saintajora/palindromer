<?php
/**
 * Methods for fiding palindromes. Organized here for my sanity.
 * 
 * @author Ben Goetzinger
 * @package Panindromer
 */
class Palindromer {
	
	/**
	 * Search a file for palindromes.
	 * 
	 * @param Resource $input_file
	 * @return Array on success or false on failure
	 */
	public static function search_file($input_file) {
		$outdat = [];
		$lens = [];
		
		$inf = fopen($input_file, 'r');
		while($line = rtrim(fgets($inf), PHP_EOL)) {
			if(!$pdromes = static::search_line($line)) continue;
			$outdat[] = $pdromes;
			$lens[] = $pdromes['num_chars'];
		}
		fclose($inf);
		
		array_multisort($lens, SORT_DESC, $outdat);
		return !empty($outdat) ? $outdat : false;
	}
	
	/**
	 * Search a single line for palindromes.
	 * 
	 * @param String $line
	 * @return Array on success or false on failure.
	 */
	protected static function search_line($line) {
		$stripped = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $line));
		$matched = preg_match_all('/(?:(.)(?=.*(\1(?(2)\2|))))*.?\2/', $stripped, $pdromes);
		if(!$matched || $matched == 0) return false;
		
		$num_chars = 0;
		$all_pdromes = [];
		foreach($pdromes[0] as $p) {
			$len = strlen($p);
			$num_chars += $len;
			$all_pdromes[] = $p;
			
			for($i = floor($len/2)-1; $i>=0; $i--) {
				$matched = preg_match_all('/((.)(?=.*(\2(?(3)\3|)))){'.$i.'}(.?)\3/', $p, $subdromes);
				if(!$matched || $matched == 0) continue;
				
				foreach($subdromes[0] as $s) {
					$all_pdromes[] = $s;
					$num_chars += strlen($s);
				}
			}
		}
		
		array_multisort(array_map('strlen', $all_pdromes), SORT_DESC, $all_pdromes);
		return [
				'line'=>$line,
				'num_chars'=>$num_chars,
				'palindromes'=>$all_pdromes
		];
	}
}