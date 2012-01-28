(function($){

    $(document).ready(function(){
        var tootr = new Tootr();
        tootr.init();
    });

    function Tootr() {
        this.colors = {
            life: '#737d17',
            death: '#333',
            sex: '#9b6262',
            money: '#4c8046'
        };
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
                        $('body').animate({
                            'background-color': $.Color( self.colors[stream] )
                        });
                        elem.find('.user').html('@'+stream);
                        elem.find('.name').html(stream.charAt(0).toUpperCase() + stream.slice(1));
                        elem.find('.text').html(text);
                        elem.find('.avatar img').attr({src: 'img/'+stream+'.png'});

                        elem.find('.container').fadeIn();

                    });
                }
            });
        },

    };

})(jQuery);

