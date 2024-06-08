
gsap.registerPlugin(ScrollTrigger)
ScrollTrigger.refresh();

let banner= gsap.timeline();
banner.fromTo('.panelBanner',{opacity:0,duration:2},{opacity:1})
let bannerPanel = ScrollTrigger.create({
  invalidateOnRefresh: true,
  animation:banner,
  toggleActions: "play complete reverse reset",
  trigger: ".panelBanner",
  pin: ".panelBanner",
  start: "top center",
  pinSpacing: true,
        end: "+=400",
//  markers: {startColor: "blue", endColor: "blue", fontSize: "18px", fontWeight: "bold"},
});


ScrollTrigger.saveStyles(".mobile, .desktop");

/*** Different ScrollTrigger setups for various screen sizes (media queries) ***/
ScrollTrigger.matchMedia({
	// desktop
	"(min-width: 800px)": function() {
		// setup animations and ScrollTriggers for screens over 800px wide (desktop) here...
		// ScrollTriggers will be reverted/killed when the media query doesn't match anymore.
    let st = ScrollTrigger.create({
      trigger: ".panele",
      invalidateOnRefresh: true,
      pin: ".panele",
      start: "top center",
      pinSpacing: false,
            end: "+=300",
    //  markers: {startColor: "red", endColor: "red", fontSize: "18px", fontWeight: "bold"},
    });
  },
	// mobile
	"(max-width: 799px)": function() {
		// Any ScrollTriggers created inside these functions are segregated and get
		// reverted/killed when the media query doesn't match anymore.
    let st = ScrollTrigger.create({
      trigger: ".panele",
      invalidateOnRefresh: true,
      pin: ".panele",
      start: "-120px top",
            end: "+=400",
    //  markers: {startColor: "blue", endColor: "blue", fontSize: "18px", fontWeight: "bold"},
    });
  },
	// all
	"all": function() {
		// ScrollTriggers created here aren't associated with a particular media query,
		// so they persist.
	}
});


//Animation leaves
let tl = gsap.timeline({scrollTrigger:{
    trigger:"#home.leaves",
    invalidateOnRefresh: true,
    start:"5px top",
    scrub:3,
    pinSpacing: false,
    toggleActions: "play complete reverse reset",
    //markers:true
}});
tl.fromTo('#home.leaves',{scale:1,duration:2},{scale:1.5})
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
  window.addEventListener("load", () => {
  let heart = gsap.timeline({scrollTrigger:{
    trigger:".knowledge",
    start:"center center",
    invalidateOnRefresh: true,
    end:"1000px bottom",
    toggleActions: "play play play pause",
    // markers: {startColor: "white", endColor: "white", fontSize: "18px", fontWeight: "bold"},
    ease:"linear",
  },
  repeat: -1
});
  heart.set("#heart",{scale:0, y:30,x:10, autoAlpha:0, duration:5, transformOrigin: "50% 50%"},0)
  heart.to("#heart",{scale:1, y:0, x:0,autoAlpha:1, duration:2, transformOrigin: "50% 50%",}, 0)
  heart.to("#heart",{autoAlpha:0,duration:0.2},'>')

  heart.set("#heart2",{scale:0, y:50,x:-20, autoAlpha:0, duration:5, transformOrigin: "50% 50%"},0)
  heart.to("#heart2",{scale:1, y:0, x:0,autoAlpha:1, duration:2, transformOrigin: "50% 50%",},0.4)
  heart.to("#heart2",{autoAlpha:0,duration:0.2},'>')

  heart.set("#heart3",{scale:0, y:50,x:-30, autoAlpha:0, duration:5, transformOrigin: "50% 50%"},0)
  heart.to("#heart3",{scale:1, y:0, x:0,autoAlpha:1, duration:2, transformOrigin: "50% 50%",},0.4)
  heart.to("#heart3",{autoAlpha:0,duration:0.2},'>')

  //Logo fixed
let logoFixed = gsap.timeline({
  scrollTrigger:{
  trigger:".logo",
  pin: true,
  start: "top -100px",
  invalidateOnRefresh: true,
  end: "top 200px",
  scrub:2,
  toggleActions: "play pause resume reset",
  pinSpacing: false,
  //markers:true,
}});

logoFixed.fromTo('.logo',{scale:1.3,  top: "50%",left: "50%",xPercent: -50,yPercent: -50,opacity:1, }, {scale:0.2,  top: "75px",left: "50%", xPercent: -50, yPercent: -50, duration:2});
logoFixed.to('#nestleTrash',{opacity:0, display:"none"},0)
logoFixed.to('.logoNestle',{opacity:1},0)
logoFixed.to('.scrollIcn',{opacity:0, duration:2},0)


if ($(window).width() < 800) {
  logoFixed.to('.logoNestle',{opacity:1},0)
  logoFixed.fromTo('.logo',{duration:2},{scale:0.5,duration:2,top: "78px",left:"58%"});
}


let traductorIcn = gsap.timeline();
traductorIcn.fromTo("#rotate",
            {rotation: 0},
            {rotation: 360,
             transformOrigin: "50% 50%",
             repeat: -1,
             duration:10,
             ease: "none",
            });

let men = gsap.timeline();
men.fromTo("#hombre",
            {rotation: 0},
            {rotation: 6,
             transformOrigin: "50% 50%",
             repeat: -1,
             duration:2,
             ease: "none",
             yoyo:true
            });

let hojalataanimation = gsap.timeline({
  scrollTrigger:{
    trigger:".scroll1",
    invalidateOnRefresh: true,
    start: "-200px center",
    toggleActions: "play pause resume reset",
    // markers:true
  }});
hojalataanimation.set("#Bee1",{y:0,x:-100, duration:5, opacity:1},0)
hojalataanimation.to("#Bee1",{y:2,x:2,duration:3},0)
hojalataanimation.to("#Bee1",{x:0,y:0, yoyo:true,duration:1, repeat:-1},'>')
hojalataanimation.set("#Bee2",{y:0,x:100, opacity:1,  duration:2,},0)
hojalataanimation.to("#Bee2",{y:2,x:0,duration:3},0)
hojalataanimation.to("#Bee2",{x:0,y:0, yoyo:true,duration:1, repeat:-1},'>')

let tucan = gsap.timeline({
  scrollTrigger:{
    trigger:".scroll1",
    invalidateOnRefresh: true,
    start: "-200px center",
    toggleActions: "play pause play pause",
    // markers:true,
  },
});

tucan.set("#tucan",{x:200,rotation:3, duration:0.3, transformOrigin: "50% 50%", repeat:-1,yoyo:true},0)
tucan.to('#tucan',{rotation:-3,transformOrigin: "50% 50%", duration:1,yoyo:true, repeat:-1,},0)
tucan.to('#tucan',{x:-200,duration:6, repeat: -1},0)
tucan.fromTo('#flores',{rotate:0},{repeat:-1, duration:2,yoyo:true,rotate:5,transformOrigin: "50% 50%"},0)

let granos = gsap.timeline({
  scrollTrigger:{
    trigger:".knowledge",
    invalidateOnRefresh: true,
    start:"top center",
    end:"100px bottom",
    toggleActions: "play restart play reset",
    // markers:true,
  },
});

granos.set("#grano1",{rotation:10, duration:4, transformOrigin: "50% 50%", repeat:-1,yoyo:true},0)
granos.to('#grano1',{rotation:-10,transformOrigin: "50% 50%", duration:4,yoyo:true, repeat:-1,},0)
granos.set("#grano2",{rotation:-10, duration:0.3, transformOrigin: "50% 50%", repeat:-1,yoyo:true},'<')
granos.to('#grano2',{rotation:10,transformOrigin: "50% 50%", duration:3,yoyo:true, repeat:-1,},'<')
granos.set('#capsulas',{opacity:0,scale:0,transformOrigin: "50% 50%", duration:3,},0)
granos.to('#capsulas',{opacity:1,scale:1.1,transformOrigin: "50% 50%", duration:1},0)

let mapa = gsap.timeline({
  scrollTrigger:{
    trigger:".scroll1",
    start: "-200px center",
    toggleActions: "play pause resume reset",
    //markers:true,
  },
});
mapa.set('#mapa',{opacity:0,scale:0,transformOrigin: "50% 50%", duration:3,},0)
mapa.to('#mapa',{opacity:1,scale:1,transformOrigin: "50% 50%", duration:1},0)
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

//circle knowledge
let circleKnowledge = gsap.timeline({scrollTrigger:{
  trigger:".knowledge",
  start:"top center",
  end:"100px bottom",
  toggleActions: "play restart play reset",
  //markers: {startColor: "red", endColor: "red", fontSize: "18px", fontWeight: "bold"},
 }});
 circleKnowledge.fromTo(".circleKnowledge",
            {rotation: 0},
            {rotation: 360,
             transformOrigin: "50% 50%",
             repeat: -1,
             duration:10,
             ease: "none",
            });
//bees
gsap.set(".bees",{opacity:0})
// loop through each element
$(".material").each(function(i, el) {
  let bees = gsap.timeline({paused:true});
  var t = bees
  .to($(el).find(".bees"),{opacity:1},0)
  .to($(el).find(".icnMaterial"),{scale:1.1, duration:.7},0)
  .to($(el).find(".beeder"),{x:-40,y:-30,duration:.7,opacity:1, ease:"linear" },0)
  .fromTo($(el).find(".beeder"),{y:-30, repeat:-1},{y:-40, yoyo:true,repeat:-1},0)
  // store the tween timeline in the javascript DOM node
  el.animation = t;

  //create the event handler
  $(el).on("mouseenter",function(){
    this.animation.restart();
  }).on("mouseleave",function(){
    this.animation.revert();
  });
});

//TYPING DECRESE FONT SIZE
/*$('textarea').keyup(function(ev){
  if($(this).val().length > 7)
  {
      var size =  $(this).css("font-size");
      size = size.slice(0,-2)
      size -= 0.9;
      if(size >= 8)
      {
          $(this).css("font-size",size + "px");
      }
  }

  else {
    $(this).css("font-size","30px");
  }

  var input = document.getElementById('trash-value');

  input.addEventListener('keydown', function(event) {
  const key = event.key; // const {key} = event; ES6+
  if (key === "Backspace") {
    $(this).css("font-size","30px");
    return false;
  }
});
});
*/

//SCROLL TO TRANSLATE SECTION
$(".material").click(function() {
  $('html,body').animate({
      scrollTop: $(".trashlater").offset().top},
      'slow');
});

$(".traductorIcn").click(function() {
  $('html,body').animate({
      scrollTop: $(".trashlater").offset().top},
      'slow');
});

//SLIDER
var swiper = new Swiper(".sliderMateriales", {
  slidesPerView: 1,
      spaceBetween: 10,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 10,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
        1080: {
          slidesPerView: 5,
          spaceBetween: 30,
        },
      },
    });

    //Banner Random
    var random = Math.floor(Math.random() * $('.knowledgeContainer').length);
$('.knowledgeContainer').eq(random).css('opacity','1');

//Hide intro
  if (!!localStorage.getItem("signIn")) {
    document.body.classList.add("signIn")
      $(".containerBanner").fadeOut();
      $('html,body').removeClass('blocked');
  }
  else {
    $(".accessSite, .closeIcnBanner").click(function() {
        localStorage.setItem("signIn", "ok")
          $(".containerBanner").fadeOut();
          $('html,body').removeClass('blocked');
    });
  }
  function handleLazyLoad(config={}) {
    let lazyImages = gsap.utils.toArray("img[loading='lazy']"),
        timeout = gsap.delayedCall(config.timeout || 1, ScrollTrigger.refresh).pause(),
        lazyMode = config.lazy !== false,
        imgLoaded = lazyImages.length,
        onImgLoad = () => lazyMode ? timeout.restart(true) : --imgLoaded || ScrollTrigger.refresh();
    lazyImages.forEach((img, i) => {
      lazyMode || (img.loading = "eager");
      img.naturalWidth ? onImgLoad() : img.addEventListener("load", onImgLoad);
    });
  }
  // usage: you can optionally set lazy to false to change all images to load="eager". timeout is how many seconds it throttles the loading events that call ScrollTrigger.refresh()
  handleLazyLoad({ lazy: false, timeout: 1 });