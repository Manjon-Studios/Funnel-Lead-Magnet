import '../css/main.css'
import Alpine from 'alpinejs'
import flmOptin from './flm-optin';

window.Alpine = Alpine;
Alpine.data('flmOptin', (el) => flmOptin(el));
Alpine.start();