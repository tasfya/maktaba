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
	    	e.preventDefault();
	    	play($(this).attr('href'));
	    });

      $('.toggle-show-icon').on('click', function(event) {
        $('.sticky-bottom').toggleClass('visible');
        $('body').toggleClass('player-visible');
      });


      function fetch_all_radios_infos(){
        $.ajax({
          url: 'http://radio-panel.miraath.net/radiostations.json',
        })
        .done(render_radios)
      }
      function render_radios(radios){
        $("#radios").html(tmpl("radios_tmpl", {radios}));
      }
      fetch_all_radios_infos()
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
