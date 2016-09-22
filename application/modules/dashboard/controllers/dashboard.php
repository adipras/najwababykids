<?php
	class Dashboard extends MX_Controller 
	{
		function __construct() {
			parent::__construct();
		}

        function index() {
            $data['module'] = "dashboard";
            $data['view_file'] = "dashboard";
            echo Modules::run('templates/admin', $data);
        }
    }