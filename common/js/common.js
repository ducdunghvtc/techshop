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

$(document).ready(function () {
  var offset = 5; // khái báo số lượng bài viết đã hiển thị
  $(".load-more").click(function (event) {
    $.ajax({
      // Hàm ajax
      type: "post", //Phương thức truyền post hoặc get
      dataType: "html", //Dạng dữ liệu trả về xml, json, script, or html
      async: false,
      url: "/wp-admin/admin-ajax.php", // Nơi xử lý dữ liệu
      data: {
        action: "loadmore", //Tên action, dữ liệu gởi lên cho server
        offset: offset, // gởi số lượng bài viết đã hiển thị cho server
      },
      beforeSend: function () {
        // Có thể thực hiện công việc load hình ảnh quay quay trước khi đổ dữ liệu ra
      },
      success: function (response) {
        $(".product").append(response);
        offset = offset + 5; // tăng bài viết đã hiển thị
      },
      error: function (jqXHR, textStatus, errorThrown) {
        //Làm gì đó khi có lỗi xảy ra
        console.log("The following error occured: " + textStatus, errorThrown);
      },
    });
  });
});
