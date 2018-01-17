import 'expose?Tether!tether';
import 'bootstrap/dist/js/bootstrap.min';
import 'flexibility';
import 'bootstrap-touchspin';
import 'jquery.uniform';

import '../css/theme';
import './responsive';
import './checkout';
import './customer';
import './listing';
import './product';
import './cart';

import DropDown from './components/drop-down';
import Form from './components/form';
import ProductMinitature from './components/product-miniature';
import ProductSelect from './components/product-select';
import TopMenu from './components/top-menu';

import prestashop from 'prestashop';
import EventEmitter from 'events';

import './lib/bootstrap-filestyle.min';
import './lib/jquery.scrollbox.min';
import './lib/slick.min';
import './lib/jquery.smooth-scroll.min';
import './lib/pace';
import './lib/jquery.sticky';
//import './lib/SmoothScroll';
import './lib/jquery.countdown.min';
import './lib/jquery.nivo.slider.pack';
import './lib/colpick';
import './lib/jquery.elevateZoom.min';
import './lib/jquery.magnific-popup.min';

import './components/block-cart';
import './components/ps-modules';

import './aone/aone-module';

// "inherit" EventEmitter
for (var i in EventEmitter.prototype) {
  prestashop[i] = EventEmitter.prototype[i];
}

$(document).ready(() => {
  let dropDownEl = $('.js-dropdown');
  const form = new Form();
  let dropDown = new DropDown(dropDownEl);
  let productSelect  = new ProductSelect();
  //let topMenuEl = $('.js-top-menu ul[data-depth="0"]');
  //let topMenu = new TopMenu(topMenuEl);
  //let productMinitature = new ProductMinitature();

  dropDown.init();
  form.init();
  productSelect.init();
});
