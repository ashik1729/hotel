
/************************************************/
/* Go Top */
/************************************************/

$(window).scroll(function () {
    if ($(".go-top").offset().top > 1500) {
      $(".go-top").addClass("go-top-2");
    } else {
      $(".go-top").removeClass("go-top-2");
    }
  });



  // MOBILE MENU
  jQuery('.btn-menu-open').on('click', function(){
    jQuery('.mobile-menubar').addClass('open');
  });
  jQuery('.btn-menu-close').on('click', function(){
    jQuery('.mobile-menubar').removeClass('open');
  });

  jQuery('.btn-menu-open').on('click', function(){
    jQuery('body').addClass('scroll_mobile_fix');
  });
  jQuery('.btn-menu-close').on('click', function(){
    jQuery('body').removeClass('scroll_mobile_fix');
  });


  



/************************************************/
/* Counter Up */
/************************************************/


jQuery(document).ready(function( $ ) {
      $('.counter').counterUp({
          delay: 15,
          time: 1000
      });
  });


/************************************************/
/* slider */
/************************************************/

$(document).ready(function() {
  $('.home-slider').owlCarousel({
    loop: true,
    autoplay:true,
    margin: 0,
    nav: true,
    dots:false,
    items: 1
  })
})


$(document).ready(function() {
  $('.home-client__slider').owlCarousel({
    loop: true,
    autoplay:true,
    margin: 0,
    nav: true,
    dots:true,
    items: 1
  })
})

$(document).ready(function() {
  $('.inner__slider').owlCarousel({
    loop: true,
    autoplay:true,
    margin: 0,
    nav: false,
    dots:false,
    items: 1
  })
})


//QUANTITY COUNTER
    jQuery('.quantity-counter').each(function() {
      var spinner = jQuery(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.btn-quantity-up'),
        btnDown = spinner.find('.btn-quantity-down'),
        min = input.attr('min'),
        max = input.attr('max');

      btnUp.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue >= max) {
        var newVal = oldValue;
        } else {
          var newVal = oldValue + 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    });


  jQuery(document).ready(function($){
  $(".show-more-btn").click(function(e){
    $(".show-more-item:hidden").slice(0,1).fadeIn();
    if ($(".show-more-item:hidden").length < 1) $(this).fadeOut();
  })
})
