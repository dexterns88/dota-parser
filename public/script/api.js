// javascript api
(function($) {

	$(window).load(function(){
		if( jQuery().mCustomScrollbar ){
			$(".chat .content").mCustomScrollbar();
		}
  });

	$(document).ready(function(){
		$('.teams .player .basic-info').heroExpand();

    $('.replay_list .pager').ajaxView();

    
    if( $.chatPlayerSelect ) {
      $('.chat .content li').chatPlayerSelect();
    }

	});

//plugin

$.chatPlayerSelect = function( element , options ) {
  
  var settings = {};
  element.data('chatPlayerSelect' , this);
  var storage = false;
  var activeClass = "jsSelected";
  obj = this;

  this.init = function( options ) {
    
    this._atachEvent();
      
  };

  this._atachEvent = function() {
    element.on('click',function(){
      
      if( $(this).hasClass(activeClass)) {
        obj._removeClass();
      } else {
        obj._addClass( $(this) );  
      }
      
    });
  };
  
  this._removeClass = function() {
    storage.removeClass(activeClass);
  }

  this._addClass = function(el) {
    
    if( storage != false ) {
      storage.removeClass( activeClass );
    }

    var tmpClass = "." + el.attr('class');
    storage = element.parent().children(tmpClass);
    storage.addClass( activeClass );

  };

  this.init( options );
};

$.fn.chatPlayerSelect = function(options) {
  return this.each(function() {
    (new $.chatPlayerSelect($(this), options));
  });        
};


$.heroExpand = function( element ) {
	
  var settings = {};
	element.data('heroExpand' , this);
  var obj = this;

  this.init = function() {
  	
  	this._click();

  };


  this._ico = function( event , el ) {

  	if( event == 'normal' ) {
  		el.find('.fa-arrow-circle-down').css('display','inline-block');
  		el.find('.fa-arrow-circle-up').hide();
  	} else {
  		el.find('.fa-arrow-circle-down').hide();
  		el.find('.fa-arrow-circle-up').css('display','inline-block');
  	}

  }

  this._click = function() {

  	element.css('cursor','pointer');

  	element.on('click',function(){

  		var next = $(this).next('.more-info');
  		if( next.hasClass('expanded') ) {
  			next.stop().slideUp();
  			next.removeClass('expanded');

  			obj._ico('normal' , $(this) );

  		} else {
  			next.stop().slideDown();
  			next.addClass('expanded');
  			
  			obj._ico('invers' , $(this));

  		}

  	});
  };
  
  this.init();
};

$.fn.heroExpand = function() {
  return this.each(function() {
    (new $.heroExpand($(this)));
  });        
};

// ajax 

$.ajaxView = function( element ) {
  
  var settings = {};
  element.data('ajaxView' , this);

  var obj = this;

  this.init = function() {
    this._ajax();
  };

  this._ajax = function() {

    var link = $('.replay_list .pager a');

    link.on('click',function(e){
      e.preventDefault();
      var page = $(this).attr('href').split('/');
      if( page[3].length == 0 ) {
        page = 0;
      } else {
        page = page[3];  
      }
      var url = "/view/ajax/" + page;
      
      $(".replay_list" ).load( url ,  { 'type':'ajax' } , function(){
        obj.init();
      });

    });
    
  };
  
  this.init();
};

$.fn.ajaxView = function() {
  return this.each(function() {
    (new $.ajaxView($(this)));
  });        
};



})(jQuery);