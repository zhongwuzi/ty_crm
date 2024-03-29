<?php
class scheduleClassModel extends Model
{
	private $datarows = array();
	
	public function initModel()
	{
		$this->dtobj		= c('date');
	}
	
	public function getmonthdata($uid, $month='')
	{
		if($month=='')$month = date('Y-m');
		$startdt = ''.substr($month,0,7).'-01';
		$enddt 	 = $this->dtobj->getenddt($month);
		return $this->getlistdata($uid, $startdt, $enddt);
	}
	
	/**
	*	读取个人日程
	*/
	public function getlistdata($uid, $startdt, $enddt, $whe='')
	{
		$arr 		= array();
		$this->dtobj= c('date');
		$jg			= $this->dtobj->datediff('d', $startdt, $enddt)+1;
		$where 		= '';
		if($uid>0){
			$whes  = m('admin')->getjoinstr('receid', $uid, 0 , 1);
			$where = 'and ((`uid`='.$uid.') or (1 '.$whes.'))';
		}
		$sql 		= "select id,title,enddt,startdt,rate,rateval,`receid`,`optname`,`uid` from `[Q]schedule` where `status`=1 $where $whe and `startdt`<'$enddt 23:59:59' and (`enddt` is null or `enddt`>'$startdt')";
		$rows 		= $this->db->getall($sql);
		foreach($rows as $k=>$rs){
			$endtime = 2999999999;
			if(!isempt($rs['enddt']))$endtime = strtotime($rs['enddt']);
			$rows[$k]['endtime'] 	= $endtime;
			$rows[$k]['time']  		= explode('-', date('Y-m-d-H-i-s', strtotime($rs['startdt'])));
			$rows[$k]['starttime'] 	= strtotime(substr($rs['startdt'],0,10));
		}
		for($i=0;$i<$jg; $i++){
			if($i==0)$dt= $startdt;
			if($i>0)$dt = $this->dtobj->adddate($dt,'d', 1);
			$dttime = strtotime($dt);
			$dta	= explode('-', $dt);
			$_d 	= (int)$dta[2];
			$nw 	= (int)date('w', $dttime);
			$row 	= array();
			foreach($rows as $k=>$rs){
				$rate = $rs['rate'];
				$uid  = $rs['uid'];
				$ratev= ','.$rs['rateval'].',';
				if($dttime<$rs['starttime'])continue;
				if($rs['endtime']<$dttime)continue;
				$dts  = $rs['time'];
				$time = '';
				if($rate=='d'){
					$time = ''.$dt.' '.$dts[3].':'.$dts[4].':00';
				}else if($rate=='m'){
					if(contain($ratev,','.$_d.',')){
						$time = ''.$dt.' '.$dts[3].':'.$dts[4].':00';
					}
				}else if($rate=='w'){
					if(contain($ratev,','.$nw.',')){
						$time = ''.$dt.' '.$dts[3].':'.$dts[4].':00';
					}
				}else{
					if(contain($rs['startdt'], $dt))$time=$rs['startdt'];
				}
				if($time!=''){
					$barsa = array(
						'id'	=> $rs['id'],
						'title' => $rs['title'],
						'optname'=>$rs['optname'],
						'receid'=>$rs['receid'],
						'week'	=>$this->dtobj->cnweek($dt),
						'time'	=>$time,
						'timea' =>substr($time,11,5)
					);
					$row[] = $barsa;
					if(!isset($this->datarows[$uid]))$this->datarows[$uid] = array();
					$this->datarows[$uid][] = $barsa;
				}
			}
			$arr[$dt] = $row;
		}
		return $arr;
	}
	
	/**
	*	日程提醒发送
	*/
	public function gettododata($dt='')
	{
		if($dt=='')$dt=$this->rock->date;
		$this->datarows = array();
		$this->getlistdata(0, $dt, $dt,'and txsj=1');
		$barr = $this->datarows;
		$time = time();
		$flow = m('flow')->initflow('schedule');
		foreach($barr as $uid=>$rows){
			$str ='';
			$sid 	= 0;
			$GLOBALS['adminid'] = $uid;
			foreach($rows as $k=>$rs){
				$txsj 	= strtotime($rs['time']);
				$jg 	= $txsj-$time;
				if($jg <= 305 && $jg>0){
					$str   		.= ','.$rs['title'];
					$sid 		 = $rs['id'];
					$flow->id  	 = $sid;
					$receid 	 = $uid;
					if(!isempt($rs['receid'])){
						$receid  = 'u'.$receid.','.$rs['receid'].'';
					}
					$flow->push($receid, '', $rs['title'], '日程提醒');
				}
			}
			if($str!=''){
				//$flow->id = $sid;
				//$flow->push($uid, '', substr($str, 1), '日程提醒');
			}
		}
	}
}