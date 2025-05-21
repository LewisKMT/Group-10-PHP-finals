<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Destinations</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Travelix Project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/destination_styles.css">
<link rel="stylesheet" type="text/css" href="styles/destination_responsive.css">
</head>

<body>



<div class="super_container">
	
	<!-- Header -->

	<header class="header">

		<!-- Main Navigation -->

		<nav class="main_nav">
			<div class="container">
				<div class="row">
					<div class="col main_nav_col d-flex flex-row align-items-center justify-content-start">
						<div class="logo_container">
							<div class="logo"><a href="#"><img src="" alt="">C O M P A S S</a></div>
						</div>
						<div class="main_nav_container ml-auto">
							<ul class="main_nav_list">
								<li class="main_nav_item"><a href="index.php">home</a></li>
								<li class="main_nav_item"><a href="trip.php">Trip Planner</a></li>
								<li class="main_nav_item"><a href="destination.php">Destinations</a></li>
								<li class="main_nav_item"><a href="logs.php">Travel Logs</a></li>
								<li class="main_nav_item"><a href="../logout.php">Sign Out</a></li>
							</ul>
						</div>
						
	</header>

	<div class="menu trans_500">
		<div class="menu_content d-flex flex-column align-items-center justify-content-center text-center">
			<div class="menu_close_container"><div class="menu_close"></div></div>
			<div class="logo menu_logo"><a href="#"><img src="images/logo.png" alt=""></a></div>
			<ul>
				<li class="menu_item"><a href="index.php">home</a></li>
				<li class="menu_item"><a href="trip.php">Trip Planner</a></li>
				<li class="menu_item"><a href="destination.php">Destinations</a></li>
				<li class="menu_item"><a href="logs.php">Travel Logs</a></li>
			</ul>
		</div>
	</div>

	<!-- Home -->

	<div class="home">
		<div class="home_background parallax-window" data-parallax="scroll" data-image-src="images/destination_compass
		.jpg"></div>
		<div class="home_content">
			<div class="home_title">Destinations</div>
		</div>
	</div>

	<!-- Offers/Destination -->

	<div class="offers">

		<!-- Search -->

		<div class="search">
			<div class="search_inner">

				
				<div class="container fill_height no-padding">
					<div class="row fill_height no-margin">
						<div class="col fill_height no-padding">
					
							<!--  Panel -->

							<div class="search_panel active" >
								<div><h3 style="color: white;"><center>Compass works hard to bring you the best possible trips for your rugged lifestyle. Here you'll find our latest travel packages suited for the adventrue spirit.</center></h3></div>
							</div>

						

							
						</div>
					</div>
				</div>	
			</div>	
		</div>

		<!-- Offers -->

		<div class="container">
			<div class="row">
				<div class="col-lg-1 temp_col"></div>
				<div class="col-lg-11">
					
					<!-- Offers Sorting -->
					<div class="offers_sorting_container">
						<ul class="offers_sorting">
							<li>
								<span class="sorting_text">price</span>
								<i class="fa fa-chevron-down"></i>
								<ul>
									<li class="sort_btn" data-isotope-option='{ "sortBy": "original-order" }' data-parent=".price_sorting"><span>show all</span></li>
									<li class="sort_btn" data-isotope-option='{ "sortBy": "price" }' data-parent=".price_sorting"><span>ascending</span></li>
								</ul>
							</li>
							<li>
								<span class="sorting_text">location</span>
								<i class="fa fa-chevron-down"></i>
								<ul>
									<li class="sort_btn" data-isotope-option='{ "sortBy": "original-order" }'><span>default</span></li>
									<li class="sort_btn" data-isotope-option='{ "sortBy": "name" }'><span>alphabetical</span></li>
								</ul>
							</li>
							<li>
								<span class="sorting_text">stars</span>
								<i class="fa fa-chevron-down"></i>
								<ul>
									<li class="filter_btn" data-filter="*"><span>show all</span></li>
									<li class="sort_btn" data-isotope-option='{ "sortBy": "stars" }'><span>ascending</span></li>
									<li class="filter_btn" data-filter=".rating_3"><span>3</span></li>
									<li class="filter_btn" data-filter=".rating_4"><span>4</span></li>
									<li class="filter_btn" data-filter=".rating_5"><span>5</span></li>
								</ul>
							</li>
							
						</ul>
					</div>
				</div>

				<div class="col-lg-12">
					<!-- Offers Grid -->

					<div class="offers_grid">

						<!-- Offers Item -->

						<div class="offers_item rating_4">
							<div class="row">
								<div class="col-lg-1 temp_col"></div>
								<div class="col-lg-3 col-1680-4">
									<div class="offers_image_container">
										
										<div class="offers_image_background" style="background-image:url(images/destination_1.jpg)"></div>
										<div class="offer_name"><a href="surfing.php">Surfing Safari</a></div>
									</div>
								</div>
								<div class="col-lg-8">
									<div class="offers_content">
										<div class="offers_price">$960<span>inlcludes lodging, food and airfare</span></div>
										<div class="rating_r rating_r_4 offers_rating" data-rating="4">
											<i></i>
											<i></i>
											<i></i>
											<i></i>
											<i></i>
										</div>
										<p class="offers_text">	Be ready to go on a moment's notice. We will call you when the surf is pumping and fly you out for five mornings of hurricane inspired summertime swells!</p>
									
										<div class="button book_button"><a href="surfing.php">details<span></span><span></span><span></span></a></div>
										<div class="offer_reviews">
											<div class="offer_reviews_content">
												<div class="offer_reviews_title">very good</div>
												<div class="offer_reviews_subtitle">403 reviews</div>
											</div>
											<div class="offer_reviews_rating text-center">4.2</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>

						<!-- Offers Item -->

						<div class="offers_item rating_3">
							<div class="row">
								<div class="col-lg-1 temp_col"></div>
								<div class="col-lg-3 col-1680-4">
									<div class="offers_image_container">
										
										<div class="offers_image_background" style="background-image:url(images/destination_2.jpg)"></div>
										<div class="offer_name"><a href="biking.php">Bike New Zealand</a></div>
									</div>
								</div>
								<div class="col-lg-8">
									<div class="offers_content">
										<div class="offers_price">$1490<span>inlcludes lodging, food and airfare</span></div>
										<div class="rating_r rating_r_3 offers_rating" data-rating="3">
											<i></i>
											<i></i>
											<i></i>
											<i></i>
											<i></i>
										</div>
										<p class="offers_text">	Beautiful scenery combined with steep inclines and fast rodes allowed for some great cycling. Don't forget the helmet!</p>
									
										<div class="button book_button"><a href="biking.php">Details<span></span><span></span><span></span></a></div>
										<div class="offer_reviews">
											<div class="offer_reviews_content">
												<div class="offer_reviews_title">good</div>
												<div class="offer_reviews_subtitle">403 reviews</div>
											</div>
											<div class="offer_reviews_rating text-center">3.6</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br><br>

						<!-- Offers Item -->

						<div class="offers_item rating_5">
							<div class="row">
								<div class="col-lg-1 temp_col"></div>
								<div class="col-lg-3 col-1680-4">
									<div class="offers_image_container">
										
										<div class="offers_image_background" style="background-image:url(images/destination_3.jpg)"></div>
										<div class="offer_name"><a href="rock.php">Devil's Tower</a></div>
									</div>
								</div>
								<div class="col-lg-8">
									<div class="offers_content">
										<div class="offers_price">$740<span>inlcludes lodging, food and airfare</span></div>
										<div class="rating_r rating_r_5 offers_rating"  data-rating="5">
											<i></i>
											<i></i>
											<i></i>
											<i></i>
											<i></i>
										</div>
										<p class="offers_text">In this three day trip you'll scale the majestic cliffs of beatiful Devil's Tower, Wyoming!</p>
									
										<div class="button book_button"><a href="rock.php">Details<span></span><span></span><span></span></a></div>
										<div class="offer_reviews">
											<div class="offer_reviews_content">
												<div class="offer_reviews_title">exemplary</div>
												<div class="offer_reviews_subtitle">403 reviews</div>
											</div>
											<div class="offer_reviews_rating text-center">5.0</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>

						

						

					</div>
				</div>

			</div>
		</div>		
	</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/Isotope/isotope.pkgd.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/destination_custom.js"></script>

</body>

</html>
