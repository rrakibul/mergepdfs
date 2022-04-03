<h2>PDF Merger</h2>
<hr>
<p>Merge multiple PDF files into a single one.</p>
<h3>Command</h3>
<hr>
Syntax:

`php bin/console app:merge-pdfs <inputFilePath> [<outputFilePath>]`

Sample commands:

1. `php bin/console app:merge-pdfs files`
Here `files` is the inputFilePath where all files reside which needs to be merged


2. `php bin/console app:merge-pdfs files outfile`
Here `outfile` is a directory where merged file will be generated