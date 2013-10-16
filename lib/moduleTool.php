<?php
namespace  OCA\FaceFinder;
/**
 * The class OC_Module_Maneger instal and include the module automatically
 * ownCloud - faceFinder application
 *
 * @author Aaron Messner
 * @copyright 2012 Aaron Messner aaron.messner@stuudent.uibk.ac.at
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.	If not, see <http://www.gnu.org/licenses/>.
 *
 */

use OCP;
class ModuleTool {
	private $title;
	private $itamList;
	
	public function   __construct($title) {
		$this->title=$title;
	}
	
	public function addFixItems(){
		array_push($this->itamList,'<div class="tool_items fix" style=""></div>');
	}
	
	public function addscrollItems(){
		array_push($this->itamList,'<div class="tool_items" style=""></div>');
	}
	
	public function buildModuleTool(){
		$html='<div class="tool '.$this->title.'">';
		$html.=$this->getTitleDiv();
		foreach($this->itemList as $item){
			$html.=$item;
		}
		$html.='</div>';
		return $html;
	}
	
	private function getTitleDiv(){
		$titlediv='<div class="tool_title">'.
						'<i class="icon-white icon-arrow-up"></i>'.
						$this->title.
				'</div>';
		return $titlediv;
	}
	
}

