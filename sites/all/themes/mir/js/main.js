var Drupal = Drupal || {};
(function ($,Drupal) {
  Drupal.behaviors.mir = {
    attach: function (context, settings){
			function play(audiofile){
				var audio_player = document.getElementById('audio-player');
				audio_player.removeAttribute("src");
			 	audio_player.src = audiofile;
			 	audio_player.play();
			}

	    $('.field-name-field-audio a, a.play').on('click', function(e){
        $('.sticky-bottom').addClass('visible');
        $('body').addClass('player-visible');

	    	e.preventDefault();
	    	play($(this).attr('href'));
	    });

      $('.toggle-show-icon').on('click', function(event) {
        $('.sticky-bottom').toggleClass('visible');
        $('body').toggleClass('player-visible');
      });

     function fetch_main_radio_infos(){
        $.ajax({
          url: 'http://192.34.56.9/radiostations/0.json',
        })
        .done(render_main_radio)
      }
      function render_main_radio(radio_info){

        $("#radio-stream-url").attr('href', radio_info.streaming_url);
        $("#current_playing").text(radio_info.live_info.current_playing);
        $("#listners-count").text(radio_info.live_info.listeners_count);
      }
      fetch_main_radio_infos()
    }
  }
})(jQuery,Drupal);

function active_if_0(i){
  if (i===0) {return 'active'} else { return ''}
}

(function(){
  var cache = {};

  window.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
      cache[str] = cache[str] ||
        tmpl(document.getElementById(str).innerHTML) :

      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +

        // Introduce the data as local variables using with(){}
        "with(obj){p.push('" +

        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("<%").join("\t")
          .replace(/((^|%>)[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)%>/g, "',$1,'")
          .split("\t").join("');")
          .split("%>").join("p.push('")
          .split("\r").join("\\'")
      + "');}return p.join('');");

    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();
