<?php
/**
 * ��Ʒ��������
 *
 */
error_reporting(E_ALL);
ini_set("display_errors",1);
class  Yun_Page_Product  extends Yun_Page_Abstract {
    /**
     * ��֤
     */
    public function validate(ZOL_Request $input, ZOL_Response $output){
		$output->pageType = 'Product';
        $output->permission = array(1);//ָ��Ȩ��
        if (!parent::baseValidate($input, $output)) { return false; }
		return true;
	}

    /**
     * ��������б�
     */
	public function doDefault(ZOL_Request $input, ZOL_Response $output){
		$wArr     = array();#�����ֶ�
		$whereSql = "";
		$page = (int)$input->get('page')<1?1:(int)$input->get('page');
		$output->sername = $wArr['name'] = $input->get('name');
        $output->sercode = $wArr['code'] = $input->get('code');
        $output->sercateId = $wArr['cateId'] = $input->get('cateId');
        
	    if(!empty ($wArr)){
		    foreach($wArr as $k=>$v){
                if($k == 'cateId' && $v){
                    $whereSql .= ' AND cateId ='.$v;
                }elseif($k == 'code' && $v){
                    $whereSql .= ' AND code =\''.$v."'";
                }else{
                    if(gettype($v) == 'string'){
                         $whereSql .= !empty($v)?' AND '.$k.' like binary "%'.$v.'%" ':'';
                      }else{
                         $whereSql .= !empty($v)?' AND '.$k.'='.$v:'';
                    }
                }
		    }
		}
		$pageUrl  = "?c={$output->ctlName}&a={$output->actName}&page={$page}&name={$wArr['name']}&code={$wArr['code']}&cateId={$wArr['cateId']}";
		$pageSize = 30;
		$orderSql = "order by id desc";
		
		$data = Helper_Dao::getList(array(
			'dbName'        => "Db_AndyouYun",  #���ݿ���
			'tblName'       => "product",       #����
			'cols'          => "*",            #����
			'pageSize'      => $pageSize,      #ÿҳ����
			'page'          => $page,          #��ǰҳ
			'pageUrl'       => $pageUrl,       #ҳ��URL����
			'whereSql'      => $whereSql,       #where����
			'orderSql'      => $orderSql,
		    'iswrite'       =>  true,
		    'pageTpl'       =>  9,     #��ҳģ��
		    #'debug'        =>1
		));
        //��÷��������Ŀ������
        $db = Db_AndyouYun::instance();
        $sql = "select sum(stock) sumstock,sum(stock*price) sumprice from product where ctype = 1 {$whereSql}";
		$tmp              = $db->getRow($sql);
        $output->sumstock = $tmp["sumstock"];
        $output->sumprice = $tmp["sumprice"];
        
		if($data){
		    $output->pageBar = $data['pageBar'];
		    $output->allCnt  = $data['allCnt'];
		    $output->data    = $data['data'];
			$output->pageUrl= $pageUrl;
		}
		
        $output->cateInfo = Helper_Yun_Product::getProductCatePairs();
        
        //������е�����
        $output->proCtype = ZOL_Config::get("GLOBAL","PRO_CTYPE");
        
        
		$output->setTemplate('Product');
	}
	
    
    /**
     * ��������б�
     */
	public function doSendTable(ZOL_Request $input, ZOL_Response $output){
        header ("Pragma: no-cache");     
        header ('Content-type: application/x-msexcel;charset=utf-8'); 
        header ("Content-Disposition: attachment; filename=��Ʒ����_".date('Y-m-d H:i:s').".xls" );  
		$wArr     = array();#�����ֶ�
		$whereSql = "";
		$output->sername = $wArr['name'] = $input->get('name');
        $output->sercode = $wArr['code'] = $input->get('code');
        $output->sercateId = $wArr['cateId'] = $input->get('cateId');
        
	    if(!empty ($wArr)){
		    foreach($wArr as $k=>$v){
                if($k == 'cateId' && $v){
                    $whereSql .= ' AND cateId ='.$v;
                }elseif($k == 'code' && $v){
                    $whereSql .= ' AND code =\''.$v."'";
                }else{
                    if(gettype($v) == 'string'){
                         $whereSql .= !empty($v)?' AND '.$k.' like binary "%'.$v.'%" ':'';
                      }else{
                         $whereSql .= !empty($v)?' AND '.$k.'='.$v:'';
                    }
                }
		    }
		}
		$pageSize = 100000;
		$orderSql = "order by id desc";
		
		$data = Helper_Dao::getList(array(
			'dbName'        => "Db_AndyouYun",  #���ݿ���
			'tblName'       => "product",       #����
			'cols'          => "*",            #����
			'pageSize'      => $pageSize,      #ÿҳ����
			'page'          => 1,          #��ǰҳ
			'pageUrl'       => $pageUrl,       #ҳ��URL����
			'whereSql'      => $whereSql,       #where����
			'orderSql'      => $orderSql,
		    'iswrite'       =>  true,
		    'pageTpl'       =>  9,     #��ҳģ��
		    #'debug'        =>1
		));
        
        
		if($data){
		    $output->pageBar = $data['pageBar'];
		    $output->allCnt  = $data['allCnt'];
		    $output->data    = $data['data'];
			$output->pageUrl= $pageUrl;
		}
		
        $output->cateInfo = Helper_Product::getProductCatePairs();
        
        //������е�����
        $output->proCtype = ZOL_Config::get("GLOBAL","PRO_CTYPE");
        
        
		$html = $output->fetchCol('ProductToExcel');
        echo mb_convert_encoding($html, "utf-8","gbk");
        exit;
	}
    /**
     * ���Ӽ�¼
     */
	public function doAddItem(ZOL_Request $input, ZOL_Response $output){
	       
        $Arr = array();
		$Arr['name']    = $input->post('name');
        $Arr['code']    = $input->post('code');
        $Arr['cateId']  = $input->post('cateId');
        $Arr['price']   = $input->post('price');
        $Arr['inPrice'] = $input->post('inPrice');
        $Arr['stock']   = $input->post('stock');
        $Arr['score']   = $input->post('score');
        $Arr['discut']  = $input->post('discut');
        $Arr['canByScore']  = (int)$input->post('canByScore');
        $Arr['addtm']   = SYSTEM_TIME;
        $Arr['ctype']   = (int)$input->post('ctype');
        $Arr['othername']  = $input->post('othername');
        $Arr['num']     = (int)$input->post('num');
        
        //��Ʒ���ۣ������Է�Ϊ��λ�ļ۸�
        $Arr['price']   = $Arr['price']   * 100;
        $Arr['inPrice'] = $Arr['inPrice'] * 100;
        
		$pageUrl = $input->request('pageUrl');
		$data = Helper_Dao::insertItem(array(
		        'addItem'       =>  $Arr, #������
		        'dbName'        =>  'Db_AndyouYun',    #���ݿ���
		        'tblName'       =>  'product',    #����
		));
		/*backUrl*/
        $urlStr = $pageUrl ? $pageUrl : "?c={$output->ctlName}&t={$output->rnd}";
	    echo "<script>document.location='{$urlStr}';</script>";
		exit;
	}
    
    /**
     * ��������
     */
	 public function doUpItem(ZOL_Request $input, ZOL_Response $output){
	    $Arr = array();
	    
	    $input->request('name')?$Arr['name'] = $input->request('name'):'';
        $input->request('code')?$Arr['code'] = $input->request('code'):'';
        $input->request('cateId')?$Arr['cateId'] = $input->request('cateId'):'';
        $input->request('price')!=''?$Arr['price'] = $input->request('price'):'';
        $input->request('inPrice')!=''?$Arr['inPrice'] = $input->request('inPrice'):'';
        $input->request('stock')!=''?$Arr['stock'] = $input->request('stock'):'';
        $input->request('score')!=''?$Arr['score'] = $input->request('score'):'';
        $input->request('discut')!=''?$Arr['discut'] = $input->request('discut'):'';
        $input->request('canByScore')!=''?$Arr['canByScore'] = $input->request('canByScore'):'';
        $input->request('ctype')!=''?$Arr['ctype'] = (int)$input->request('ctype'):1;
        $input->request('othername')!=''?$Arr['othername'] = $input->request('othername'):'';
        $input->request('num')!=''?$Arr['num'] = (int)$input->request('num'): 0;
        
        //��Ʒ���ۣ������Է�Ϊ��λ�ļ۸�
        if(isset($Arr['price']))  $Arr['price']   = $Arr['price']   * 100;
        if(isset($Arr['inPrice']))$Arr['inPrice'] = $Arr['inPrice'] * 100;
        
	    $pageUrl = $input->request('pageUrl');
	    $data = Helper_Dao::updateItem(array(
	            'editItem'       =>  $Arr, #������
	            'dbName'         =>  'Db_AndyouYun',    #���ݿ���
	            'tblName'        =>   'product',    #����
	            'where'          =>   ' id='.$input->request('dataid'), #��������
	    ));
	    /*backUrl*/
        $urlStr = $pageUrl ? $pageUrl : "?c={$output->ctlName}&t={$output->rnd}";
	    echo "<script>document.location='{$urlStr}';</script>";
	    exit;
	 }
    /**
     * ɾ������
     */
	 public function doDelItem(ZOL_Request $input, ZOL_Response $output) {

         /*
		Helper_Dao::delItem(array(
                'dbName'=> 'Db_AndyouYun',#���ݿ���
                'tblName' => 'product',#����
                'where'=> 'id='.$input->post('dataid'),#��������
        ));
		$pageUrl = $input->request('pageUrl');
          * 
          */
		/*backUrl*/
        $urlStr = $pageUrl ? $pageUrl : "?c={$output->ctlName}&t={$output->rnd}";
	    echo "<script>document.location='{$urlStr}';</script>";
		exit;
        
	 }

	
    /**
     * ajax���ָ������
     */
	 public function doAjaxData(ZOL_Request $input, ZOL_Response $output) {
		$id = (int)$input->get('id');
		$arr = Helper_Dao::getRows(array(
		        'dbName'   => "Db_AndyouYun", #���ݿ���
		        'tblName'  => "product", #����
		        'cols'     => "*", #����
		        'whereSql' =>  ' and id='.$id
		));
		$data = ZOL_String::convToU8($arr);
		if(isset($data[0])){
		  echo json_encode($data[0]);
		}
		exit();
	 }
	
}
