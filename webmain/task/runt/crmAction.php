<?php
class crmClassAction extends runtAction
{
	//每天运行
	public function runAction()
	{
		//客户合同到期
		m('flow')->initflow('custract')->custractdaoqi();
		
		//自动放入公海
		m('flow')->initflow('customer')->addgonghai();
		return 'success';
	}
	
}