<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$standardPage = false;
$itemFile = "/Elementi/Categorie/news.php";

include(tpf("/Elementi/Pagine/page_top.php"));

include(tpf(ElementitemaModel::p("BLOG_TOP")));

echo $fasce;

include(tpf("/Elementi/Pagine/page_bottom.php"));
