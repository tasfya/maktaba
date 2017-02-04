var Drupal = Drupal || {};
(function ($, Drupal) {
  Drupal.behaviors.mir = {
    attach: function (context, settings) {
      function play(audiofile) {
        $('#jquery_jplayer_1').jPlayer('setMedia', {
          title: 'Audio loaded !!',
          mp3: audiofile
        });
        $('#jquery_jplayer_1').jPlayer('play');
      }

      $('.field-name-field-audio a, a.play').on('click', function (e) {
        e.preventDefault();
        play($(this).attr('href'));
      });
      // jplayer:
      $(document).ready(function () {
        $('#jquery_jplayer_1').jPlayer({
          ready: function () {
            $(this).jPlayer('setMedia', {
              title: 'Bubble',
              m4a: 'http://www.jplayer.org/audio/m4a/Miaow-07-Bubble.m4a',
              oga: 'http://www.jplayer.org/audio/ogg/Miaow-07-Bubble.ogg'
            });
          },
          cssSelectorAncestor: '#jp_container_1',
          swfPath: '/js',
          supplied: 'm4a,ogg,mp3',
          useStateClassSkin: true,
          autoBlur: false,
          smoothPlayBar: true,
          keyEnabled: true,
          remainingDuration: true,
          toggleDuration: true
        });
      });
    }
  };
})(jQuery, Drupal);
