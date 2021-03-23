$(document).ready(function() {
    $('#start_survey').click(function() {
        $('.pre_vote').hide()
        $('.section:first-child').show()
    })
    $('.options .option').click(function() {
        $(this).parent('.options').children('.option').removeClass('active');
        $(this).addClass('active');
        $(this).parent('.options').children('input[type=hidden]').val($(this).attr('rate'));
        $(this).parent('.options').siblings('.nextQuestion').css('display','block')
        $(this).parent('.options').siblings('#submit').css('display','block')
    })
    $('.nextQuestion').click(function() {
        $(this).parent('.section').hide();
        console.log($(this).parent('.section').next('.section').show())
    })
});