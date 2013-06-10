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
	<label>Select view:</label>
	<select title="Select view">
		<option value="time">Time</option>
		<!-- button class="time" style=""> Time </button> -->
		</select>
	</span>
</div>
 
 <div id="photoff">
	 <div id="tool_right">
		 <ul class="start"></ul>
	 </div>
	
	 <div id="box">
	  <div id="module">
	    <div id="moduleFildsinner"></div>
	  </div>
	 <div id="photoOverView"></div>
	
	</div>
	
</div>
<div id="photoview">
	<div id="tool_left">
		<div id="photo" ></div>
	</div>
	<div id="tool_righte"></div>
</div>
<div id="duplicate">
<table class="table table-hover">
	<thead>
    	<tr>
        	<th>
        	Similarity
            </th>
            <th>
            	Pfad 1 
            </th>
            <th>
            	Size 2
            </th>
             <th>
            	 Image 1 
            </th>
            <th>
            	 Info
            </th>
            <th>
            	Image 2
            </th>
            <th>
            	Pfad 2
            </th>
            <th>
            	Size 2
            </th>
            
        </tr>
    </thead>
    <tbody id="data"></tbody>
</table>
</div>
<div id="fancybox-tmp" style="display;none;"></div>


