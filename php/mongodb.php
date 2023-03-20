<?php

// create a MongoDB client instance
$client = new MongoDB\Client('mongodb+srv://liyonraja21:Liyon215@cluster0.nj5bn4w.mongodb.net/?retryWrites=true&w=majority');
// select the database and collection
$database = $client->selectDatabase('project');