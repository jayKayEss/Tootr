(function($){

    $(document).ready(function(){
        var tootr = new Tootr();
        tootr.init();
    });

    function Tootr() {

    }

    Tootr.prototype = {

        init: function() {
            this.displayTweet();
            var self = this;
            setInterval(function(){
                self.displayTweet();
            }, 10000);
        },

        displayTweet: function() {
            var self = this;
            $.ajax({
                dataType: 'json',
                url: 'srv/gettweet.php',
                success: function(data){
                    var stream = data.stream;
                    var text = data.text;
                    var elem = $('#thetweet');

                    elem.find('.container').fadeOut(function(){
                        elem.find('.user').html('@'+stream);
                        elem.find('.name').html(stream.charAt(0).toUpperCase() + stream.slice(1));
                        elem.find('.text').html(text);

                        elem.find('.container').fadeIn();
                    });
                }
            });
        },

    };

})(jQuery);

