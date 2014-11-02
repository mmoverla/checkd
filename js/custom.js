


        $(document).ready(function () {
            $('.navbar-inverse').waypoint('sticky');
        });
  
      jQuery(document).ready(function($) {  

      // site preloader -- also uncomment the div in the header and the css style for #preloader
      $(window).load(function(){
        $('#preloader').fadeOut('slow',function(){$(this).remove();});
      });

      });

      $(document).ready(function() {
 
  // Fakes the loading setting a timeout
    setTimeout(function() {
        $('body').addClass('loaded');
    }, 2000);
 
});



// <!-- noConflict -->

  var $ = jQuery.noConflict();
  $(document).ready(function() {
    //$("#form").validationEngine();
    $("#form").validate();
    $("#form1").validate();
  } );


// <!-- DEFER JAVASCRIPT -->

  function downloadJSAtOnload() {
    var element = document.createElement("script");
    element.src = "deferredfunctions.js";
    document.body.appendChild(element);
  }
  if (window.addEventListener)
    window.addEventListener("load", downloadJSAtOnload, false);
  else if (window.attachEvent)
    window.attachEvent("onload", downloadJSAtOnload);
  else window.onload = downloadJSAtOnload;


// <!-- Modal youtube -->

  $(document).ready(function ($) {

                // delegate calls to data-toggle="lightbox"
                $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
                  event.preventDefault();
                  return $(this).ekkoLightbox({
                    onShown: function() {
                      if (window.console) {
                        return console.log('Code is checkd? Contact us @ post@checkd.it');
                      }
                    }
                  });
                });


                $('#open-youtube').click(function (e) {
                  e.preventDefault();
                  $(this).ekkoLightbox();
                });
              });




// <!-- Navbar on scroll -->


var classes = ['iphone', 'mini', 'ipad', 'macbook', 'imac'];
var current = 0;

function setClass() {
  current++;
  
  if (current >= classes.length) {
    current = 0;
  }
  
  var device = document.getElementsByClassName('device')[0];
  device.setAttribute('class', ['device', classes[current]].join(' '));
  
  setTimeout(setClass, 1000);
};

setTimeout(setClass, 1000);




// <!-- Mouse transistion -->

  setTimeout(function () {
  $('.mouse').removeClass('hidden');
}, 0);
setTimeout(function () {
  $('.mouse').addClass('play');
}, 2000);



  function spillAv() {
    document.getElementById('wrap').style.cssText = 'display: block;';
    document.getElementById('overlay-image').style.cssText = 'display:none;';
    document.getElementById('playvid').src = 
    document.getElementById('playvid').src.replace('autoplay=0','autoplay=1');
  }


// <!-- Smooth scroll -->

    smoothScroll.init();



  
  var forms = document.getElementsByTagName('form');
for (var i = 0; i < forms.length; i++) {
    forms[i].noValidate = true;

    forms[i].addEventListener('submit', function(event) {
        //Prevent submission if checkValidity on the form returns false.
        if (!event.target.checkValidity()) {
            event.preventDefault();
            //Implement you own means of displaying error messages to the user here.
        }
    }, false);
}
