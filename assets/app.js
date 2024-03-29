import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

import $ from 'jquery'; // Importez jQuery


$(document).ready(function() {
    $('.nav-item.dropdown').hover(function() {
      $(this).addClass('show');
      $(this).find('.dropdown-menu').addClass('show');
    }, function() {
      $(this).removeClass('show');
      $(this).find('.dropdown-menu').removeClass('show');
    });
  });
