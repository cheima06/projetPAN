import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

require('bootstrap');

// loads the jquery package from node_modules
import $ from 'jquery';

// Importation des styles Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// Optionnel : Importation des scripts Font Awesome (si vous avez besoin des fonctionnalités JavaScript de Font Awesome)
import '@fortawesome/fontawesome-free/js/all.min.js';

$(document).ready(function () {}
);

