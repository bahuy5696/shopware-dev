import ExamplePlugin from './example-plugin/example-plugin.plugin';
import LoginPlugin from './login-plugin/login-plugin.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('ExamplePlugin', ExamplePlugin, '[data-scroll-detector-plugin]');
PluginManager.register('LoginPlugin', LoginPlugin);
