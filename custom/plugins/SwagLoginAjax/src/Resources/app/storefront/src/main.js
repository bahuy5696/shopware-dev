import ExamplePlugin from './example-plugin/example-plugin.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('ExamplePlugin', ExamplePlugin, '[data-scroll-detector-plugin]');

