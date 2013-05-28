<div id="controls">
<div class="crumb ui-droppable" title='/'>
	<a href="<?php echo \OCP\Util::linkTo('facefinder', 'index.php')."?dir=/"?>"><img class="svg" src="/owncloud/core/img/places/home.svg"></img></a>
</div>
<?php 
$size=sizeof($_['patharray']);
$path;
if($size>1)
	$count=0;
	foreach ($_['patharray'] as $a){
	$count++;
	$path.="/".$a;
	if($size-$count===0){
?>
<div class="crumb last svg" title="<?php echo $path?>">
	<a href="<?php echo \OCP\Util::linkTo('facefinder', 'index.php')."?dir=".$path?>"><?php echo $a?></a>
</div>
<?php }else{?>
<div class="crumb  svg ui-droppable" title="<?php echo $path?>">
	<a href="<?php echo \OCP\Util::linkTo('facefinder', 'index.php')."?dir=".$path?>""><?php echo $a?></a>
</div>
<?php
}

}
?>
	<span class="right">
		<button class="time" style=""> Time </button>
		<button class="time" style=""> Defect duplicatits </button>
	</span>
</div>
 
 <div id="photoff">
	 <div id="tool_right">
		 <ul class="start"></ul>
	 </div>
	 <div id="photoOverView"></div>
</div>
<div id="photoview">
	<div id="tool_left">
		<div id="photo" ></div>
	</div>
	<div id="tool_righte"></div>
</div>


