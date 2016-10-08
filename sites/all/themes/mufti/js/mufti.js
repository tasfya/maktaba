
(function ($) {
  Drupal.behaviors.mudfti_theme_sticky_toolbar = {
    attach: function(context, settings) {
      //sticky the player 
      var top_menu = document.querySelector('#zone-user');
      if(top_menu){
        var origOffsetY = top_menu.offsetTop;
            // console.log(origOffsetY);
      
            function onScroll(e) {
              window.scrollY >= 236 ? top_menu.classList.add('sticky') :
                                              top_menu.classList.remove('sticky');
            }
      
            document.addEventListener('scroll', onScroll);
      }

      // by default hide now playing and the donwload link


      $("#more-information").hide();

      jQuery("#jquery_jplayer_1").jPlayer({
        swfPath: "js",
        supplied: "mp3",
        wmode: "window"
       });

      //Trigger the playing of audio when clicking on play
      jQuery(".playlist").click(function(){
        var $this = jQuery(this);
        $("#more-information").fadeIn();
        //console.log($this.parents("tr").text());
        //get the informations of the current played audio
        var $row =$this.parents("tr");
        $("#playing-title").text($row.find(".views-field-title").text());

        $("#download-title a").attr("href",$this.attr("href"));
        
        // start playing the audio file 
        jQuery(".jp-jplayer").jPlayer("setMedia",{
          mp3:$this.attr("href"),
          }).jPlayer("play");

        return false;
      })

    }
  }


})(jQuery);
