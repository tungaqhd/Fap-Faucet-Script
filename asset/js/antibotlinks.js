$(function() {
    var claim_button=$('form[method="POST"] input[type="submit"], form[method="POST"] input[type="button"]');
    var clicks = 0;
    var time_interval = 5;
    var interval;
    $('#antibotlinks_reset').hide();
    $('.antibotlinks').each(function(k){
        if (typeof(ablinks[k])!=='undefined') {
            $(this).html(ablinks[k]);
        }
    });
    claim_button.after('<div id="ncb"></div>');
    $('#ncb').append('<input type="'+claim_button.attr('type')+'" class="'+claim_button.attr('class')+'" value="Get Reward!" />');
    claim_button.remove();
    claim_button=$('#ncb input');
    claim_button.css('display', 'none');

    $('.antibotlinks a').click(function() {
        $('#antibotlinks_reset').show();
        clicks++;
        $('#antibotlinks').val($('#antibotlinks').val()+' '+$(this).attr('rel'));
        if(clicks==ablinks.length) {
            var badblock=false;

            if ((badblock)&&($('#tester').length==0)) {
                claim_button.val('Please disable AdBlock and reload');
            } else {
                if (time_interval>0) {
                    claim_button.val('Please wait '+time_interval+' seconds!');
                    claim_button.prop('disabled', true);
                    interval=setInterval(function() {
                        time_interval--;
                        if (time_interval>0) {
                            claim_button.css('display', '');
                            claim_button.prop('disabled', true);
                            claim_button.val('Please wait '+time_interval+' seconds!');
                        } else {
                            claim_button.prop('disabled', false).val('Get Reward!');
                            clearInterval(interval);
                        }
                    }, 1000);
                }
            }
            claim_button.css('display', '');
        }
        $(this).hide();
        return false;
    });

    $('#antibotlinks_reset').click(function() {
        clicks = 0;
        $('#antibotlinks').val('');
        $('.antibotlinks a').show();
        time_interval = 5;
        if (typeof(interval)!='undefined') {
            clearInterval(interval);
        }
        claim_button.css('display', 'none');
        $('#antibotlinks_reset').hide();
        return false;
    });
});