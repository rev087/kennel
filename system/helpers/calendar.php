<?php
	
class calendar {
	var $month;
	var $yearh;
	var $dim;
	var $msw;
	var $events = array();
	var $links = array();
	
	function __construct($month=null, $year=null)
	{
		$this->month = $month | date('n');
		$this->year = $year | date('Y');
		$this->dim = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		$this->msw = jddayofweek(cal_to_jd(CAL_GREGORIAN, $this->month, 1, $this->year));
	}
	
	function addEvent($day, $description, $url)
	{
		$this->events[intval($day)] = $description;
		$this->links[intval($day)] = $url;
	}
	
	function __toString()
	{
		return $this->output();
	}
	
	function output()
	{
		$ret  = '<table class="calendar">';
		$ret .= '<tr>';
		$ret .= '	<th class="cal_D">D</th>';
		$ret .= '	<th class="cal_S">S</th>';
		$ret .= '	<th class="cal_T">T</th>';
		$ret .= '	<th class="cal_Q">Q</th>';
		$ret .= '	<th class="cal_Q">Q</th>';
		$ret .= '	<th class="cal_S">S</th>';
		$ret .= '	<th class="cal_S">S</th>';
		$ret .= '</tr>';
		$ret .= '<tr>';
		$dom=1-$this->msw;
		while($dom<=$this->dim) {
			for($dow=1; $dow<=7; $dow++) {
				$class = '';
				$title = '';
				$link_start = '';
				$link_end = '';
				if($dom>0 && $dom<=$this->dim)  {
					if(isset($this->events[$dom])) {
						$class = " cal_event";
						$title = ' title="&nbsp;'.$this->events[$dom].'&nbsp;"';
						if($this->links[$dom]) {
							$link_start = '<a href="'.$this->links[$dom].'">';
							$link_end = '</a>';
						}
					}
					
					if($dom == intval(date("d")) && $this->month == intval(date("m")) && $this->year == intval(date("Y")))
						$ret .= '<td class="cal_today cal_'.$dom.$class.'"'.$title.'>'.$link_start.$dom.$link_end.'</td>';
					else
						$ret .= '<td class="cal_filled cal_'.$dom.$class.'"'.$title.'>'.$link_start.$dom.$link_end.'</td>';
				} else {
					$ret .= '<td class="cal_empty cal_'.$dom.$class.'"'.$title.'>&nbsp;</td>';
				}
				
				if($dow==7) $ret .= '</tr><tr>';
				
				$dom++;
			}
		}
		$ret .= '</tr></table>';
		return $ret;
	}
}

?>
