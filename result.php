<?php session_start(); require_once("ChromePhp.php"); require_once("db.php");
        $db = new db(); $user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <!-- <title>Hello</title> -->
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="index.php">Class Undecided</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="index.php">Home</a></li>
                            <li><a href="about.html">About</a></li>
                            <!-- <li><a href="contact.html">Contact</a></li> -->
                            <!-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li class="nav-header">Nav header</li>
                                    <li><a href="#">Separated link</a></li>
                                    <li><a href="#">One more separated link</a></li>
                                </ul>
                            </li> -->
                        </ul>
                        <form class="navbar-form pull-right" action="http://undecided.web.engr.illinois.edu">
                            <button type="submit" class="btn">Log out</button>
                        </form>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
        		<!-- The COMBINATION itself. -->
        		<h1>
        			<?php
					$firstClass = $_GET[ 'classOne' ];
					$secondClass = $_GET[ 'classTwo' ];
					$thirdClass = $_GET[ 'classThree' ];
					
					//ChromePhp::log(tempOne);
					//ChromePhp::log(tempTwo);
					//ChromePhp::log(tempThree);
					
					// Query the server for any combination of these.
					$courses = array($firstClass, $secondClass, $thirdClass);
					$ids = $db->queryWithCourses($courses);
					
					// Sort the courses.
					sort( $courses );
					
					// Print out the courses in sorted order.
					foreach( $courses as $course )
					{
						echo "$course ";
					}
					
					$_SESSION[ 'firstClass' ] = $firstClass;
					$_SESSION[ 'secondClass' ] = $secondClass;
					$_SESSION[ 'thirdClass' ] = $thirdClass;
					$_SESSION[ 'combo_id' ] =$ids[0];
					
					
					// Print it out.
				//	print_r($ids);
					if(empty($ids))
					{
						$db->addCombination($courses, $user_id);
						$ids = $db->queryWithCourses( $courses );
					}
					
					$reviews = $db->getReview( $ids[ 0 ] );
				?>
        		</h1>
        		
        		<!-- The list of reviews already posted. -->
			<p>
				Reviews:
				<?php
				foreach( $reviews as $review ) { 

					$review_ID = $review[ 'review_id' ]; ?>

					<li><article id="comment_<?php echo($review[ 'review_id' ]); ?>" >
					
						<!-- The text. -->
						<div class="entry-content">
							<p>
								<?php print_r( $review[ 'text' ] ); ?>
							</p>
						</div>
						
						<!-- Content for Delete button. -->
						<form id = "delete<?php echo ($review[ 'review_id' ]); ?>" action="delete_comment.php" method="post" />
							<input type="hidden" name="delete" value="<?php echo htmlspecialchars( $review_ID ); ?>" />
							<input type="submit" value="Delete Review">
						</form>	
						<!-- Helpful or not helpful. -->
						<?php echo $db->getHelpfulRating($review[ 'review_id' ])." out of ".$db->getRatingCount($review[ 'review_id' ])." people found this review helpful.";?>
						<a href="like.php?<?php echo htmlspecialchars( $review_ID ); ?>" name="like" value="<?php echo htmlspecialchars( $review_ID ); ?>" ><img src="like.png" alt="UPVOTE!" width="20" height="20"></a>
						<a href="dislike.php?<?php echo htmlspecialchars( $review_ID ); ?>" name="dislike" value="<?php echo htmlspecialchars( $review_ID ); ?>" ><img src="dislike.png" alt="DOWNVOTE!" width="20" height="20"></a>
						
						<hr width ="100%"/>
					</article></li><?php
				} ?>
			</p>
			<!-- Leave a review -->
			<form id="commentForm" action="post_comment.php" >
				<label for="commentBox" class="required">Leave a review:</label>
				<textarea name="commentBox" id="commentBox" name ="c" rows="10" required="required"></textarea>
				<select name="rating" required="required">
				  	<option value="">Rating</option>
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<input type="submit" value="Submit" style="height 50px; width: 100px" />
				</select>
			</form>
		
        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>