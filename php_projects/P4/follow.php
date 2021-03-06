<?php
session_start();// Initialize the session
//this post logout value is from logout button
if(isset($_POST['logout']))
{
    if (!empty($_POST['logout']))
    {
        // Unset all of the session variables
        $_SESSION = array();
        // Destroy the session
        session_destroy();
        // Redirect to login page
        header("location: ./chatter.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>
        <?php
            global $pageName;
            $pageName="Following";//by default is following page
            $pageName= $_GET['title'];//change page title according to the get value
            echo $pageName;
        ?>
    </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body class="container-fluid">
<?php
    include_once './includes/myFunctions2.php';//load functions
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    date_default_timezone_set('America/Halifax');
    global $userId;
    $accountId=$_SESSION["userID"];//the account user's id (logged in )
    $userId=$accountId;//by default, it should be the account user's id
    //get userId by $_GET
    if (isset($_GET['userId'])) {//changed to other user's id if links are clicked
        $userId=$_GET['userId'];
    }


    global $followList;
    //determine to show the following names or follower names according the page name
    if($pageName=='Following')
    {
        $followList =getAllFollowingName($userId);//get followings' name according to the user's id
    }
    else if($pageName=='Followers')
    {
        $followList= getAllFollowerName($userId);//get followers' name according to the user's id
    }
    else
    {
        $followList =getAllFollowingName($userId);//by default, followList is the following name list
    }

    if (isset($_POST['newChat']))//if create a new chat
    {
        $chatContent = $_POST['newChat'];
        if ($chatContent!=null)//record to database only if content is not empty
        {
            myNewChats($chatContent,$accountId);//call the function to write chat to database
        }

    }


    if (isset($_POST['chatContent']))//if create a new chat by chat button
    {
        $chatContent = $_POST['chatContent'];
        if ($chatContent!=null)//write to database only if content is not empty
            $newChatFeedback=myNewChats($chatContent,$accountId);//call the function to write chat to the database
    }

?>
	<!--Start of nav-->
		<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3399CC";>
			<a class="navbar-brand" href="./index.php">
			    <img src="./images/home.png" width="30" height="30" alt="HomeIcon">
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	    	<span class="navbar-toggler-icon"></span>
	  		</button>
		  	<div class="collapse navbar-collapse" id="navbarNav" >  
			    <ul class="navbar-nav mr-auto">
			      <li class="nav-item active"><a class="nav-link" href="./index.php" style="color: white;font-size: 1em;">Home</a></li>
                  <li class="nav-item active"><a class="nav-link" href="./chats.php?userId=<?php echo $accountId ?>" style="color: white;font-size: 1em;">MyChats</a></li>
                </ul>
                <form class="form-inline" action="./search.php" method="get">
                    <img class="navbar-left" src="./images/chatterIcon.png"  width="50" height="50" alt="ChatterIcon">
                    <input class="form-control" type="search" placeholder="Search Chatter" name="search" size="40">
                    <img src="./images/owl.png" width="40" height="40" alt="owl">
                </form>
                <!--chat button-->
                <button class="btn btn-primary" data-toggle="modal" data-target="#chatButton" data-whatever="chat" type="submit">Chat</button>
                <!-- The Modal of the Bootstrap which will pop up when the chat button is clicked-->
                <div class="modal fade" id="chatButton" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header"><!-- Modal Header -->
                                <h5 class="modal-title" id="chatModalLabel">New Chat</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" >
                                <div class="modal-body"><!-- Modal body -->
                                    <div class="form-group">
                                        <textarea class="form-control" id="chatText" name="chatContent" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer"><!-- Modal footer -->
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary"  name="submit" value="submit">Create New Chat</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--End of The Modal-->
		    </div>
		</nav>
	<!--End of nav-->

    <!--Chatter Head-->
    <header>
        <div class="container-fluid" style="background-color: #91C9E8;">
            <div class="row" >
                <div class="media col-sm-4" >
                    <img src="./images/chatterIcon.png" class="rounded mx-auto d-block" style="overflow: auto;" alt="chatter Icon">
                </div>
                <div class="media col-sm-5" >
                    <div class="media-body" style="margin-top: 5%;">
                        <h1 class="blockquote " style="color:white;font-size:3.5em;text-align:center; margin-top: 8%;">...Chatter...</h1>
                        <p  style="color:white;font-size:1.5em;text-align:center;">Chit Chat for all</p>
                    </div>
                </div>
                <div class="media col-sm-1" >
                </div>
                <!--a logout button-->
                <form class="media col-sm-2 justify-content-end" method="post">
                    <button class="btn btn-primary"  type="submit" name="logout" value="logout" style="width: 70px;font-size: small;margin-top: 10px;">Log out</button>
                </form>
            </div>
        </div>
    </header>
    <!--End of Chatter Head-->

	<!--body part-->
	<div class="media">
		<div class="media-body">
			<div class="container-fluid" style="margin-bottom: 20px;">
				<div class="row mb-2">
					<!--Start of left container-->
					<div class="col-sm-3" style="margin-top: 10px;background-color: #E8F8FF;padding-top: 10px;padding-left: 20px;padding-bottom: 10px;">
                        <div class="media" style="background-color: #91C9E8;padding-top: 20px;padding-bottom: 10px;min-width: 128px;">
                            <div class="container-fluid">
                                <!--user profile information starts here-->
                                <div class="media" >
                                    <div class="media-body">
                                        <?php
                                        if ($userId!=$accountId)//change the user's account head img, default is an owl which represents the logged in user's head
                                        {
                                            $head="./images/userHead.png";
                                        }
                                        else
                                            $head="./images/owl.png";
                                        ?>
                                        <img class="rounded-circle img-fluid" src=<?php echo $head; ?> alt="my picture"  style="border:1px solid black;background-color:#3399CC;">
                                    </div>
                                    <div class="media-body" style="margin-top: 5%;">
                                        <h3 class="blockquote text-center" style="text-align:center;margin: 0px;display: inline-block;"><?php printAccountName($userId)?></h3><!--this would change according to the clicked value-->
                                    </div>
                                </div>
                                <!--user profile information ends here-->
                            </div>
                        </div>
                        <!--3 links starts here-->
                        <div class="media">
                            <div class="media-body">
                                <div class="container" style="padding-left: 0px;padding-right: 0px;">
                                    <?php
                                        //functions get number of chats,following,followers
                                        $numChats=chatNum($userId);
                                        $flernum=followerNum($userId);//follower num
                                        $flnum=followingNum($userId);//following num
                                        echo <<<count
                                        <div class="media">
                                            <div class="media-body">
                                                <div class="container" style="padding-left: 0px;padding-right: 0px;">
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="./chats.php?userId=$userId">Chats</a>
                                                            <span class="badge badge-light">$numChats</span><!--number of chats-->
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="./follow.php?title=Following&userId=$userId" id="following" >Following</a>
                                                            <span class="badge badge-light">$flnum</span><!--number of followings-->
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="./follow.php?title=Followers&userId=$userId" id="follower">Followers</a>
                                                            <!--follower's page-->
                                                            <span class="badge badge-light">$flernum</span><!--number of followers-->
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
count;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--3 links ends here-->
                    </div>
                    <!--End of left container-->

					<!--main container-->
					<div class="col-sm" style="background-color: #E8F8FF;margin-top:10px;padding-bottom: 10px;">		
						<div class="container" style="margin-top:10px;margin-bottom: 0px; ">
                            <!--chat pad-->
                            <div class="media" >
                                <form class="form-inline" style="width: 100%;" method="post" >
                                    <img class="align-self-start mr-3" src="./images/owl.png" alt="Owl" style="margin-right: 10px;height: 40px;display:inline-block">
                                    <div class="media-body">
                                        <input class="form-control" type="text" placeholder="Chit Chat..." style="height:40px;width: 100%;" name="newChat" value="" required size="200">
                                    </div>
                                </form>
                            </div>
                            <!--End of chat pad-->

                            <!--Start of list of following-->
                            <div class="container" style="padding-left: 10px;">
                                <div class="list-group" style="background-color: #E8F8FF;">
                                    <!--Following or Followers-->
                                    <h2><?php echo "$pageName" //show Following or Followers ?></h2>
                                    <?php //print all names of followings or followers
                                        foreach ($followList as $v)
                                        {
                                            echo <<<nameList
                                            <!--Start of Name 1-->
                                            <div class="media" style="margin-top: 5px;margin-bottom: 10px;">
                                              <img class="mr-3" src="./images/userHead.png" alt="userHead">
                                              <div class="media-body">
                                                <h3 class="mt-0">
                                                    <a href="./chats.php?userId=$v[0]">$v[1]</a>
                                                </h3>
                                              </div>
                                            </div>
                                            <!--End of Name 1-->
nameList;
                                        }
                                    ?>
                                </div>
                            </div>
                            <!--End of list of following-->
						</div>
					</div>
					<!--End of main container-->
				</div>
			</div>
		</div>		
	</div>
	<!--End of body part-->

	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  	<script type="text/javascript" src="./js/myScript.js"></script>
</body>
<footer>
	
</footer>
</html>