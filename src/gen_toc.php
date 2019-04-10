<?php 

$headerData = "
<!DOCTYPE html>
<html lang=\"en\">
<head>
	<title>श्रीशांकरग्रन्थावलिः संपुटः १ - २०</title>
	<meta charset=\"utf-8\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
	<link rel=\"stylesheet\" href=\"../public/css/bootstrap.min.css\">
	<script src=\"../public/js/jquery.min.js\"></script>
	<script src=\"../public/js/bootstrap.min.js\"></script>
	<link rel=\"stylesheet\" href=\"../public/css/font-awesome.min.css\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../public/css/navbar.css\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../public/css/custom.css\">
</head>
<body>
  <nav id=\"mainNavBar\" class=\"navbar navbar-light navbar-expand-lg fixed-top\">
    <div class=\"container-fluid clear-paddings\">
      <p class=\"navbar-text\" id=\"navbarText\">श्रीशांकरग्रन्थावलिः<br><small>संपुटः १ - २०</small></p>
      <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\"><span class=\"navbar-toggler-icon\"></span></button>
      <div class=\"collapse navbar-collapse\" id=\"navbarNav\">
        <ul class=\"navbar-nav nav ml-auto\">
          <li class=\"nav-item\"><a class=\"nav-link\" href=\"../index.html\">Home</a></li>
		  <li class=\"nav-item dropdown\">
			<a class=\"nav-link dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">About</a>
			<div class=\"dropdown-menu\">
			  <a class=\"dropdown-item\" href=\"concludingnote.html\">Concluding Note<br /><small style=\"float: right;\"><em>T. K. Balasubramaniam</em></small><br /></a>
			  <div class=\"dropdown-divider\"></div>
			  <a class=\"dropdown-item\" href=\"superhuman.html\">Superhuman efforts of a savant<br /><small style=\"float: right;\"><em>Prema Nandakumar</em></small><br /></a>
			  <div class=\"dropdown-divider\"></div>
			  <a class=\"dropdown-item\" href=\"views.html\">Views on the Sri Vani Vilas Press</a>
			  <div class=\"dropdown-divider\"></div>
			  <a class=\"dropdown-item\" href=\"hisaim.html\">His (Sri TKB’s) aim</a>
			</div>
		  </li>
          <li class=\"nav-item\"><a class=\"nav-link\" href=\"volumes.html\">Volumes</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
 <div class=\"container\">
	<div class=\"row\">
		<div class=\"col-md-9 gap-above-largeplus\">";



	//vol16 - 2pages missing after page 190 - solved temporarily in xml
	//vol17 - solved temporarily in xml
	$offsets = ["001"=>[55,0], "002"=>[66,280], "003"=>[62,560], "004"=>[26,0], "005"=>[16,0], "006"=>[36,6], "007"=>[24,200], 
				"008"=>[22,0], "009"=>[33,350], "010"=>[14,2], "011"=>[41,0], "012"=>[11,278], "013"=>[26,0], "014"=>[21,0], 
				"015"=>[34,0], "016"=>[13,0], "017"=>[22,0], "018"=>[21,0], "019"=>[29,0],"020"=>[25,304]  ];

	$xml = simplexml_load_file("smt_toc.xml");		
	
	foreach($xml->book as $book){

		$list = "\n<ul>\n";
		$htmlData = $headerData;
		
		$bookDetails = [];
		$bookId = (string) $book[0]["bid"];
		$bookTitle = $book[0]["title"];
		$bookDetails["id"] = $bookId;
		$bookDetails["title"] = $book[0]["title"];
		$bookDetails["toc"] = [];
		
		foreach($book->children() as $sone){
			
			$liElement = "<li>\n";		
			
			$type = (string)$sone->getName();
			$title = (string)$sone["title"];
			$page = (int)$sone["page"];
			
			//~ echo "$bookId -> $page\n";
		
			if($page == 0){
				
				$liElement .= "<a href=\"#\">" . $title . "</a>\n";
			}
			else{
				
				$pageoffset = $page - $offsets[$bookId][1] + $offsets[$bookId][0];
				$liElement .= "<a href=\"../public/Volumes/" . $bookId . ".pdf#page=" . $pageoffset . "&view=fitV\" target=\"_blank\">" . $title . "</a>\n";
			}

			$page = (string)$sone["page"]; 
			$childrens = getChildrens($sone,$bookId,$offsets);
			
			//~ echo $childrens;
			
			if($childrens != "")
				$liElement .= $childrens;
			
			$liElement .= "</li>\n";
			$list .= $liElement;
		}

		$list .= "\n</ul>\n";
		
		$htmlData .= $list . "\n";
		$htmlData .= "\n</div>
		<div class=\"col-md-3 booksCollection image-fix\">
             <a href=\"../public/Volumes/" . $bookId . ".pdf#page=1&view=fitV\"  target=\"_blank\"><img class=\"img-responsive\" src=\"../public/images/covers/" . $bookId . ".jpg\" /><h5 class=\"card-title\">" . preg_replace("/\(/","<br />(",$bookTitle) . "</h5></a>
		</div>	
	</div>
</div>

</body>
</html>\n";
		
		file_put_contents("../html/" . $bookId . ".html",$htmlData);	
}

		

function getChildrens($element,$bookId,$offsets){

	$childrensArray = [];

	$first = 1;
	$list = "";

	foreach($element->children() as $child){
		
		if($first){
			
			$list = "\n<ul>\n";
			$first = 0;
		}
		
		$liElement = "<li>\n";

		$type = (string)$child->getName();
		$title = (string)$child["title"];
		$page = (int)$child["page"];

		//~ echo "$bookId -> $page\n";		

		if($page == 0){
			
			$liElement .= "<a href=\"#\">" . $title . "</a>\n";
		}
		else{
			
			$pageoffset = $page - $offsets[$bookId][1] + $offsets[$bookId][0];
			$liElement .= "<a href=\"../public/Volumes/" . $bookId . ".pdf#page=" . $pageoffset . "&view=fitV\" target=\"_blank\">" . $title . "</a>\n";
		}	
				 
		$childrens = getChildrens($child,$bookId,$offsets);
		//~ echo $childrens;
		
		if($childrens != "")
			$liElement .= $childrens;

		$liElement .= "</li>\n";
		$list .= $liElement;
	}

	if($list != "")
		$list .= "\n</ul>\n";
	
	return 	$list;
	
}

?>

