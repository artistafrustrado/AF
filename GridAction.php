<?php

class AF_GridAction
{
	protected $__actions;

	public function __construct()
	{
		$this->__actions = array();
	}

	public function addAction($column, $object)
	{
		$this->__actions[$column] = $object;
	}


	public function build($sql)
	{
		$buffer = "";

		$db = Zend_Db_Table::getDefaultAdapter(); 
		$linhas = $db->fetchAll($sql);

		if($linhas)
		{
			$buffer = "<table class=\"table table-striped table-bordered table-condensed\">\n<thead>\n";
			foreach($linhas[0] as $chave => $valor)
			{
				$buffer .= "\t<td>{$chave}</td>\n";
			}
			$buffer .= "</thead>\n<tbody>\n";


			foreach($linhas as $linha)
			{
				$buffer .= "<tr>\n";
				foreach($linha as $key => $value)
				{
					if(array_key_exists($key, $this->__actions))
					{
						$act = str_replace('{[KEY]}', $value, $this->__actions[$key]->build());
						$buffer .= "<td style='width: 85px;'>{$act}</td>\n";
					}
					else
					{
						$buffer .= "\t<td>{$value}</td>\n";
					}
				}
				$buffer .= "</tr>\n";
			}
			$buffer .= "</tbody>\n";
			$buffer .= "</table>\n";
		}

		return $buffer;
	}
}

