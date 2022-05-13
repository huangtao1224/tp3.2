<?php
class downloadAction extends Action {
    public function index(){
		$this->display();
    }

    public function details(){
        $this->display();
    }
	public function download_number()
	{
		$download_id=pg('download_id');
		$download=M('download')->where(array('download_id'=> $download_id))->find();
		M('download')->where(array('download_id'=> $download_id))->save(array('download_number'=>($download['download_number']+1)));
	}
}
