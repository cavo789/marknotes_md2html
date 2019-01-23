import Vue from 'vue'
import App from './App.vue'
import 'bulma/css/bulma.css';

new Vue({
    el: '#app',
    render: h => h(App, {
        props: {
            app_title: 'Marknotes - MD2HTML',
            app_subtitle: 'Quick markdown to HTML converter',
            github_url: 'https://github.com/cavo789/marknotes_md2html',
            howto_title: 'How to use?',
            howto_imgsrc: 'https://raw.githubusercontent.com/cavo789/marknotes_md2html/master/image/demo.gif',
        }
    }),
});
