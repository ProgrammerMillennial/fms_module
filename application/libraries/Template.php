<?php

Class Template {
	
	var $template_data = array();

	function set($name, $value)
	{
		$this->template_data[$name] = $value;
	}

	function load($template = '', $view = '', $view_data = array(), $return = '')
	{
		$this->CI =& get_instance();
		$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
		return $this->CI->load->view($template, $this->template_data, $return);
	}

	function loadSidebar($template = '', $view = '', $view_data = array(), $return = '')
	{
		$this->CI =& get_instance();
		$this->set('sidebar', $this->CI->load->view($view, $view_data, TRUE));
	}

	function loadNavbar($template = '', $view = '', $view_data = array(), $return = '')
	{
		$this->CI =& get_instance();
		$this->set('navbar', $this->CI->load->view($view, $view_data, TRUE));
	}

	function loadHead($template = '', $view = '', $view_data = array(), $return = '')
	{
		$this->CI =& get_instance();
		$this->set('custom_head', $this->CI->load->view($view, $view_data, TRUE));
	}

	function loadFoot($template = '', $view = '', $view_data = array(), $return = '')
	{
		$this->CI =& get_instance();
		$this->set('custom_foot', $this->CI->load->view($view, $view_data, TRUE));
	}

}