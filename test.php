<?php
require_once "./blogapi.php";

// Used to add some test records to the DB
//populate_test_records();

// Used to delete ALL records in the DB 
//delete_all_records();


// Creates test records in the database
function populate_test_records() {
	// Open the database
	$blogdb = new BlogDB();
	
	// Exit if database not opened
	if (!$blogdb) {
		echo $blogdb->lastErrorMsg();
		exit;
	}
	
	// Create test records
	$sql =<<<EOF
      INSERT INTO posts (Title,Body)
      VALUES ('Test Article #1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla in ex vitae velit scelerisque vehicula bibendum nec nulla. Vivamus a pellentesque nisl. Sed convallis consectetur bibendum. Nam at magna dui. Quisque semper, justo eget viverra feugiat, massa tortor posuere orci, sit amet sagittis enim velit laoreet odio. Vestibulum dictum porttitor mauris, quis mattis nisi dapibus eu. Aenean eu rutrum tortor. Cras sed convallis libero, sed viverra risus. Sed scelerisque tincidunt metus, ut finibus nunc maximus vel. Aenean dignissim, lacus eu ullamcorper viverra, mauris ante efficitur quam, sed tincidunt arcu purus nec elit. Donec purus tortor, congue et est id, posuere vehicula lacus. Curabitur purus ligula, mattis sed commodo sit amet, eleifend eget libero. Suspendisse blandit, purus id feugiat varius, metus lacus egestas nulla, ac pretium erat felis vel leo.');

      INSERT INTO posts (Title,Body)
      VALUES ('Test Article #2', 'Aliquam ac orci massa. Pellentesque varius sagittis orci, in egestas mi egestas eget. In ac justo ut nisl ultrices pretium a sit amet lacus. Duis tristique aliquam eros a posuere. Nam non vestibulum erat. Nam id nisl sit amet quam lacinia blandit. Donec sed tortor vitae est suscipit imperdiet id nec nunc. Sed metus risus, porta a dolor a, bibendum feugiat ante. Aliquam et accumsan eros, a rutrum magna.');

      INSERT INTO posts (Title,Body)
      VALUES ('Test Article #3', 'Duis mattis non ipsum at vestibulum. Nunc eget diam accumsan, feugiat dolor vel, egestas justo. Mauris justo libero, venenatis vitae placerat luctus, vestibulum eu mauris. Aliquam non ligula eu leo imperdiet iaculis a nec est. Nam ultricies erat rutrum, viverra odio eu, eleifend lacus. Vestibulum vitae lorem ac enim viverra tincidunt. Nulla varius et elit quis tempor. Phasellus pulvinar feugiat nibh, quis mollis erat posuere ut. Donec vulputate aliquet mi. Morbi efficitur nibh at dolor finibus, suscipit lacinia enim tincidunt. Fusce placerat mollis neque ut gravida. Aenean interdum magna dolor, sit amet aliquam augue tristique ut. Phasellus elit nisi, imperdiet et ipsum quis, porta scelerisque velit. Morbi aliquet ex ac bibendum consequat. Cras consectetur metus justo, eleifend fermentum urna faucibus a.');

      INSERT INTO posts (Title,Body)
      VALUES ('Test Article #4', 'Phasellus aliquet diam vitae enim gravida, in ultricies orci venenatis. In fringilla viverra justo at posuere. Aliquam ac sem sit amet est vestibulum fringilla. Etiam porta nibh libero, ut tempor felis accumsan ut. Nulla venenatis nulla leo, quis ornare massa commodo et. Aliquam id pulvinar libero. Sed non dolor justo. Nunc ut facilisis ex, quis commodo turpis. Etiam commodo est libero, vitae sodales eros ultricies non. Integer suscipit accumsan nulla, quis condimentum risus laoreet vel. Etiam euismod ullamcorper volutpat.');

      INSERT INTO posts (Title,Body)
      VALUES ('Test Article #5', 'Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum suscipit neque nulla, at vehicula felis lobortis quis. Nullam sed tristique nunc, ac fringilla nisl. Vestibulum iaculis posuere lorem et malesuada. Cras finibus velit sed magna sodales imperdiet id a libero. Integer elementum est non mattis congue. Proin ac diam risus. Praesent tincidunt tortor nec magna tincidunt laoreet. Aliquam mollis metus sed nibh pellentesque, quis gravida urna accumsan. Donec non augue ultricies odio elementum aliquet a non massa. Integer malesuada urna lectus, quis laoreet sapien vehicula tincidunt. Proin interdum accumsan nunc, quis mattis ipsum porttitor id.');
EOF;

	$ret = $blogdb->exec($sql);

	if (!$ret) {
		echo $blogdb->lastErrorMsg();
	} else {
		echo "Records created successfully\n";
	}	
	
	// Close the database
	$blogdb->close();	
}

// Deletes all records in the database
function delete_all_records() {
	// Open the database
	$blogdb = new BlogDB();
	
	// Exit if database not opened
	if (!$blogdb) {
		echo $blogdb->lastErrorMsg();
		exit;
	}	
	
	// Delete all records
	$sql =<<<EOF
	  DELETE FROM posts;
EOF;

	$ret = $blogdb->exec($sql);

	if (!$ret){
		echo $blogdb->lastErrorMsg();
	} else {
		echo $blogdb->changes(), "Records deleted successfully\n";
	}	
	
	// Close the database
	$blogdb->close();	
}

?>