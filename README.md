<h2>PDF Merger</h2>

<p>Merge multiple image/pdf files into a single pdf.</p>
<h3>Command</h3>

<h4>Syntax:</h4>

`php bin/console app:merge-pdfs <inputPath> [<outputPath>]`

<h4>Sample commands:</h4>

1. `php bin/console app:merge-pdfs files`
Here `files` is the inputPath where all files reside which needs to be merged


2. `php bin/console app:merge-pdfs files outfile`
Here `outfile` is a directory where merged file will be generated