<?php

declare(strict_types = 1);

/*
 * AUTHOR : AVONTURE Christophe
 *
 * Written date : 12 december 2018
 *
 * Quick markdown to HTML converter
 * 1. Type your markdown code in the editor,
 * 2. click on Convert to get the HTML rendering,
 * 3. click on the Copy to clipboard button so you can paste the HTML rendering
 *      in an email f.i.
 *
 * Conversion is made in javascript, no server side action needed.
 */

define('REPO', 'https://github.com/cavo789/marknotes_md2html');

// A demo text
$md =
    "---\n" .
    "title: 'A title'\n" .
    "---\n\n" .
    "# Test\n\n" .
    "> This is a demo text\n\n" .
    "![banner](https://raw.githubusercontent.com/cavo789/marknotes_md2html/master/image/banner.jpg)\n\n" .
    "Lorem ipsum dolor ==sit amet==, consectetur *adipiscing* ==elit==.\n" .
    "[Demo site](https://www.avonture.be/marknotes_md2html/)\n\n" .
    "```sql\n" .
    "SELECT ... FROM ... WHERE ... ORDER BY ...\n" .
    "```\n\n" .
    "| Column 1 Header | Column 2 Header |\n" .
    "| --- | --- |\n" .
    "| Row 1-1 | Row 1-2 |\n" .
    "| Row 2-1 | Row 2-2 |\n\n" .
    "Demo of `html` source code:\n\n" .
    "```html\n" .
    "<div class=\"col-sm\">\n" .
    "   <title>Marknotes - MD2HTML - Quick convert markdown to HTML</title>\n" .
    "</div>\n" .
    "```\n\n" .
    "```php\n" .
    "<?php\n" .
    "   echo \$variable;\n" .
    "```\n";

// Get the GitHub corner
$github = '';
if (is_file($cat = __DIR__ . DIRECTORY_SEPARATOR . 'octocat.tmpl')) {
    $github = str_replace('%REPO%', REPO, file_get_contents($cat));
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="author" content="Christophe Avonture" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />
        <title>Marknotes - MD2HTML - Quick convert markdown to HTML</title>
        <!-- Optional, use Prism https://github.com/PrismJS/prism -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.13.0/themes/prism.min.css" rel="stylesheet" type="text/css">
        <!-- Optional, use GitHub markdown style; comes from https://github.com/sindresorhus/github-markdown-css -->
        <link href="vendor/github-markdown.css" rel="stylesheet" type="text/css">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <style>
            textarea {
                margin-top:10px;
                font-family:Consolas,Monaco,Lucida Console,Liberation Mono,DejaVu Sans Mono,Bitstream Vera Sans Mono,Courier New, monospace;
            }
            details {
                margin: 1rem;
            }
            summary {
                font-weight: bold;
            }
            .form-control {
                padding: none;
                font-size: 0.8em;
            }
            .buttons {
                padding:10px;
            }
            .markdown-body {
                box-sizing: border-box;
                margin: 0 auto;
                max-height: 80%;
            }
            .highlight {
                background-color: yellow;
            }
            @media (max-width: 767px) {
                .markdown-body {
                    padding: 15px;
                }
            }
            html {
                position: relative;
                min-height: 80%;
            }            
            body {
                /* Margin bottom by footer height */
                margin-bottom: 60px;
            }
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                height: -20px;
            }
        </style>
    </head>
    <body>
        <?php echo $github; ?>
        <header class="page-header">
            <h1>Marknotes - MD2HTML</h1>
        </header>
        <main role="main" class="container-fluid">
            <div class="container-fluid">
                <details>
                    <summary>How to use?</summary>
                    <div class="row">
                        <div class="col-sm">
                            <ol>
                                <li>Type the markdown code in the text area here below</li>
                                <li>Click on the Convert button to see the HTML rendering 
                                    at the right side</li>
                                <li>If looks fine, click on the Copy to clipboard button 
                                    so you can f.i. paste the rendering in an email</li>
                            </ol>
                            <h2>Tips</h2>
                            <ul>
                                <li>Would you like to draw attention to some of your content? 
                                    Add an equal double before and after like in 
                                    <pre>==Please read this==</pre></li>
                            </ul>
                        </div>
                        <div class="col-sm">
                            <img height="300px" src="https://raw.githubusercontent.com/cavo789/marknotes_md2html/master/image/demo.gif" alt="Demo">
                        </div>
                    </div>
                </details>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm">
                        <textarea class="form-control" rows="20" id="editor" name="editor"><?php echo $md;?></textarea>
                    </div>
                    <div class="col-sm">
                        <article id="HTML" class="d-none markdown-body"><article>
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer">
            <div class="buttons container-fluid">
                <button type="button" id="btnConvert" class="btn btn-primary">
                    Convert
                </button> 
                <button disabled="disabled" class="btnClipboard d-none btn btn-success" data-clipboard-target="#HTML">
                    Copy to clipboard
                </button>
            </div>
        </footer>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous" type="text/javascript"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="https://unpkg.com/marked@0.3.6" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.13.0/prism.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.13.0/components/prism-php.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                if (typeof ClipboardJS === 'function') {

                    $('.btnClipboard').removeAttr("disabled");

                    var clipboard = new ClipboardJS('.btnClipboard');
                    
                    clipboard.on('success', function(e) {
                        alert('HTML rendering copied!');
                        e.clearSelection();
                    });
                }

                function convertMD2HTML() {

                    var $markdown = $('#editor').val();

                    // Replace the YAML block that can be at the top of the file, f.i.
                    //      ---
                    //      title: 'a title'
                    //      date: 2018-12-25
                    //      ---
                    // Just remove it
                    try {
                        $markdown = $markdown.replace(/\s*^-{3}[.\S\s]*^-{3}/mg, "");    
                    } catch (error) {
                    }

                    var $HTML = marked($markdown, { 
                        sanitize: true,
                        gfm: true, 
                        smartypants: true, 
                        tables: true 
                    });

                    // Handle a few custom tags
                    //   ==WORD==       will put this word in a highlighted span
                    $HTML = $HTML.replace(/([^=]*)==([^=]*)==([^=]*)/g, 
                        "$1<span class='highlight'>$2</span>$3");

                    // Render the markdown table to HTML
                    $("#HTML").html($HTML);

                    // Add bootstrap classes
                    $("#HTML table").addClass("table table-hover table-striped");

                    if (typeof Prism === 'object') {
                        // Use prism.js and highlight source code
                        Prism.highlightAll();
                    }

                    // Finally show the copy clipboard button and the HTML result
                    $('.btnClipboard').removeClass('d-none');
                    $('#HTML').removeClass('d-none');

                    return;
                }

                $("#editor").on('change keyup paste', function(e) {                    
                    e.stopImmediatePropagation();
                    convertMD2HTML();
                });
                
                $('#btnConvert').click(function(e)  {
                    e.stopImmediatePropagation();
                    convertMD2HTML();
                });
            });
        </script>
    </body>
</html>
