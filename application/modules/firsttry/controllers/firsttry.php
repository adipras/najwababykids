<?php
	class Firsttry extends MX_Controller 
	{
		function __construct()
		{
			parent::__construct();
		}

        function hello()
        {
            $this->load->view('admin');
        }
	}