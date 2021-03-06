<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EcommerceMyAdmin
//
// EcommerceMyAdmin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EcommerceMyAdmin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EcommerceMyAdmin.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class HierarchicalModel extends GenericModel {

	public $controller = "";
	
	public $strings = null;
	public $lId = 0;
	public $titleFieldName = "title";
	public $aliaseFieldName = "alias";
	public $orderType = "ASC";
	public $idOrderRebuild = "id_order";
	
	public $section = null; //define the section node
	public $sLeft = null; //lft of the section node
	public $sRight = null; //rgt of the section node
	public $sId = null; //id of the section node
	public $sIdParent = null; //id of the section parent
	public $rootSectionName = "-- root --";
	
	public $rootTitle = "-- root --";
	
	public static $controllerName = "";
	public static $actionName = "";
	public static $viewStatus = "";
	public static $orderWhere = array();
	
	public static $currentRecord = null;
	
	public function __construct() {
		
		$this->rootSectionName = $this->rootTitle  = Parametri::$hierarchicalRootTitle;
		
		$this->strings = Factory_Strings::generic(Params::$language);
		
		parent::__construct();
		
		if (isset($this->section))
		{
			$this->getSectionElements();
		}
		
		$this->getRootTitle();
	}
	
	//get the title of the root node
	public function getRootTitle()
	{
		$res = $this->clear()->where(array($this->_idFields => 1))->send();
		
		if (count($res) > 0)
		{
			$this->rootTitle = $res[0][$this->_tables][$this->titleFieldName];
		}
	}
	
	//add the where clause to get only the elements of that section
	public function getSectionWhere()
	{
		if (isset($this->section) )
		{
			if ($this->section !== $this->rootSectionName)
			{
				$this->aWhere(array(
					"gte" => array("lft" => $this->sLeft),
					"lte" => array("-lft" => $this->sRight),
// 					"lft"=>">=".$this->sLeft,"-lft"=>"<=".$this->sRight
				));
			}
			else
			{
				if (count($this->sLeft) > 0)
				{
					for ($i=0;$i<count($this->sLeft);$i++)
					{
						$this->aWhere(array(
							str_repeat("+", $i)."OR" => array(
									"lt" => array("lft" => $this->sLeft[$i]),
									"gt" => array("-lft" => $this->sRight[$i]),
// 									"lft"=>"<".$this->sLeft[$i],
// 									"-lft"=>">".$this->sRight[$i])
									)
								));
					}
				}
			}
		}
	}
	
	public function getChildrenFilterWhere()
	{
		return $this->createWhereClause(0, $this->getChildrenSectionWhere());
	}
	
	//return the where clause to get only the children of the elements of that section
	public function getChildrenSectionWhere()
	{
		$whereArray = array();
		
		if (isset($this->section) )
		{
			if ($this->section !== $this->rootSectionName)
			{
				$whereArray = array(
					"gte" => array("n!".$this->_tables.".lft" => $this->sLeft),
					"lte" => array("-n!".$this->_tables.".lft" => $this->sRight),
// 					"n!".$this->_tables.".lft"=>">=".$this->sLeft,"-n!".$this->_tables.".lft"=>"<=".$this->sRight
				);
			}
			else
			{
				if (count($this->sLeft) > 0)
				{
					$whereArray = array();
					for ($i=0;$i<count($this->sLeft);$i++)
					{
						$whereArray[str_repeat("+", $i)."OR"] = array(
									"lt" => array("n!".$this->_tables.".lft" => $this->sLeft[$i]),
									"gt" => array("-n!".$this->_tables.".lft" => $this->sRight[$i]),
// 									"n!".$this->_tables.".lft"=>"<".$this->sLeft[$i],
// 									"-n!".$this->_tables.".lft"=>">".$this->sRight[$i]);
									);
					}
				}
			}
		}
		return $whereArray;
	}
	
	//get elements of the section none
	public function getSectionElements()
	{
		if ($this->section !== $this->rootSectionName)
		{
			$res = $this->save()->clear()->where(array("section"=>$this->section))->send();
			$this->restore();
			
			if (count($res) > 0)
			{
				$this->sLeft = $res[0][$this->_tables]["lft"];
				$this->sRight = $res[0][$this->_tables]["rgt"];
				$this->sId = $res[0][$this->_tables][$this->_idFields];
				$this->sIdParent = $res[0][$this->_tables]["id_p"];
			}
			else
			{
				$this->section = null;
			}
		}
		else
		{
			$res = $this->save()->clear()->where(array(
				"ne" => array("section" => ""),
				"id_p"=>1
			))->send();
			$this->restore();
			
			if (count($res) > 0)
			{
				foreach ($res as $r)
				{
					$this->sLeft[] = $r[$this->_tables]["lft"];
					$this->sRight[] = $r[$this->_tables]["rgt"];
					$this->sId[] = $r[$this->_tables][$this->_idFields];
					$this->sIdParent[] = $r[$this->_tables]["id_p"];
				}
			}
		}
	}
	
	public static function setControllerName($name)
	{
		self::$controllerName = $name;
	}
	
	public static function setActionName($name)
	{
		self::$actionName = $name;
	}
	
	public static function setViewStatus($status)
	{
		self::$viewStatus = $status;
	}
	
	public static function setOrderWhere($value)
	{
		self::$orderWhere = unserialize($value);
	}
	
	public function pUpdate($id = null, $where = null)
	{
		return parent::update($id, $where);
	}
	
	public function indentNoHtml($id)
	{
		return $this->indent($id, false, false,false);
	}
	
	//get the indentation of the row
	public function indent($id, $alias = true, $editLink = true, $useHtml = true)
	{
		$clean["id"] = (int)$id;
		
		$depth = $this->clear()->depth($clean["id"]);
		$field = isset(self::$currentRecord) ? self::$currentRecord["node"] : $this->clear()->selectId($clean["id"]);
		
		$str = "";
		$strAlias = "";
		for($i = 0;$i < $depth;$i++)
		{
			$str .= $useHtml ? "<span style='padding-right:3px;'>-</span>" : "- ";
			$strAlias .= $useHtml ? "<span style='padding-right:3px;'>&nbsp</span>" : "- ";
		}
		$strAlias = strcmp($strAlias,"") !== 0 ? $strAlias."&nbsp" : "";
		
		$titolo = $editLink ? "<a href='".Url::getRoot().$this->controller."/form/update/".$clean["id"]."'>".$field[$this->titleFieldName]."</a>" : $field[$this->titleFieldName];
		
		if ($alias)
		{
			return $str." ".$titolo." <br />$strAlias<span style='font-size:10px;font-style:italic;'>(alias: ".$field[$this->aliaseFieldName].")</span>";
		}
		return $str." ".$titolo;
	}

	//return the neigthbours of a given record
	//$id: primary key of the record
	public function neighbours($id)
	{
		$clean["id"] = (int)$id;
		$rowData = isset(self::$currentRecord) ? self::$currentRecord["node"] : $this->clear()->selectId($clean["id"]);

		return $this->clear()->where(array("id_p"=>$rowData["id_p"],"ne" => array($this->_idFields => "1"),$this->_idOrder=>$rowData[$this->_idOrder]))->aWhere(self::$orderWhere)->getNeighbours($this->_idOrder);
	}

	//create the HTML of the move up button
	public function arrowUp($id)
	{
		$clean["id"] = (int)$id;
		
		$neighbours = $this->neighbours($clean["id"]);
		
		$index = $this->orderType === "DESC" ? 0 : 1;
		
		if (is_array($neighbours[$index]))
		{
			$protocol = Params::$useHttps ? "https" : "http";
			
			return '<form class="listItemForm" method="POST" action="'.$protocol.'://'.DOMAIN_NAME.'/'.self::$controllerName.'/'.self::$actionName.self::$viewStatus.'"><input type="image" value="'.$this->strings->gtext('up').'" src="'.$protocol.'://'.DOMAIN_NAME.'/Public/Img/Icons/elementary_2_5/up.png" title="'.$this->strings->gtext('move up').'"><input type="hidden" value="'.$this->strings->gtext('up').'" name="moveupAction"><input type="hidden" value="'.$clean["id"].'" name="'.$this->_idFields.'"></form>';
		}
	}
		
	//create the HTML of the move down button
	public function arrowDown($id)
	{
		$clean["id"] = (int)$id;
		
		$neighbours = $this->neighbours($clean["id"]);
		
		$index = $this->orderType === "DESC" ? 1 : 0;
		
		if (is_array($neighbours[$index]))
		{
			$protocol = Params::$useHttps ? "https" : "http";
			
			return '<form class="listItemForm" method="POST" action="'.$protocol.'://'.DOMAIN_NAME.'/'.self::$controllerName.'/'.self::$actionName.self::$viewStatus.'"><input type="image" value="'.$this->strings->gtext('down').'" src="'.$protocol.'://'.DOMAIN_NAME.'/Public/Img/Icons/elementary_2_5/down.png" title="'.$this->strings->gtext('move down').'"><input type="hidden" value="'.$this->strings->gtext('down').'" name="movedownAction"><input type="hidden" value="'.$clean["id"].'" name="'.$this->_idFields.'"></form>';
		}
	}
	
	public function callRebuildTree()
	{
		$res = $this->clear()->where(array("id_p"=>"0"))->send();

		if (count($res) > 0)
		{
			$this->rebuildTree($res[0][$this->_tables][$this->_idFields],1);
		}
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
// 		$clean["id_p"] = (int)$this->values["id_p"];
		
		$children = $this->children($clean["id"],true);
		
		if (isset($this->values["id_p"]) and in_array((int)$this->values["id_p"],$children))
		{
			$this->result = false;
			$this->notice = "<div class='alert'>Non è possibile scegliere questo padre.</div>\n";
		}
		else
		{
			if ($this->checkAll)
			{
				$this->setAlias($id);
				
// 				if (isset($this->values[$this->aliaseFieldName]))
// 				{
// 					if (strcmp($this->values[$this->aliaseFieldName],"") === 0)
// 					{
// 						$this->values[$this->aliaseFieldName] = sanitizeDb(encodeUrl($this->values[$this->titleFieldName]));
// 					}
// 					
// 					$res = $this->clear()->where(array($this->aliaseFieldName=>$this->values[$this->aliaseFieldName],"ne" => array((string)$this->_idFields => $clean["id"])))->send();
// 				
// 					if (count($res) > 0)
// 					{
// 						$this->values[$this->aliaseFieldName] = $this->values[$this->aliaseFieldName] . "-".generateString(4,"123456789");
// 					}
// 				}
			}
			
			if (parent::update($id, $where))
			{
				$this->callRebuildTree();
			}
		}
	}
	
	// Imposta l'alias della categora controllando che non ci sia un duplicato
	public function setAlias($id)
	{
		$clean["id"] = (int)$id;
		
		if (isset($this->values[$this->aliaseFieldName]))
		{
			if (strcmp($this->values[$this->aliaseFieldName],"") === 0)
			{
				$this->values[$this->aliaseFieldName] = sanitizeDb(encodeUrl($this->values[$this->titleFieldName]));
			}
			
			if ($clean["id"])
				$res = $this->clear()->where(array($this->aliaseFieldName=>$this->values[$this->aliaseFieldName],"ne" => array((string)$this->_idFields => $clean["id"])))->send();
			else
				$res = $this->clear()->where(array($this->aliaseFieldName=>$this->values[$this->aliaseFieldName]))->send();
		
			if (count($res) > 0)
			{
				$this->values[$this->aliaseFieldName] = $this->values[$this->aliaseFieldName] . "-".generateString(4,"123456789");
			}
			else
			{
// 				$res = $this->query("select alias from contenuti_tradotti where alias = '".$this->values[$this->aliaseFieldName]."'");
// 				
// 				if (count($res) > 0)
// 					$this->values[$this->aliaseFieldName] = $this->values[$this->aliaseFieldName] . "-".generateString(4,"123456789");
			}
		}
		
// 		if (count($res) > 0)
// 		{
// 			$this->values[$this->aliaseFieldName] = $this->values[$this->aliaseFieldName] . "-".generateString(4,"123456789");
// 		}
// 		else
// 		{
// 			$res = $this->query("select alias from contenuti_tradotti where alias = '".$this->values["alias"]."'");
// 			
// 			if (count($res) > 0)
// 				$this->values[$this->aliaseFieldName] = $this->values[$this->aliaseFieldName] . "-".generateString(4,"123456789");
// 		}
	}
	
	public function insert()
	{
		$this->setAlias(0);
		
// 		if (isset($this->values[$this->aliaseFieldName]))
// 		{
// 			if (strcmp($this->values[$this->aliaseFieldName],"") === 0)
// 			{
// 				$this->values[$this->aliaseFieldName] = sanitizeDb(encodeUrl($this->values[$this->titleFieldName]));
// 			}
// 			
// 			$res = $this->clear()->where(array($this->aliaseFieldName=>$this->values[$this->aliaseFieldName]))->send();
// 		
// 			if (count($res) > 0)
// 			{
// 				$this->values[$this->aliaseFieldName] = $this->values[$this->aliaseFieldName] . "-".generateString(4,"123456789");
// 			}
// 		}
		
		if (parent::insert())
		{
			$this->lId = $this->lastId();
		
			$this->callRebuildTree();
		}
		
// 		$this->callRebuildTree();
	}
	
	public function depth($id = null)
	{
		$clean["id"] = (int)$id;
		 
		Params::$whereClauseSymbolArray[] = "between";

		$depth = $this->select("node.*,(COUNT(parent.".$this->_idFields.") - 2) AS depth")->from($this->_tables." AS node")->inner($this->_tables." AS parent")->where(array("n!node.".$this->_idFields=>$clean["id"]))->sWhere(" node.lft between parent.lft AND parent.rgt ")->groupBy("node.".$this->_idFields)->orderBy("node.lft")->send();
		
		if (count($depth) > 0)
		{
			self::$currentRecord = $depth[0];
			
			return $depth[0]["aggregate"]["depth"];
		}
		self::$currentRecord = null;
		return 0;
	}
	
	//get the full tree with the depth of each row
	//$untilDepth: retrieve until depth
	//$id_cat: show root node or not (true or false)
	public function getTreeWithDepth($untilDepth, $id_cat = null, $lingua = null)
	{
		$whereLingua = "";
		
		if (isset($lingua))
			$whereLingua = " (node.lingua='".$lingua."' OR node.lingua='') and ";
		
		$clean["untilDepth"] = (int)$untilDepth;
		$clean["id_cat"] = (int)$id_cat;
		
		if (isset($id_cat))
		{
			$sql = "SELECT node.*, (COUNT(parent.".$this->_idFields.") - (sub_tree.depth + 1)) AS depth FROM ".$this->_tables." AS node, ".$this->_tables." AS parent, ".$this->_tables." AS sub_parent, (SELECT node.".$this->_idFields.", (COUNT(parent.".$this->_idFields.") - 1) AS depth FROM ".$this->_tables." AS node, ".$this->_tables." AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.".$this->_idFields." = ".$clean["id_cat"]." GROUP BY node.".$this->_idFields." ORDER BY node.lft) AS sub_tree WHERE $whereLingua node.lft BETWEEN parent.lft AND parent.rgt AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt AND sub_parent.".$this->_idFields." = sub_tree.".$this->_idFields." GROUP BY node.".$this->_idFields." HAVING depth <= ".$clean["untilDepth"]." ORDER BY node.lft;";
		}
		else
		{
			$sql = "SELECT node.*, (COUNT(parent.".$this->_idFields.") - 1) AS depth FROM ".$this->_tables." AS node, ".$this->_tables." AS parent WHERE $whereLingua node.lft BETWEEN parent.lft AND parent.rgt GROUP BY node.".$this->_idFields." HAVING depth > 0 AND depth <= ".$clean["untilDepth"]." ORDER BY node.lft;";
		}
// 		echo $sql;die();
		
		return $res = $this->query($sql);
	}
	
	public function recursiveTree($id_cat, $depth = 2)
	{
		$tree = array();
		
		$children = $this->clear()->where(array(
			"id_p"	=>	(int)$id_cat,
		))->orderBy("lft")->send(false);
		
		foreach ($children as $c)
		{
			$numeroFigli = $this->clear()->where(array(
				"id_p"	=>	(int)$c["id_c"],
			))->rowNumber();
			
			$c = htmlentitydecodeDeep($c);
			
			$c["url-alias"] = $this->getUrlAlias($c["id_c"]);
			
			if ((int)$numeroFigli === 0 || $depth <= 1)
				$tree[] = htmlentitydecodeDeep($c);
			else
			{
				$c["figli"] = $this->recursiveTree($c["id_c"], $depth-1);
				$tree[] = $c;
			}
		}
		
		return $tree;
	}
	
// 	//get the parents of a node
// 	//$id: primary_key of the node
// 	public function parents($id, $onlyIds = true, $onlyParents = true, $fields = null)
// 	{
// 		$clean["id"] = (int)$id;
// 		
// 		$res = $this->clear()->where(array($this->_idFields=>$clean["id"]))->send();
// 		
// 		if (count($res) > 0)
// 		{
// 			$lft = $res[0][$this->_tables]["lft"];
// 			$rgt = $res[0][$this->_tables]["rgt"];
// 			
// 			if ($onlyParents)
// 			{
// 				//select only the parents
// 				$this->clear()->where(array("lft"=>"<$lft","rgt"=>">$rgt"))->orderBy("lft");
// 			}
// 			else
// 			{
// 				//select the parents and the element
// 				$this->clear()->where(array("lft"=>"<=$lft","rgt"=>">=$rgt"))->orderBy("lft");
// 			}
// 			
// 			if ($onlyIds)
// 			{
// 				$parents = $this->toList($this->_tables.".".$this->_idFields)->send();
// 			}
// 			else
// 			{
// 				$fields = isset($fields) ? $fields : "*";
// 				$parents = $this->select($fields)->send();
// 			}
// 			
// 			return $parents;
// 		}
// 		
// 		return array();
// 	}
	
	//get the parents of a node
	//$id: primary_key of the node
	public function parents($id, $onlyIds = true, $onlyParents = true, $lingua = null)
	{
		$clean["id"] = (int)$id;
		
		$res = $this->clear()->where(array($this->_idFields=>$clean["id"]))->send();
		
		if (count($res) > 0)
		{
			$lft = $res[0][$this->_tables]["lft"];
			$rgt = $res[0][$this->_tables]["rgt"];
			
			if ($onlyParents)
			{
				//select only the parents
				$this->clear()->where(array(
					"lt" => array("lft" => $lft),
					"gt" => array("rgt" => $rgt),
// 					"lft"=>"<$lft","rgt"=>">$rgt"
				))->orderBy("lft");
			}
			else
			{
				//select the parents and the element
				$this->clear()->where(array(
					"lte" => array("lft" => $lft),
					"gte" => array("rgt" => $rgt),
// 					"lft"=>"<=$lft","rgt"=>">=$rgt"
				))->orderBy("lft");
			}
			
			if ($onlyIds)
			{
				$parents = $this->toList($this->_tables.".".$this->_idFields)->send();
			}
			else
			{
				if (!$lingua)
				{
					$parents = $this->select("*")->send();
					
// 					echo $this->getQuery();
// 					die();
				}
				else
				{
					$parents = $this->select($this->_tables.".*,contenuti_tradotti.*")->left("contenuti_tradotti")->on("contenuti_tradotti.id_c = categories.id_c and contenuti_tradotti.lingua = '".sanitizeDb($lingua)."'")->send();
					
// 					echo $this->getQuery();
// 					die();
				}
			}
			
			return $parents;
		}
		
		return array();
	}
	
	public function children($id, $self = false, $onlyIds = true)
	{
		$clean["id"] = (int)$id;
		
		$res = $this->clear()->where(array($this->_idFields=>$clean["id"]))->send();
		
		if (count($res) > 0)
		{
			$lft = $res[0][$this->_tables]["lft"];
			$rgt = $res[0][$this->_tables]["rgt"];
			
			$this->clear()->orderBy("lft");
			
			if ($self)
			{
				$this->aWhere(array(
					"gte" => array("lft" => $lft),
					"lte" => array("-lft" => $rgt),
// 					"lft"=>">=$lft","-lft"=>"<=$rgt"
				));
			}
			else
			{
				$this->aWhere(array(
					"gt" => array("lft"=>$lft),
					"lt" => array("-lft" => $rgt),
// 					"lft"=>">$lft","-lft"=>"<$rgt"
				));
			}
			
			if ($onlyIds)
			{
				$this->toList($this->_tables.".".$this->_idFields);
			}
			
			$children = $this->send();
			
			return $children;
		}
		
		return array();
	}
	
	//show the immediate children of the node
	public function immediateChildren($id)
	{
		$clean["id"] = (int)$id;
		
		$sql = "SELECT ".$this->_tables.".*, (COUNT(parent.".$this->_idFields.") - (sub_tree.depth + 1)) AS depth
		FROM ".$this->_tables." AS ".$this->_tables.",
			".$this->_tables." AS parent,
			".$this->_tables." AS sub_parent,
			(
				SELECT ".$this->_tables.".".$this->_idFields.", (COUNT(parent.".$this->_idFields.") - 1) AS depth
				FROM ".$this->_tables." AS ".$this->_tables.",
				".$this->_tables." AS parent
				WHERE ".$this->_tables.".lft BETWEEN parent.lft AND parent.rgt
				AND ".$this->_tables.".".$this->_idFields." = ".$clean["id"]."
				GROUP BY ".$this->_tables.".".$this->_idFields."
				ORDER BY ".$this->_tables.".lft
			)AS sub_tree
		WHERE ".$this->_tables.".lft BETWEEN parent.lft AND parent.rgt
			AND ".$this->_tables.".lft BETWEEN sub_parent.lft AND sub_parent.rgt
			AND sub_parent.".$this->_idFields." = sub_tree.".$this->_idFields."
		GROUP BY ".$this->_tables.".".$this->_idFields.",sub_tree.".$this->_idFields."
		HAVING depth = 1
		ORDER BY ".$this->_tables.".lft;";
		
		return $res = $this->query($sql);
	}
	
	public function buildSelect($id = null, $showRoot = true, $where = null)
	{
		$res = $this->query("SELECT node.".$this->_idFields.",node.lft,node.rgt,CONCAT( REPEAT('- ', COUNT(parent.".$this->_idFields.") - 2), node.".$this->titleFieldName.") AS name FROM ".$this->_tables." AS node, ".$this->_tables." AS parent WHERE $where node.lft BETWEEN parent.lft AND parent.rgt GROUP BY node.".$this->_idFields." ORDER BY node.lft;");
		
		$children = array();
		if (isset($id))
		{
			$children = $this->children((int)$id,true);
		}
		
		$ret = array();
		foreach ($res as $r)
		{
			if (!in_array($r["node"][$this->_idFields],$children))
			{
				if (!isset($this->section))
				{
					$ret[$r["node"][$this->_idFields]] = $r["aggregate"]["name"];
				}
				else
				{
					if ($this->section !== $this->rootSectionName)
					{
						if ($r["node"]["lft"] >= $this->sLeft and $r["node"]["lft"] <= $this->sRight)
						{
							$ret[$r["node"][$this->_idFields]] = $r["aggregate"]["name"];
						}
					}
					else
					{
						if (count($this->sLeft) > 0)
						{
							$boolArray = array();
							for ($i=0;$i<count($this->sLeft);$i++)
							{
								$boolArray[] = ($r["node"]["lft"] < $this->sLeft[$i] or $r["node"]["lft"] > $this->sRight[$i]);
							}
							if (count(array_filter($boolArray)) == count($boolArray))
							{
								$ret[$r["node"][$this->_idFields]] = $r["aggregate"]["name"];
							}
						}
						else
						{
							$ret[$r["node"][$this->_idFields]] = $r["aggregate"]["name"];
						}
					}
				}
// 				$bool = isset($this->section) ? ($r["node"]["lft"] >= $this->sLeft and $r["node"]["lft"] <= $this->sRight) : true;
// 				if ($bool)
// 				{
// 					$ret[$r["node"][$this->_idFields]] = $r["aggregate"]["name"];
// 				}
			}
		}
		
		if (!$showRoot)
		{
			return array_slice($ret, 1, null, true);
		}
		return $ret;
	}
	
	public function isImmediateChild($idCat, $idParent)
	{
		$clean["idCat"] = (int)$idCat;
		$clean["idParent"] = (int)$idParent;
		
		return $this->clear()->where(array(
			"id_c"	=>	$clean["idCat"],
			"id_p"	=>	$clean["idParent"],
		))->rowNumber();
	}
	
	public function isChild($idCat, $idParent)
	{
		$children = $this->children($idParent, true, true);
		
		return in_array($idCat, $children) ? true : false;
	}
	
	public function hasChildren($id)
	{
		$clean["id"] = (int)$id;
		
		$children = $this->children($clean["id"]);
		
		if (count($children) === 0)
		{
			return false;
		}

		return true;
	}
	
	public function del($id_c = null, $where = null)
	{
		if ($this->hasChildren($id_c))
		{
			$this->result = false;
			$this->notice = "<div class='alert'>Non è possibile eliminare l'elemento perché contiene dei figli. Si prega di eliminare prima i figli.</div>\n";
		}
		else
		{
			parent::del($id_c);
			
			if ($this->queryResult)
			{
				$this->hasDeleted = true;
				$this->callRebuildTree();
			}
		}
	}

	public function moveup($id)
	{
		parent::moveup($id);
		
		$this->callRebuildTree();
	}

	public function movedown($id)
	{
		parent::movedown($id);
		
		$this->callRebuildTree();
	}
	
	public function getIdFromAlias($alias, $lingua = null)
	{
		if (strcmp($alias,"") === 0)
		{
			return 0;
		}
		
		$clean['alias'] = sanitizeAll($alias);
		
		$res = $this->clear()->where(array($this->aliaseFieldName=>$clean['alias']))->toList($this->_idFields)->send();
		
		if (count($res) > 0)
		{
			return $res[0];
		}
		else
		{
			// Cerco la traduzione
			$ct = new ContenutitradottiModel();
			
			$res = $ct->clear()->select("categories.id_c")->inner(array("category"))->where(array("alias"=>$clean['alias']))->toList("categories.id_c");
			
			if ($lingua)
			{
				$ct->aWhere(array(
					"lingua"	=>	sanitizeAll($lingua),
				));
			}
			
			$res = $ct->send();
			
			if (count($res) > 0)
			{
				return $res[0];
			}
		}
		
		return 0;
	}
	
	public function rebuildTree($id_p, $left) {

		$right = $left+1;   

		$result = $this->clear()->where(array("id_p"=>$id_p))->orderBy($this->idOrderRebuild." ".$this->orderType)->toList($this->_idFields)->send();

		foreach ($result as $id_c)
		{
			$right = $this->rebuildTree($id_c, $right);   
		}
		
		$this->values = array(
			"lft" => $left,
			"rgt" => $right,
		);
		$this->pUpdate($id_p);

		return $right+1;   
	}
	
// 	//get the URL of a node
// 	public function getUrlAlias($id)
// 	{
// 		$clean["id"] = (int)$id;
// 		
// 		$parents = $this->parents($clean["id"], false, false, $this->aliaseFieldName);
// 		
// 		//remove the root node
// 		array_shift($parents);
// 		
// 		$urlArray = array();
// 		foreach ($parents as $node)
// 		{
// 			$urlArray[] = $node[$this->_tables][$this->aliaseFieldName];
// 		}
// 		
// 		$ext = Parametri::$useHtmlExtension ? ".html" : null;
// 		
// 		return implode("/",$urlArray).$ext;
// 	}
	
	//get the URL of a node
	public function getUrlAlias($id, $lingua = null)
	{
		$lingua = isset($lingua) ? $lingua : Params::$lang;
		
		$clean["id"] = (int)$id;
		
		$parents = $this->parents($clean["id"], false, false, $lingua);
		
		//remove the root node
		array_shift($parents);
		
		$urlArray = array();
		foreach ($parents as $node)
		{
			if (isset($node["contenuti_tradotti"][$this->aliaseFieldName]) && $node["contenuti_tradotti"][$this->aliaseFieldName])
				$urlArray[] = $node["contenuti_tradotti"][$this->aliaseFieldName];
			else
				$urlArray[] = $node[$this->_tables][$this->aliaseFieldName];
		}
		
		$ext = Parametri::$useHtmlExtension ? ".html" : null;
		
		return implode("/",$urlArray).$ext;
	}
}
