// Marked for conversion from MD to HTML
import marked from 'marked/marked.min.js';

// Copy in clipboard
import ClipboardJS from 'clipboard';

// Import babel-plugin-prismjs
// The list of supported languages can be retrieved in the
// /.babelrc file (in the root folder)
import Prism from 'prismjs';

// Primer-css is the new name for github-markdown
import "primer-css/build/build.css";

export default {
    name: "editor",
    props: {
        showEditor: {
            type: Boolean,
            required: false,
            default: true
        },
        MARKDOWN: {
            type: String,
            required: false,
            default: "---\n" +
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
                "```\n"
        }
    },
    data: function () {
        return {
            markdown: this.MARKDOWN,
            showeditor: this.showEditor,
            clipboardDisabled: 1
        }
    },
    computed: {
        HTML() {
            if (this.markdown == '') {
                return '';
            }

            var $markdown = this.markdown;

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
            //   ==WORD==    will put this word in a highlighted span
            $HTML = $HTML.replace(/([^=]*)==([^=]*)==([^=]*)/g,
                "$1<span class='highlight'>$2</span>$3");

            // Add classes to tables
            $HTML = $HTML.replace('<table>', '<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">');

            // Strange: need a timeout to give "time" to Prism to
            // highlight the HTML content
            setTimeout(() => {
                Prism.highlightAll();
            }, 10);

            return $HTML;
        }
    },
    methods: {
        doToggle(e) {
            this.showeditor = !(this.showeditor);
            this.$emit('toggleVisibility', e);
        }
    },
    watch: {
        HTML: function (val) {
            // Call PrismJS if HTML has changed
            Prism.highlightAll();
        }
    },
    render() {
        alert('rendering');
    },
    mounted() {
        // If ClipboardJS library is correctly loaded,
        if (typeof ClipboardJS === 'function') {
            // Remove the disabled attribute
            this.clipboardDisabled = 0;

            // Handle the click event on buttons
            var clipboard = new ClipboardJS('.btnClipboard');

            clipboard.on('success', function (e) {
                alert('Copied!');
                e.clearSelection();
            });
        }
    }
};
