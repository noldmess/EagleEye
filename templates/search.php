<?php
if(isset($_GET['search'])&&isset($_GET['tag'])&&(isset($_GET['name']))){
	$seachResult=$_GET['search']::searchArry($_GET['name'],$_GET['tag']);
	$result=count($seachResult);
	$searchbool=true;
}else{
	$searchbool=false;
}
?>
<div id="controls">
<?php
	if($searchbool){
	echo '<h1>Suche nach:'.$_GET['tag'].' ergibt resultate '.$result.'</h1>';
}else{
	echo '<h1>Keine korekte suche </h1>';

}
?>
</div>
<div id="search">
<?php
//<a><img src="'+image.imagstmp+'"  alt="'+image.imagsname+'"></a>


foreach ($seachResult as $img){
	echo '<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?filepath='.$img.'" alt='.$img.'></a>';
	
}
?>
</div>
<div id="photoview">

<div id="tool_left">
<div id="photo">
<div id="photoview_load"></div>
<img alt="" src="">
</div>
<div id="tool_taggs">
<div id="taggs"></div>
<textarea></textarea></div>
</div>
<div id="tool_righte">
<div class="tool">
<div class="tool_title">
<div class="tool_ico"></div>
<h1>Modula A</h1>
</div>
<div class="tool_items">
<p>Kamera:dsfsdfds</p>
<p>Kamera:</p>
<p>Kamera:</p>
<p>Kamera:</p>
</div>

</div>
<div class="tool">
<div class="tool">
<div class="tool_title">
<div class="tool_ico"></div>
<h1>Modula A</h1>
</div>
<div class="tool_items">dsgfsfg
<p>Kamera:dsfsdfds</p>
<p>Kamera:</p>
<p>Kamera:</p>
<p>Kamera:</p>
</div>
</div>
<div class="tool">
<div class="tool">
<div class="tool_title">
<div class="tool_ico"></div><h1>Modula A</h1>
</div>
</div>
<div class="tool">
<div class="tool">
<div class="tool_title">
<div class="tool_ico"></div><h1>Modula A</h1>
</div>
</div>
</div>
</div>