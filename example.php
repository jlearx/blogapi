<?php
require_once "./blogapi/blogapi.php";

$displayBlogs = isset($_GET["GetPosts"]) ? true : false;
$postBlog = isset($_POST["PostBlog"]) ? true : false;
$blogs = array();

if ($displayBlogs) {
	// Call endpoint posts
	$response = CallAPI("GET", "http://web.paradisent.com/blogapi/posts");
	$blogs = json_decode($response, true);
} elseif ($postBlog) {
	$title = htmlspecialchars($_POST["txtTitle"]);
	$body = htmlspecialchars($_POST["txtBody"]);
	
	$blogPost = array("Title" => $title, "Body" => $body);
	$data = json_encode($blogPost);
	
	// Call endpoint post
	$response = CallAPI("POST", "http://web.paradisent.com/blogapi/post", $data);
	print_r(json_decode($response, true));
	print("<p>Blog Post Submitted!</p>");
}

$displayBlogs = false;
$postBlog = false;

// Requires php-curl
// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function CallAPI($method, $url, $data = false) {
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}
			
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
			}
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

	
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

?>

<html>
<head>
<title>Blog API Test</title>
</head>
<body>

<div align="center">Get Blog Posts</div>
<form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="get">
<input name="GetPosts" type="submit" value="Get Posts"/>
</form>
<p>&nbsp;</p>

<div id="blogs">
<?php print_r($blogs); ?>
</div>

<p>&nbsp;</p>
<hr />
<p>&nbsp;</p>

<div align="center">Post Blogs</div>
<form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post">
<p><span>Title </span>
<input name="txtTitle" id="txtTitle" type="text" value="" size="50" maxlength="50"/>
</p>
<p>
<span>Body</span>
<textarea name="txtBody" id="txtBody" cols="50" rows="5"></textarea>
</p>
<input name="PostBlog" type="submit" value="Post"/>
</form>
</body>
</html>