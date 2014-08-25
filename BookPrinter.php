<?php

/**
 * Generates documentation for QuickAppsCMS.
 *
 * YOU MUST install [pandoc](http://johnmacfarlane.net/pandoc/) to run this class.
 *
 * **Usage:**
 *
 *    BookPrinter::pdf('/full/path/to/en/');
 *
 * This will create PDF documentation for English language.
 */
class BookPrinter {

/**
 * Pandoc command.
 * 
 * @var string
 */
	protected static $_pandocBin = 'pandoc';

/**
 * Sets the location of the pandoc binary.
 *
 * This is used by Windows environments, you should indicate a full path to
 * "pandoc.exe".
 * 
 * @param string $binPath Full path to pandoc.exe
 * @return void
 */
	public static function pandoc($binPath) {
		static::$_pandocBin = $binPath;
	}

/**
 * Creates documentation in PDF format.
 * 
 * @param string $dir Directory containing documentation
 * @param string $pdfName Name of the resulting PDF file, defaults to "book.pdf",
 *  it must end with ".pdf"
 * @return void
 */
	public static function pdf($dir, $pdfName = 'book.pdf') {
		if (strpos($pdfName, '.pdf') === false) {
			die('Invalid PDF name, must end with ".pdf"!');
		}

		static::_mergeMd($dir);
		$target = 'merged.md';
		$cmd = static::$_pandocBin . ' "' . $target . '" -o "' . $pdfName . '" --toc --chapters --latex-engine=xelatex -V geometry="top=1.5cm, bottom=1.5cm, right=1.5cm, left=2.5cm, footskip=0.7cm" -V fontsize="12pt"';
		exec($cmd);
		@unlink('merged.md');
	}

/**
 * Creates documentation in HTML format.
 * 
 * @param string $dir Directory containing documentation
 * @param string $htmlName Name of the resulting PDF file, defaults to "book.html",
 *  it must end with ".html"
 * @return void
 */
	public static function html($dir, $htmlName = 'book.html') {
		if (strpos($htmlName, '.html') === false) {
			die('Invalid HTML name, must end with ".html"!');
		}

		static::_mergeMd($dir);
		$target = 'merged.md';
		$cmd = static::$_pandocBin . ' "' . $target . '" -o "' . $htmlName . '"';
		exec($cmd);
		@unlink('merged.md');
	}

/**
 * Creates documentation in RST format.
 * 
 * @param string $dir Directory containing documentation
 * @param string $rstName Name of the resulting PDF file, defaults to "book.rst",
 *  it must end with ".rst"
 * @return void
 */
	public static function rst($dir, $rstName = 'book.rst') {
		if (strpos($rstName, '.rst') === false) {
			die('Invalid RST name, must end with ".rst"!');
		}

		static::_mergeMd($dir);
		$target = 'merged.md';
		$cmd = static::$_pandocBin . ' "' . $target . '" -o "' . $rstName . '"';
		exec($cmd);
		@unlink('merged.md');
	}

/**
 * Creates documentation in LATEX format.
 * 
 * @param string $dir Directory containing documentation
 * @param string $texName Name of the resulting LATEX file, defaults to "book.tex",
 *  it must end with ".tex"
 * @return void
 */
	public static function latex($dir, $texName = 'book.tex') {
		if (strpos($texName, '.tex') === false) {
			die('Invalid TEX name, must end with ".tex"!');
		}

		static::_mergeMd($dir);
		$target = 'merged.md';
		$cmd = static::$_pandocBin . ' "' . $target . '" -o "' . $texName . '"';
		exec($cmd);
		@unlink('merged.md');
	}

/**
 * Starts the merging process.
 * 
 * @param string $dir Directory containing documentation
 * @return void
 */
	protected static function _mergeMd($dir) {
		if (file_exists('merged.md')) {
			@unlink('merged.md');
		}

		static::_processDir($dir);
	}

/**
 * Merges all .md files into "merged.md".
 *
 * Look for markdown files in the given directory and merges them together
 * into one unique markdown, this merged markdown will be converted using pandoc.
 * 
 * @param string $dir Full path to directory containing markdown files
 * @return void
 */
	protected static function _processDir($dir) {
		static $headers = [];

		list($folders, $files) = static::_readDir($dir);
		$dirname = basename($dir);
		$sectionName = str_replace('_', ' ', $dirname);
		$sectionName = preg_replace('/^\d{2}\s/', '', $sectionName);
		$sectionHeader = !in_array($sectionName, ['en', 'es']) ? "{$sectionName}\n" . str_repeat('=', strlen($sectionName)) . "\n\n\n" : '';

		foreach ($files as $file) {
			$fileName = preg_replace('/\.md$/', '', basename($file));

			if (!in_array($sectionHeader, $headers)) {
				$headers[] = $sectionHeader;
			} else {
				$sectionHeader = '';
			}

			file_put_contents('merged.md',
				$sectionHeader .
				file_get_contents($file) .
				"\n\n---\n\n",
				FILE_APPEND
			);
		}

		foreach ($folders as $folder) {
			static::_processDir($folder);
		}
	}

/**
 * Returns a list of files and folders within the given path.
 * 
 * @param string $dir Full path to directory to read
 * @return array List of files and folders
 */
	protected static function _readDir($dir) {
		$result = [
			0 => [], // folders
			1 => [], // files
		];

		foreach (scandir($dir) as $file) {
			if (strpos($file, '.') === 0) {
				continue;
			}

			$fullPath = $dir . DIRECTORY_SEPARATOR . $file;
			if (is_dir($fullPath)) {
				$result[0][] = $fullPath;
			} else {
				$result[1][] = $fullPath;
			}
		}

		return $result;
	}

}
