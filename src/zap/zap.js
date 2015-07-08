(function(configuration){
  'use strict';
  
  function ImageJuggler(configuration){
    var elements = { scale: {}, rotate: {}, horizontal: {}, vertical: {}, color: {}, opacity: {}, quality: {} };
    var data = { input: {}, output: {}, color: {} };
    
    function browse(event){ elements.picture.click(); }

    function select(event){ elements.selected.value = event.target.value; }

    function scale(event){
      var value = event.target.value;
      if(value === '+') data.scale += (data.scale >= configuration.scale.max) ? 0 : configuration.scale.amount;
      if(value === '-') data.scale -= (data.scale <= configuration.scale.min) ? 0 : configuration.scale.amount;
      ui();
    }

    function rotate(event){
      var value = event.target.value;
      if(value === '+'){
        data.rotate = (data.rotate >= configuration.rotate.max)
          ? configuration.rotate.amount : data.rotate + configuration.rotate.amount;
      }
      if(value === '-'){
        data.rotate = (data.rotate <= configuration.rotate.min)
          ? configuration.rotate.max - configuration.rotate.amount : data.rotate - configuration.rotate.amount;
      }
      ui();
    }
    
    function horizontal(event){
      var value = event.target.value;
      if(value === '+') data.horizontal += configuration.horizontal.amount;
      if(value === '-') data.horizontal -= configuration.horizontal.amount;
      ui();
    }
    
    function vertical(event){
      var value = event.target.value;
      if(value === '+') data.vertical += configuration.vertical.amount;
      if(value === '-') data.vertical -= configuration.vertical.amount;
      ui();
    }
    
    function color(event){
      var c = rgb(event.target.value);
      data.color.r = c.r;
      data.color.g = c.g;
      data.color.b = c.b;
      ui();
    }
    
    function transparency(event){
      var value = event.target.value;
      if(value === '+') data.color.a += (data.color.a >= configuration.color.max) ? 0 : configuration.color.amount;
      if(value === '-') data.color.a -= (data.color.a <= configuration.color.min) ? 0 : configuration.color.amount;
      ui();
    }

    function opacity(event){
      var value = event.target.value;
      if(value === '+') data.opacity += (data.opacity >= configuration.opacity.max) ? 0 : configuration.opacity.amount;
      if(value === '-') data.opacity -= (data.opacity <= configuration.opacity.min) ? 0 : configuration.opacity.amount;
      ui();
    }

    function quality(event){
      var value = event.target.value;
      if(value === '+') data.quality += (data.quality >= configuration.quality.max) ? 0 : configuration.quality.amount;
      if(value === '-') data.quality -= (data.quality <= configuration.quality.min) ? 0 : configuration.quality.amount;
      ui();
    }
    
    function reset(event){
      init();
      ui();
    }

    function transform(){
      var scale = 'scale(' + data.scale + ',' + data.scale + ')';
      var rotate = 'rotate(' + data.rotate + 'deg)';
      var translate = '';
      var transformation = scale + ' ' + rotate + ' ' + translate; 
      elements.retouch.style.webkitTransform = transformation;
      elements.retouch.style.MozTransform = transformation;
      elements.retouch.style.msTransform = transformation;
      elements.retouch.style.OTransform = transformation;
      elements.retouch.style.transform = transformation;
      elements.retouch.style.left = ((0 - (data.input.width * 0.5)) + data.horizontal) + 'px';
      elements.retouch.style.top = ((0 - (data.input.height * 0.5)) + data.vertical) + 'px';
      elements.retouch.style.opacity = data.opacity;
      elements.viewport.style.background =
        'rgba(' + data.color.r + ',' + data.color.g + ',' + data.color.b + ',' + data.color.a + ')';
    }

    function ui(){
      elements.scale.note.innerHTML = (data.scale * 100.00).toFixed(2) + ' %';
      elements.rotate.note.innerHTML = data.rotate.toFixed(2) + ' deg';
      elements.horizontal.note.innerHTML = data.horizontal.toFixed(2) + ' px';
      elements.vertical.note.innerHTML = data.vertical.toFixed(2) + ' px';
      elements.color.picker.value = hex(data.color.r, data.color.g, data.color.b);
      elements.color.note.innerHTML = (data.color.a * 100.0).toFixed(2) + ' %';
      elements.opacity.note.innerHTML = (data.opacity * 100.00).toFixed(2) + ' %';
      elements.quality.note.innerHTML = data.quality.toFixed(2) + ' %';
      elements.data.value = JSON.stringify(data);
      transform();
      if(configuration.debug === true) alert(JSON.stringify(data));
    }

    function elem(id){ return document.getElementById(id); }
 
    function rgb(hex){
      var h = (hex.charAt(0) === '#') ? hex.substr(1) : hex;
      return {
        r: parseInt(h.substring(0, 2), 16),
        g: parseInt(h.substring(2, 4), 16),
        b: parseInt(h.substring(4, 6), 16)
      };
    }
    
    function h(i){
      var hex = i.toString(16);
      return (hex.length === 1) ? '0' + hex : hex;
    }
    
    function hex(r,g,b){ return '#' + h(r) + h(g) + h(b); }

    function resolve(){
      elements.browse = elem(configuration.elements.browse);
      elements.picture = elem(configuration.elements.picture);
      elements.selected = elem(configuration.elements.selected);
      elements.viewport = elem(configuration.elements.viewport);
      elements.retouch = elem(configuration.elements.retouch);
      elements.scale.increment = elem(configuration.elements.scale.increment);
      elements.scale.decrement = elem(configuration.elements.scale.decrement);
      elements.scale.note = elem(configuration.elements.scale.note);
      elements.rotate.increment = elem(configuration.elements.rotate.increment);
      elements.rotate.decrement = elem(configuration.elements.rotate.decrement);
      elements.rotate.note = elem(configuration.elements.rotate.note);
      elements.horizontal.increment = elem(configuration.elements.horizontal.increment);
      elements.horizontal.decrement = elem(configuration.elements.horizontal.decrement);
      elements.horizontal.note = elem(configuration.elements.horizontal.note);
      elements.vertical.increment = elem(configuration.elements.vertical.increment);
      elements.vertical.decrement = elem(configuration.elements.vertical.decrement);
      elements.vertical.note = elem(configuration.elements.vertical.note);
      elements.color.picker = elem(configuration.elements.color.picker);
      elements.color.increment = elem(configuration.elements.color.increment);
      elements.color.decrement = elem(configuration.elements.color.decrement);
      elements.color.note = elem(configuration.elements.color.note);
      elements.opacity.increment = elem(configuration.elements.opacity.increment);
      elements.opacity.decrement = elem(configuration.elements.opacity.decrement);
      elements.opacity.note = elem(configuration.elements.opacity.note);
      elements.quality.increment = elem(configuration.elements.quality.increment);
      elements.quality.decrement = elem(configuration.elements.quality.decrement);
      elements.quality.note = elem(configuration.elements.quality.note);
      elements.reset = elem(configuration.elements.reset);
      elements.data = elem(configuration.elements.data);
    }
    
    function listen(){
      elements.browse.addEventListener('click', browse);
      elements.picture.addEventListener('change', select);
      elements.scale.increment.addEventListener('click', scale);
      elements.scale.decrement.addEventListener('click', scale);
      elements.rotate.increment.addEventListener('click', rotate);
      elements.rotate.decrement.addEventListener('click', rotate);
      elements.horizontal.increment.addEventListener('click', horizontal);
      elements.horizontal.decrement.addEventListener('click', horizontal);
      elements.vertical.increment.addEventListener('click', vertical);
      elements.vertical.decrement.addEventListener('click', vertical);
      elements.color.picker.addEventListener('change', color);
      elements.color.increment.addEventListener('click', transparency);
      elements.color.decrement.addEventListener('click', transparency);
      elements.opacity.increment.addEventListener('click', opacity);
      elements.opacity.decrement.addEventListener('click', opacity);
      elements.quality.increment.addEventListener('click', quality);
      elements.quality.decrement.addEventListener('click', quality);
      elements.reset.addEventListener('click', reset);
    }
    
    function init(){
      data.output.width = configuration.width;
      data.output.height = configuration.height;
      data.scale = configuration.scale.init;
      data.rotate = configuration.rotate.init;
      data.horizontal = configuration.horizontal.init;
      data.vertical = configuration.vertical.init;
      data.color.r = configuration.color.init.r;
      data.color.g = configuration.color.init.g;
      data.color.b = configuration.color.init.b;
      data.color.a = configuration.color.init.a;
      data.opacity = configuration.opacity.init;
      data.quality = configuration.quality.init;
    }

    function initialize(){
      init();
      resolve();
      listen();
    }
    
    initialize();

    var lazy = setInterval(function(){
      var w = elements.retouch.width, h = elements.retouch.height;
      if(w && h) done(w, h);
    }, 0);
    
    function done(width, height){
      clearInterval(lazy);
      data.input.width = width;
      data.input.height = height;
      if(elements.data.value !== '') data = JSON.parse(elements.data.value);
      ui();
    }
  }
  new ImageJuggler(configuration);
})({
  elements: {
    browse: 'x-app-browse', picture: 'x-app-picture',
    selected: 'x-app-selected', viewport: 'x-app-retouch-viewport', retouch: 'x-app-retouch-picture',
    scale: { increment: 'x-app-scale-in', decrement: 'x-app-scale-de', note: 'x-app-scale-no' },
    rotate: { increment: 'x-app-rotate-in', decrement: 'x-app-rotate-de', note: 'x-app-rotate-no' },
    horizontal: { increment: 'x-app-horizontal-in', decrement: 'x-app-horizontal-de', note: 'x-app-horizontal-no' },
    vertical: { increment: 'x-app-vertical-in', decrement: 'x-app-vertical-de', note: 'x-app-vertical-no' },
    color: { picker: 'x-app-color', increment: 'x-app-color-in', decrement: 'x-app-color-de', note: 'x-app-color-no' },
    opacity: { increment: 'x-app-opacity-in', decrement: 'x-app-opacity-de', note: 'x-app-opacity-no' },
    quality: { increment: 'x-app-quality-in', decrement: 'x-app-quality-de', note: 'x-app-quality-no' },
    reset: 'x-app-reset', data: 'x-app-data'
  },
  width: 1152, height: 360,
  scale: { init: 1.00, amount: 0.05, min: 0.00, max: 4.00 }, rotate: { init: 0.00, amount: 2.00, min: 0.00, max: 360.00 },
  horizontal: { init: 1152 * 0.5, amount: 10.00 }, vertical: { init: 360 * 0.5, amount: 10.00 },
  color: { init: { r: 255, g: 255, b: 255, a: 1.00 }, amount: 0.05, min: 0.00, max: 1.00 },
  opacity: { init: 1.00, amount: 0.05, min: 0.00, max: 1.00 },
  quality: { init: 100.00, amount: 1.00, min: 25.00, max: 100.00 },
  debug: false
});