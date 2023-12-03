/** @format */

(function ($) {
  "use strict";

  //*============ background color js ==============*/
  $("[data-bg-color]").each(function () {
    var bg_color = $(this).data("bg-color");
    $(this).css({
      "background-color": bg_color,
    });
  });

  //*============ background image js ==============*/
  $("[data-bg-img]").each(function () {
    var bg = $(this).data("bg-img");
    $(this).css({
      background: "no-repeat center 0/cover url(" + bg + ")",
    });
  });

  var inputs = $(".signup_form_style_two .form-control").not(":submit");

  inputs.on("input", function (idx) {
    var top_text = $(this).parent(".form-group").find(".top_text");
    $(this).toggleClass("animated", this.value > "");
    $(top_text).toggleClass("animated", this.value > "");
  });

  $(".signup_form_style_two .form-control")
    .focus(function () {
      $(this).parent().find(".top_text").addClass("animated");
    })
    .blur(function () {
      $(this).parent().find(".top_text").removeClass("animated");
    });

  function bodyScroll() {
    var bodyAnimation = $("body").data("scroll-animation");
    if (bodyAnimation === true) {
      new WOW({}).init();
    }
  }
  bodyScroll();

  if ($(".niceselect").length) {
    $(".niceselect").niceSelect();
  }

  function bootstrapTabControl() {
    if ($(".form_tab .nav-link").hasClass("active")) {
      $(".form_tab .nav-link.active").addClass("complete");
    }
    var i,
      items = $(".nav-link"),
      pane = $(".tab-pane");
    // next
    $(".next_tab").on("click", function () {
      for (i = 0; i < items.length; i++) {
        if ($(items[i]).hasClass("active") == true) {
          break;
        }
      }
      if (i < items.length - 1) {
        // for tab
        $(items[i]).removeClass("active");
        $(items[i + 1]).addClass("active complete ");
        // for pane
        $(pane[i]).removeClass("show active");
        $(pane[i + 1]).addClass("show active");
      }
    });
    $(".prev_tab").on("click", function () {
      for (i = 0; i < items.length; i++) {
        if ($(items[i]).hasClass("active") == true) {
          break;
        }
      }
      if (i != 0) {
        // for tab
        $(items[i]).removeClass("active complete");
        $(items[i - 1]).addClass("active");
        // for pane
        $(pane[i]).removeClass("show active complete");
        $(pane[i - 1]).addClass("show active");
      }
      // if ($(".form_tab .nav-link").hasClass("com")) {
      //   $(".form_tab .nav-link.active").addClass("show");
      // }
    });
  }
  bootstrapTabControl();
  // $(".form_tab .nav-link.active").addClass("show");

  /* ========================================================== */
  /*   Particles Background                                     */
  /* ========================================================== */

  // if ($("#particles-js").length) {
  //   particlesJS("particles-js", {
  //     particles: {
  //       number: {
  //         value: 80,
  //         density: {
  //           enable: true,
  //           value_area: 800,
  //         },
  //       },
  //       color: {
  //         value: "#3280FF",
  //       },
  //       shape: {
  //         type: "circle",
  //         stroke: {
  //           width: 0,
  //           color: "#000000",
  //         },
  //         polygon: {
  //           nb_sides: 5,
  //         },
  //         image: {
  //           src: "img/github.svg",
  //           width: 100,
  //           height: 100,
  //         },
  //       },
  //       opacity: {
  //         value: 0.8,
  //         random: false,
  //         anim: {
  //           enable: false,
  //           speed: 1,
  //           opacity_min: 0.1,
  //           sync: false,
  //         },
  //       },
  //       size: {
  //         value: 7,
  //         random: true,
  //         anim: {
  //           enable: false,
  //           speed: 40,
  //           size_min: 0.1,
  //           sync: false,
  //         },
  //       },
  //       line_linked: {
  //         enable: true,
  //         distance: 150,
  //         color: "#ffffff",
  //         opacity: 0.4,
  //         width: 1,
  //       },
  //       move: {
  //         enable: true,
  //         speed: 6,
  //         direction: "none",
  //         random: false,
  //         straight: false,
  //         out_mode: "out",
  //         attract: {
  //           enable: false,
  //           rotateX: 600,
  //           rotateY: 1200,
  //         },
  //       },
  //     },
  //     interactivity: {
  //       detect_on: "canvas",
  //       events: {
  //         onhover: {
  //           enable: true,
  //           mode: "repulse",
  //         },
  //         onclick: {
  //           enable: true,
  //           mode: "push",
  //         },
  //         resize: true,
  //       },
  //       modes: {
  //         grab: {
  //           distance: 400,
  //           line_linked: {
  //             opacity: 1,
  //           },
  //         },
  //         bubble: {
  //           distance: 400,
  //           size: 40,
  //           duration: 2,
  //           opacity: 8,
  //           speed: 3,
  //         },
  //         repulse: {
  //           distance: 200,
  //         },
  //         push: {
  //           particles_nb: 4,
  //         },
  //         remove: {
  //           particles_nb: 2,
  //         },
  //       },
  //     },
  //     retina_detect: true,
  //     config_demo: {
  //       hide_card: false,
  //       background_color: "#b61924",
  //       background_image: "",
  //       background_position: "50% 50%",
  //       background_repeat: "no-repeat",
  //       background_size: "cover",
  //     },
  //   });
  // }

  /*-------------------------------------------------------------------------------
	  Parallax Effect js
	-------------------------------------------------------------------------------*/
  function parallaxEffect() {
    if ($(".parallax-effect").length) {
      $(".parallax-effect").parallax();
    }
  }
  parallaxEffect();
})(jQuery);
