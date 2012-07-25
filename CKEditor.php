<?php

class AF_CKEditor
{
	
	public function __construct()
	{

	}

	public function setHeaders($view)
	{


                $view->headScript()->appendFile('/lib/ckeditor/ckeditor.js', 'text/javascript');
                $view->headScript()->appendFile('/lib/ckeditor/config.js', 'text/javascript');
                $view->headScript()->appendFile('/lib/ckeditor/adapters/jquery.js', 'text/javascript');
                $view->headScript()->appendFile('/js/ckeditor.js', 'text/javascript');
                #$view->headScript()->appendFile('', 'text/javascript');
		$view->headLink()->appendStylesheet("/lib/ckeditor/contents.css");

	}


}
