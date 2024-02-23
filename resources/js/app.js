require('./bootstrap');
import $ from 'jquery';
import {ajaxSetup} from './util/util';
import setUpPosEvents from './admin/pos';
import initProductDeliveryDetailsEvent from './admin/product-delivery-details';

window.$ = window.jQuery = $;


ajaxSetup();

setUpPosEvents();

// initProductDeliveryDetailsEvent();