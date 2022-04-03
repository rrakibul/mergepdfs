<h2>PDF Merger</h2>

<p>Merge multiple PDF files into a single one.</p>
<h3>Command</h3>

<h4>Syntax:</h4>

`php bin/console app:merge-pdfs <inputFilePath> [<outputFilePath>]`

<h4>Sample commands:</h4>

1. `php bin/console app:merge-pdfs files`
Here `files` is the inputFilePath where all files reside which needs to be merged


2. `php bin/console app:merge-pdfs files outfile`
Here `outfile` is a directory where merged file will be generated