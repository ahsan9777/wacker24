<?php
include("includes/php_includes_top.php");
$page = 1;
?>
<!doctype html>
<html lang="de">

<head>
	<?php include("includes/html_header.php"); ?>
</head>

<body>
	<div id="container" align="center">

		<!--LOCATION_POPUP_START-->
		<?php include("includes/popup.php"); ?>
		<!--LOCATION_POPUP_END-->

		<!--HEADER_SECTION_START-->
		<?php include("includes/navigation.php"); ?>
		<!--HEADER_SECTION_END-->

		<!--BANNER_SECTION_START-->
		<?php include("includes/banner.php"); ?>
		<!--BANNER_SECTION_END-->

		<!--CONTENT_SECTION_START-->
		<section id="content_section">
			<div class="home_page">
				<div class="page_width_1480">
                    <?php include_once 'includes/home_sec1.php';?>
					<?php include_once 'includes/home_sec2.php';?>
				</div>
				
				<?php include_once 'includes/baumarkt.php';?>
				
				<?php include_once 'includes/home_brands.php';?>
			</div>
		</section>
		<!--CONTENT_SECTION_END-->

		<!--FOOTER_SECTION_START-->
		
		<?php include("includes/footer.php"); ?>
		<!--FOOTER_SECTION_END-->

	</div>

</body>
<style>
    /* Prevent FOUC */
    .slick-slider { visibility: hidden; }
    .slick-initialized { visibility: visible; }
    /* Optimize transitions */
    .slick-track { will-change: transform; }
    [class*="_slider"] {
        min-height: 100px; /* Adjust based on your smallest slider */
        background: #f5f5f5;
    }
</style>
<script src="js/slick.js"></script>
<script type="text/javascript">
	// Function to initialize each slider type with its specific settings
    function initSlider(el, sliderType) {
    const settings = {
        'banner_slider': {
        lazyLoad: 'ondemand',
        dots: false,
        infinite: true,
        slidesToShow: 1,
        autoplay: true,
        autoplaySpeed: 10000,
        slidesToScroll: 1
        },
        'gerenric_slider': {
        lazyLoad: 'ondemand',
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        dots: false,
        autoplaySpeed: 2000,
        infinite: true,
        responsive: [
            {
            breakpoint: 1024,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
            }
            },
            {
            breakpoint: 650,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
            }
        ]
        },
        'brand_slider': {
        lazyLoad: 'ondemand',
        slidesToShow: 10,
        slidesToScroll: 1,
        autoplay: true,
        dots: false,
        autoplaySpeed: 2000,
        infinite: true,
        responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 6,
                slidesToScroll: 1
            }
            },
            {
            breakpoint: 1024,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
            }
            },
            {
            breakpoint: 650,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
            }
        ]
        },
        'gerenric_slider_mostviewed': {
        lazyLoad: 'ondemand',
        slidesToShow: 10,
        slidesToScroll: 1,
        autoplay: true,
        dots: false,
        autoplaySpeed: 2000,
        infinite: true,
        responsive: [
            {
            breakpoint: 1024,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
            }
            },
            {
            breakpoint: 650,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
            }
        ]
        }
    };

    $(el).slick(settings[sliderType]);
    }

    // Set up Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
            const slider = entry.target;
            const sliderType = slider.classList.contains('banner_slider') ? 'banner_slider' :
                                slider.classList.contains('gerenric_slider') ? 'gerenric_slider' :
                                slider.classList.contains('brand_slider') ? 'brand_slider' :
                                'gerenric_slider_mostviewed';
            
            initSlider(slider, sliderType);
            observer.unobserve(slider);
            }
        });
        }, {
            rootMargin: '200px', // Load 200px before entering viewport
            threshold: 0.01
    });

    // Observe all slider containers
    document.querySelectorAll('.banner_slider, .gerenric_slider, .brand_slider, .gerenric_slider_mostviewed').forEach(el => {
        observer.observe(el);
    });

    // Optional: Load first slider immediately if above the fold
    if (document.querySelector('.banner_slider')) {
        initSlider(document.querySelector('.banner_slider'), 'banner_slider');
    }

    // Check if IntersectionObserver is supported
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.banner_slider').forEach(el => initSlider(el, 'banner_slider'));
        document.querySelectorAll('.gerenric_slider').forEach(el => initSlider(el, 'gerenric_slider'));
        document.querySelectorAll('.brand_slider').forEach(el => initSlider(el, 'brand_slider'));
        document.querySelectorAll('.gerenric_slider_mostviewed').forEach(el => initSlider(el, 'gerenric_slider_mostviewed'));
    }
</script>
<?php include("includes/bottom_js.php"); ?>

</html>