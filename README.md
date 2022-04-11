<h2>PDF Merger</h2>

<p>Merge multiple image/pdf files into a single pdf.</p>
<h3>Command</h3>

<h4>Syntax:</h4>

`php bin/console app:merge-pdfs <input_dir_name> [<output_filename>]`

<h4>Sample commands:</h4>

1. `php bin/console app:merge-pdf files`
Here `files` is the input directory where all the files reside those need to be merged


2. `php bin/console app:merge-pdf files outfile`
Here `outfile` is the file name with datetime suffix that will be generated as output