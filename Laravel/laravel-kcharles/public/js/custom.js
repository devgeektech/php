$(document).ready(function(){
    $('.SliderHomeBanner').owlCarousel({
        loop:true,
        nav: true,
        navText: ["<img src='images/large_left.png'>","<img src='images/large_right.png'>"],
        animateOut: 'fadeOut',
      animateIn: 'fadeIn', 
     smartSpeed:1450,
      autoplayTimeout:2000, 
        autoplay:true,	    
        autoplayHoverPause:true,	
        center: true,      
        
        responsive:{
            0:{
                items:1,
                 autoplay:false,	    
        autoplayHoverPause:false,
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }    
    });
    

    });

$(window).on('load', function(){
    setTimeout(function(){
    $('#GetAQuotePopUp').fancybox().trigger('click');
    }, 3000);
});


$(document).ready(function(){
   $('.FixedEnvelopeIcon').on('click', function(){
    $('#GetAQuotePopUp').fancybox().trigger('click');
   });
});


jQuery(document).ready(function(){
    jQuery('.ShowMoreAboutBtn').click(function(){
       jQuery('#ShowMoreAboutContent').slideToggle(function(){
          jQuery('.ShowMoreAboutBtn').fadeOut(150);
          jQuery('.ShowLessAboutBtn').fadeIn(150);
       }); 
    });  
    
    
    jQuery('.ShowLessAboutBtn').click(function(){
       jQuery('#ShowMoreAboutContent').slideToggle(function(){
          jQuery('.ShowLessAboutBtn').fadeOut(150);
          jQuery('.ShowMoreAboutBtn').fadeIn(150);
       }); 
    });
    
    jQuery('.ShowMoreServicesBtn').click(function(){
       jQuery('#ShowMoreServicesContent').slideToggle(function(){
          jQuery('.ShowMoreServicesBtn').fadeOut(150);
          jQuery('.ShowLessServicesBtn').fadeIn(150);
       }); 
    });  
    
    
    jQuery('.ShowLessServicesBtn').click(function(){
       jQuery('#ShowMoreServicesContent').slideToggle(function(){
          jQuery('.ShowLessServicesBtn').fadeOut(150);
          jQuery('.ShowMoreServicesBtn').fadeIn(150);
       }); 
    });
    
    
    jQuery('.ShowMoreWhyShouldBtn1').click(function(){
       jQuery('.ShowMoreWhyShouldContent1').slideToggle(function(){
          jQuery('.ShowMoreWhyShouldBtn1').fadeOut(150);
          jQuery('.ShowLessWhyShouldBtn1').fadeIn(150);
       }); 
    });  
    
    
    jQuery('.ShowLessWhyShouldBtn1').click(function(){
       jQuery('.ShowMoreWhyShouldContent1').slideToggle(function(){
          jQuery('.ShowLessWhyShouldBtn1').fadeOut(150);
          jQuery('.ShowMoreWhyShouldBtn1').fadeIn(150);
       }); 
    }); 
    
    
    jQuery('.ShowMoreWhyShouldBtn2').click(function(){
       jQuery('.ShowMoreWhyShouldContent2').slideToggle(function(){
          jQuery('.ShowMoreWhyShouldBtn2').fadeOut(150);
          jQuery('.ShowLessWhyShouldBtn2').fadeIn(150);
       }); 
    });  
    
    
    jQuery('.ShowLessWhyShouldBtn2').click(function(){
       jQuery('.ShowMoreWhyShouldContent2').slideToggle(function(){
          jQuery('.ShowLessWhyShouldBtn2').fadeOut(150);
          jQuery('.ShowMoreWhyShouldBtn2').fadeIn(150);
       }); 
    });
    
      jQuery('.ShowMoreWhyShouldBtn4').click(function(){
       jQuery('.ShowMoreWhyShouldContent4').slideToggle(function(){
          jQuery('.ShowMoreWhyShouldBtn4').fadeOut(150);
          jQuery('.ShowLessWhyShouldBtn4').fadeIn(150);
       }); 
    });  
    
    
    jQuery('.ShowLessWhyShouldBtn4').click(function(){
       jQuery('.ShowMoreWhyShouldContent4').slideToggle(function(){
          jQuery('.ShowLessWhyShouldBtn4').fadeOut(150);
          jQuery('.ShowMoreWhyShouldBtn4').fadeIn(150);
       }); 
    });   
      
});


$(document).ready(function(){
    $('#MobileToggleMenu').click(function(){
        $('#HeaderMenu').slideToggle(300);
    });
});







$(window).scroll(function(){
        if($(window).scrollTop() >= 150){
                $(".HeaderMenuSection").addClass("animated-header");
            }else{
                $(".HeaderMenuSection").removeClass("animated-header");
                }
});



$(window).scroll(function() {
    
    var scrollDistance = $(window).scrollTop(); 
    if(scrollDistance > 1600 && scrollDistance < 2320){
        $('.mainmenuInner .menu-item a').each(function(){
           $(this).removeClass('active'); 
        });
    }else{
        $('.eachsection').each(function(i) {
            if ($(this).position().top - 60 <= scrollDistance) {
                $('.mainmenuInner .menu-item a.active').removeClass('active');
                $('.mainmenuInner .menu-item a').eq(i).addClass('active');
            } else {
               $('.mainmenuInner .menu-item a').eq(i).removeClass('active');
            }
        });
    }
});
$(document).ready(function() {
    $('.mainmenuInner .menu-item a').click(function() {
        $('html, body').animate({
            scrollTop: ($($.attr(this, 'href')).offset().top - 40)
        }, 1000);
        return false;
    });
})


$(window).scroll(function(){
        if($(window).scrollTop() >= 150){
                $(".HeaderMenuSection").addClass("animated-header");
            }else{
                $(".HeaderMenuSection").removeClass("animated-header");
                }
});



$(window).scroll(function() {
    if($(window).scrollTop() >=500) {
        $('#back-to-top').fadeIn();
    }else {
        $('#back-to-top').fadeOut();
    }    
});


$(document).ready(function() {
     $('#back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
     });
})