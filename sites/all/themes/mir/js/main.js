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
    }
  }
})(jQuery,Drupal);
