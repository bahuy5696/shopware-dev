import Plugin from 'src/plugin-system/plugin.class';

export default class ExamplePlugin extends Plugin {
    static options = {
        text: 'Seems like there\'s nothing more',
    }

    init() {
        window.addEventListener('scroll', this.onScroll.bind(this));
    }

    onScroll() {
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
            alert(
                this.options.text
            );
        }
    }
}
