$(document).ready(function () {
  let height = $(".header").height();
  $("main").css({ "margin-top": height });
  if ($("body").hasClass("admin-bar")) {
    $("header").css({ top: "3.2rem" });
  } else {
    $("header").css({ top: "0" });
  }
  // nav-link
  const widtScreen = window.innerWidth;
  let topMenu = 250;
  if (widtScreen < 968) {
    topMenu = 100;
  }

  $(".js-toggle-navi").click(function () {
    $(".header").toggleClass("on-nav");
    $("body").toggleClass("overflow-hidden");
  });

  $(window).scroll(function () {
    if ($(this).scrollTop()) {
      $(".backtotop").fadeIn();
      $(".header").addClass("fixed");
    } else {
      $(".backtotop").fadeOut();
      $(".header").removeClass("fixed");
    }
  });
});

$(document).ready(function () {
  $(".banner-slider").slick({
    dots: true,
    infinite: true,
    autoplay: false,
    speed: 500,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
  });
  $(".service-slider").slick({
    dots: false,
    infinite: true,
    autoplay: false,
    speed: 500,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: false,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });
  $(".js-slide").slick({
    dots: false,
    infinite: true,
    autoplay: true,
    speed: 500,
    slidesToShow: 4,
    slidesToScroll: 1,
    prevArrow:
      "<button class='slick-btn prev slick-prev'><span class='arrow left'></span></button>",
    nextArrow:
      "<button class='slick-btn next slick-next'><span class='arrow right'></span></button>",
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });
  $(".blog-slider").slick({
    dots: false,
    infinite: true,
    autoplay: true,
    speed: 500,
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow:
      "<button class='slick-btn prev slick-prev'><span class='arrow left'></span></button>",
    nextArrow:
      "<button class='slick-btn next slick-next'><span class='arrow right'></span></button>",
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });
  $("ul.tabs li").click(function () {
    var id = $(this).attr("data-id");
    // console.log(id);
    $("ul.tabs li").removeClass("active");
    $(".tab-content").removeClass("active");

    $(this).addClass("active");
    $("#" + id).addClass("active");
  });
});

$(function ($) {
  // $(".range-slider").jRange({
  //   from: 0,
  //   to: 100,
  //   step: 1,
  //   scale: [500000, 5750000, 10000000],
  //   format: "%s",
  //   width: 300,
  //   showLabels: true,
  //   isRange: true,
  // });
  $("#filter").change(function () {
    var filter = $("#filter");
    $(".loader").addClass("active");
    $.ajax({
      url: filter.attr("action"),
      data: filter.serialize(), // form data
      type: filter.attr("method"), // POST
      success: function (data) {
        // console.log(data);
        // filter.find("button").text("Apply filter"); // changing the button label back
        $(".product-wrap").html(data); // insert data
        setTimeout(function () {
          $(".loader").removeClass("active");
        }, 200);
      },
    });
    return false;
  });

  // var lastChecked = $("input:radio[name='orderby']").prop("checked");
  // $("input:radio[name='orderby']").on("click", function () {
  //   if ($(this).prop("checked") === lastChecked) {
  //     $(this).prop("checked", false);
  //   }

  //   lastChecked = $(this).prop("checked");
  // });
});
