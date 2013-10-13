<div id="controls">
<div class="crumb ui-droppable" title='/'>
	<a href="<?php echo \OCP\Util::linkToRoute( 'EagleEyeView', array("type"=>'View','dir'=>"%252F"));?>"><img class="svg" src="/owncloud/core/img/places/home.svg"></img></a>
</div>
<?php 
$size=sizeof($_['patharray']);
$path;
if(isset($_['patharray']) && $size>1 )
	$count=0;
	foreach ($_['patharray'] as $a){
	$count++;
	$path.="/".$a;
	if($size-$count===0){
?>
<div class="crumb last svg" title="<?php echo $path?>">
	<a href="<?php echo \OCP\Util::linkToRoute( 'EagleEyeView', array("type"=>'View','dir'=>str_replace("/", "", $path)));?>"><?php echo $a?></a>
</div>
<?php }else{?>
<div class="crumb  svg ui-droppable" title="<?php echo $path?>">
	<a href="<?php echo \OCP\Util::linkToRoute( 'EagleEyeView', array("type"=>'View','dir'=>str_replace("/", "%252F", $path)));?>""><?php echo $a?></a>
</div>
<?php
}

}
?>
	<span class="right">
	<label>Select view:</label>
	<select title="Select view">
		<option value="time">Sort by Time</option>
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
      <tfoot>
    <tr>
      <td colspan="7">
            <div class="pagination">
		    	<ul></ul>
    		</div>
    </td>
    </tr>
  </tfoot>
    <tbody id="data"></tbody>
</table>
</div>
<div id="fancybox-tmp" style="display;none;"><div></div><input type="hidden"></div>
<p title="Mouse over the heading above to view the tooltip." class="tooltip">Mouse over the heading text above to view it's tooltip.</p>


