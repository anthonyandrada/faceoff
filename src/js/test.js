// set some general variables
var $video_player, _player, _isPlaying = false;
jQuery(document).ready(function ($) {
    jQuery(".fancy_video").fancybox({
        // set type of content (we are building the HTML5 <video> tag as content)
        type: "html",
        // other API options
        scrolling: "no",
        fitToView: false,
        autoSize: false,
        beforeLoad: function () {
            // build the HTML5 video structure for fancyBox content with specific parameters
            this.content = "<video id='video_player' src='" + this.href + "' poster='" + $(this.element).data("poster") + "' width='360' height='360' controls='controls' preload='none' ></video>";
            // set fancyBox dimensions
            this.width = 360; // same as video width attribute
            this.height = 360; // same as video height attribute
        },
        afterShow: function () {
            // initialize MEJS player
            var $video_player = new MediaElementPlayer('#video_player', {
                defaultVideoWidth: this.width,
                defaultVideoHeight: this.height,
                success: function (mediaElement, domObject) {
                    _player = mediaElement; // override the "mediaElement" instance to be used outside the success setting
                    _player.load(); // fixes webkit firing any method before player is ready
                    _player.play(); // autoplay video (optional)
                    _player.addEventListener('playing', function () {
                        _isPlaying = true;
                    }, false);
                } // success
            });
        },
        beforeClose: function () {
            // if video is playing and we close fancyBox
            // safely remove Flash objects in IE
            if (_isPlaying && navigator.userAgent.match(/msie [6-8]/i)) {
                // video is playing AND we are using IE
                _player.remove(); // remove player instance for IE
                _isPlaying = false; // reinitialize flag
            };
        }
    });
}); // ready
