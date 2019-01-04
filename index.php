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
 *
 * Last mod:
 * 2018-12-31 - Abandonment of jQuery and migration to vue.js
 *                  Except for clipboard.min.js which requires jQuery
 */

define('REPO', 'https://github.com/cavo789/marknotes_md2html');

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
            #HTML_CODE {
                position: fixed;
                top: -100px;
            }
        </style>
    </head>
    <body>
        <?php echo $github; ?>
        <header class="page-header">
            <h1>Marknotes - MD2HTML</h1>
        </header>
        <div id="app">
            <main role="main" class="container-fluid">
                <div class="container-fluid">
                    <how-to-use demo="https://raw.githubusercontent.com/cavo789/marknotes_md2html/master/image/demo.gif">                    
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
                    </how-to-use>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm" v-if="showEditor">
                            <textarea class="form-control" rows="20" v-model="Markdown"></textarea>
                        </div>
                        <div class="col-sm">
                            <article id="HTML" v-html="HTML" class="markdown-body"><article>
                        </div>
                    </div>
                </div>
                <!-- Textarea required for the Copy into clipboard so we can get the HTML code-->
                <textarea id="HTML_CODE">{{ HTML }}</textarea>
            </main>
            <footer class="footer">
                <div class="buttons container-fluid">
                    <button v-if="HTML" title="Show/Hide the editor"  
                        class="btnToggle btn btn-success" @click="doToggle">
                        Toggle editor
                    </button>
                    <button v-if="HTML"  title="Copy the rendered HTML in the clipboard"  
                        class="btnClipboard btn btn-success" data-clipboard-target="#HTML">
                        Copy
                    </button>
                    <button v-if="HTML" title="Copy the HTML source code (with tags) in the clipboard" 
                        class="btnClipboard btn btn-success" data-clipboard-target="#HTML_CODE">
                        Copy source
                    </button>
                </div>
            </footer>
        </div>
        <script src="https://unpkg.com/vue"></script>
        <script src="https://unpkg.com/marked@0.3.6" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.13.0/prism.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.13.0/components/prism-php.js"></script>

        <!-- Clipboard requires jQuery -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

        <script type="text/javascript">

            $(document).ready(function() {
                // jQuery part, handle the Copy in the clipboard feature
                if (typeof ClipboardJS === 'function') {
                    $('.btnClipboard').removeAttr("disabled");
                    var clipboard = new ClipboardJS('.btnClipboard');

                    clipboard.on('success', function(e) {
                        alert('HTML rendering copied!');
                        e.clearSelection();
                    });
                }
            });

            Vue.component('how-to-use', {
                props: {
                    demo: {
                        type: String,
                        required: true
                    }
                },
                template:
                    `<details>
                        <summary>How to use?</summary>
                        <div class="row">
                            <div class="col-sm">
                                <slot></slot>
                            </div>
                            <div class="col-sm"><img v-bind:src="demo" alt="Demo"></div>
                        </div>
                    </details>`
            });

            var app = new Vue({
                el: '#app',
                data: {
                    Markdown: "---\n" +
                        "title: 'A title'\n" +
                        "---\n\n" +
                        "# Test\n\n" +
                        "> This is a demo text\n\n" +
                        "![banner](https://raw.githubusercontent.com/cavo789/marknotes_md2html/master/image/banner.jpg)\n\n" +
                        "Lorem ipsum dolor ==sit amet==, consectetur *adipiscing* ==elit==.\n" +
                        "[Demo site](https://www.avonture.be/marknotes_md2html/)\n\n" +
                        "```sql\n" +
                        "SELECT ... FROM ... WHERE ... ORDER BY ...\n" +
                        "```\n\n" +
                        "| Column 1 Header | Column 2 Header |\n" +
                        "| --- | --- |\n" +
                        "| Row 1-1 | Row 1-2 |\n" +
                        "| Row 2-1 | Row 2-2 |\n\n" +
                        "Demo of `html` source code:\n\n" +
                        "```html\n" +
                        "<div class=\"col-sm\">\n" +
                        "   <title>Marknotes - MD2HTML - Quick convert markdown to HTML</title>\n" +
                        "</div>\n" +
                        "```\n\n" +
                        "```php\n" +
                        "<\?php\n" +
                        "   echo \$variable;\n" +
                        "```\n",
                    showEditor: true
                },
                methods: {
                    doToggle(event) {
                        this.showEditor = !(this.showEditor);
                    }
                },
                computed: {
                    HTML() {
                        if (this.Markdown == '') {
                            return '';
                        }

                        var $markdown = this.Markdown;

                        // Replace the YAML block that can be at the top of the file, f.i.
                        //      ---
                        //      title: 'a title'
                        //      date: 2018-12-25
                        //      ---
                        // Just remove it
                        try {
                            $markdown = $markdown.replace(/\s*^-{3}[.\S\s]*^-{3}/mg, "");
                            $markdown = $markdown.trim();
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

                        // Add bootstrap classes
                        return $HTML.replace('<table>', '<table class="table table-hover table-striped">');
                    }
                }
            });
        </script>
    </body>
</html>
