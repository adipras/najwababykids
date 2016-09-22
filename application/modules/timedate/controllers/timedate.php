<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Timedate extends MX_Controller
{

	function __construct() {
		parent::__construct();
	}
	
	function get_nice_date($timestamp, $format) {
		switch ($format) {
			case 'full':
				//FULL // Friday 18th of February 2011 at 10:00:00 AM
				$the_date = date('l jS \of F Y \a\t h:i:s A', $timestamp);
				break;
			case 'cool':
				//COOL // Friday 18th of February 2011
				$the_date = date('l jS \of F Y', $timestamp);
				break;
			case 'shorter':
				//SHORTER // 18th of February 2011
				$the_date = date('jS \of F Y', $timestamp);
				break;
			case 'mini':
				//MINI // 18th Feb 2011
				$the_date = date('jS M Y', $timestamp);
				break;
			case 'oldschool':
				//OLDSCHOOL // 18/2/2011
				$the_date = date('j\/n\/y', $timestamp);
				break;
			case 'datepicker':
				//DATEPICKER // 17-08-16
				$the_date = date('d\-m\-y', $timestamp);
				break;
			case 'datepicker_ina':
				//DATEPICKER // 17/08/16
				$the_date = date('d\/m\/y', $timestamp);
				break;
			case 'datepicker_us':
				//DATEPICKER // 2/18/16
				$the_date = date('m\/d\/y', $timestamp);
				break;
			case 'monyear':
				//MONYEAR // August 2016
				$the_date = date('F Y', $timestamp);
				break;
			case 'indodesia':
				//INDONESIA // Rabu, 17 Agustus 2016
				$pattern = array('/Monday/','/Tuesday/','/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/','/January/','/February/','/March/','/April/','/Mey/','/June/','/July/','/August/','/September/','/October/','/November/','/December/');
				$replace = array('Senin', 'Selasa', 'Rabu','Kamis','Jumat','Sabtu','Minggu','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
				$the_date = date('l, j F Y', $timestamp);
				$the_date = preg_replace($pattern, $replace, $the_date);
				break;
			case 'indodesia_short':
				//INDONESIA // Rabu, 17 Agust 2016
				$pattern = array('/Monday/','/Tuesday/','/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/','/Jan/','/Feb/','/Mar/','/Apr/','/Mey/','/Jun/','/Jul/','/Aug/','/Sep/','/Oct/','/Nov/','/Dec/');
				$replace = array('Sen', 'Sel', 'Rab','Kam','Jum','Sab','Min','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agust','Sep','Okt','Nov','Des');
				$the_date = date('l, j M Y', $timestamp);
				$the_date = preg_replace($pattern, $replace, $the_date);
				break;
		}
		return $the_date;
	}

	function make_timestamp_from_datepicker($datepicker) {
		$hour=7;
		$minute=0;
		$second=0;

		$day = substr($datepicker, 0, 2);
		$month = substr($datepicker, 3,2);
		$year = substr($datepicker, 6,4);

		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		return $timestamp;
	}

	function make_timestamp_from_datepicker_us($datepicker) {
		$hour=7;
		$minute=0;
		$second=0;

		$month = substr($datepicker, 0, 2);
		$day = substr($datepicker, 3,2);
		$year = substr($datepicker, 6,4);

		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		return $timestamp;
	}

	function make_timestamp($day, $month, $year) {
		$hour=7;
		$minute=0;
		$second=0;
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		return $timestamp;
	}
}

