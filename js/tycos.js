
gsap.registerPlugin(ScrollTrigger)

//Animation leaves
let tl = gsap.timeline({scrollTrigger:{
    trigger:".leaves",
    start:"5px top",
    scrub:3,
    pinSpacing: false,
    toggleActions: "play complete reverse reset",
    //markers:true
   
}});
tl.fromTo('.leaves',{scale:1,duration:2},{scale:1.5})


  // SVG inline
  $('img[src$=".svg"]').each(function() {
    var $img = $(this);
    var imgURL = $img.attr('src');
    var attributes = $img.prop("attributes");

    $.get(imgURL, function(data) {
              // Get the SVG tag, ignore the rest
              var $svg = $(data).find('svg');

              // Remove any invalid XML tags
              $svg = $svg.removeAttr('xmlns:a');

              // Loop through IMG attributes and apply on SVG
              $.each(attributes, function() {
                $svg.attr(this.name, this.value);
              });

              // Replace IMG with SVG
              $img.replaceWith($svg);
            }, 'xml');
  });



//Menu
$(".navTrashContainer").click(function(){
  $(".navTrash").toggleClass('active');
    let showMenu = gsap.timeline();
    if ($(".navTrash").hasClass("active")) {
      $('html').css('overflow','hidden');
      $(".navTrash").css('display','block');
      showMenu.to(".icnMenu",{autoAlpha: 0})
      showMenu.to(".icnClose",{autoAlpha: 1}, "<")
      showMenu.to(".navTrash ul",{autoAlpha: 1}, "<")
      showMenu.fromTo(".navTrash",{backgroundColor:"rgba(227, 149, 3, 0.8)"},{backgroundColor:"rgba(0, 41, 23, 0.8)",  duration:1}, "<")
    }
    else{
      $('html').css('overflow','scroll');
      $(".navTrash").css('display','none');
      showMenu.to(".icnMenu",{autoAlpha: 1})
      showMenu.to(".icnClose",{autoAlpha: 0}, "<")
      showMenu.to(".navTrash ul",{autoAlpha: 0}, "<")
      showMenu.to(".navTrash",{backgroundColor:"transparent",duration:1}, "<")
    }
});








