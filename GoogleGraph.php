<?php
class AF_GoogleGraph
{
	protected $_sql = '';
	protected $_title = '';
	protected $_id = '';

	public function __construct()
	{
		$this->_buffer .= ""; 
	}

	public function setQuery($sql)
	{
		$this->_sql = $sql;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function setID($id)
	{
		$this->_id = $id;
	}


	public function addJS($view)
	{
		$view->headScript()->appendFile('https://www.google.com/jsapi', 'text/javascript');
		$view->headScript()->appendFile('/js/af-google-graph.js', 'text/javascript');
	}

	public function build()
	{
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$rows = $db->fetchAll($this->_sql);

		$buffer  = "<script>\n";
		$buffer .= "{$this->_id} = [\n";

		if($rows)
		{
			foreach($rows as $row)
			{
				$buffer .= "\t['{$row['name']}', {$row['value']}],\n";
			}
		}

		$buffer .= "];\n";

		$buffer .= "load_graph_slices('{$this->_id}','{$this->_title}', {$this->_id});\n";
		$buffer .= "</script>\n";

		$buffer .= "<div class=\"col\"><div id=\"{$this->_id}\"></div></div>\n";

		return $buffer;
	}
}
