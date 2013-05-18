<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class sent_reportsController extends  Zend_Controller_Action {
	
	public function init(){
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		//Cau hinh cho Zend_layout
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));	
		//Load ca thanh phan cau vao trang layout (index.phtml)
		$response = $this->getResponse();		
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();		
		//Lay so dong tren man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";
		//Goi lop Listxml_modList
		Zend_Loader::loadClass('dashboard_modWebMenu');
		//Lay tat ca cac chuyen muc
		$objWebMenu = new dashboard_modWebMenu();
		$arrResul = $objWebMenu->WebMenuGetAll('4',$_SESSION['OWNER_CODE'],'3','1');
		$this->view->arrMenu = $arrResul;	
		$sliidvisit = $this->_request->getParam('sliid','');
		if ($sliidvisit == "" || is_null($sliidvisit) || !isset($sliidvisit)){
			$sliidvisit = Sys_Library::_getCookie("headervisit");
		}else{
			Sys_Library::_createCookie("headervisit",$sliidvisit);
		}
		$this->view->sliidvisit = $sliidvisit;	
		$sleftmenu = $this->_request->getParam('sleftmenu','');
		$this->view->sleftmenu = $sleftmenu;	
		
		Zend_Loader::loadClass('Sent_modSent');		
		Zend_Loader::loadClass('Listxml_modList');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		//Dia chi URL doc file xu ly AJAX
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sent.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js,jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/i18n/jquery.ui.datepicker-vi.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/jquery-ui-1.8.14.custom.min.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','themes/redmond/jquery-ui-1.8.15.custom.css',',','css');
		$this->view->LoadAllFileJsCss = $JSandStyle;
		//Lay tra tri trong Cookie
		$sGetValueInCookie = Sys_Library::_getCookie("showHideMenu");		
		//Neu chua ton tai thi khoi tao
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			Sys_Library::_createCookie("showHideMenu",1);
			Sys_Library::_createCookie("ImageUrlPath",$this->_request->getBaseUrl() . "/public/images/close_left_menu.gif");
			//Mac dinh hien thi menu trai
			$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			//Hien thi anh dong menu trai
			$this->view->ShowHideimageUrlPath = $this->_request->getBaseUrl() . "/public/images/close_left_menu.gif";
		}else{//Da ton tai Cookie
			/*
				Lay gia tri trong Cookie, neu gia tri trong Cookie = 1 thi hien thi menu, truong hop = 0 thi an menu di
			*/
			if ($sGetValueInCookie != 0){
				$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			}else{
				$this->view->hideDisplayMeneLeft = "";// = "" : an menu
			}
			//Lay dia chi anh trong Cookie
			$this->view->ShowHideimageUrlPath = Sys_Library::_getCookie("ImageUrlPath");
		}		
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		$this->view->InforStaff = Sys_Publib_Library::_InforStaff();		
		//Lay lich ngay/thang/nam
		$sysLibUrlPath = $objConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;	
		//Dinh nghia current modul code
		$this->view->currentModulCode = "SENT";
		$this->view->currentModulCodeForLeft = "REPORTS";
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		if ($psshowModalDialog != 1){
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));        
		}				
	}
	/* Index	*/
	public function indexAction(){
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = 'Kết xuất báo cáo';
		// Lay toan bo tham so truyen tu form
		$arrInput = $this->_request->getParams();
		$objFilter = new Zend_Filter();	
		$ojbSysLib = new Sys_Library();
		$objSent = new Sent_modSent();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		//Tao doi tuong XML
		$objXmlLib = new Sys_Publib_Xml();
		//Lay doi tuong dinh nghia bien const
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Lay ra loai VB
		$arrDocType = $objSent->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->search_doc_type_name = $objFunction->doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type_name",0,"",1);															
		//Lay nguoi ky
		$arrSigner = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);
		$this->view->search_textselectbox_signer = $objFunction->doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_SIGNER_POSITION_NAME","hdn_signer_position_name",0,"",1);
		//Lay phong ban
		$this->view->search_idea_unit_name = $objFunction->doc_search_ajax($_SESSION['arr_all_unit'],"id","name","C_IDEA_UNIT_NAME","hdn_idea_unit_name",0);
		//Lay danh sach so VB di
		$arrInputBooks = $objSent->getPropertiesDocument('DM_SO_VAN_BAN_DI');
		$this->view->arrInputBooks = $arrInputBooks;
		//Lay danh sach cac gia tri tuong ung mo ta tieu tri loc */
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;
		//Tao chuoi loc $sFilterXmlTagList + $sFilterXmlValueList
		$sFilterXmlString = $objXmlLib->_xmlGenerateXmlDataString($sFilterXmlTagList,$sFilterXmlValueList);
		// Lay lai ma bao cao
		$sCodeReport = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeReport = $sCodeReport; 
		//LAY CAC GIA TRI REQUEST
		//Nhan bien truyen vao tu form
		$fromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$this->view->fromDate =$ojbSysLib->_ddmmyyyyToYYyymmdd($fromDate);
		$toDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$this->view->toDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($toDate);
		//Lay cac tieu thuc
		$sDocType = $objFilter->filter($arrInput['C_DOC_TYPE']);
		$sSigner  = $objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']);
		$sDepartment = $objFilter->filter($arrInput['C_IDEA_UNIT_NAME']);
		$sTextBook = $objFilter->filter($arrInput['C_TEXT_BOOK']);
		//Lay danh sach cac loai bao cao vb di
		$arrReporList = $objSent->getPropertiesDocument('DM_BAO_CAO_VBDI');
		$this->view->arrReporList = $arrReporList;
		//Hien thi danh sach bao cao ra view
		$this->view->sHtmlRes = $objSent->docSentListReport($arrReporList,$sCodeReport);
	}
	/**
	 * thuc hien in	
	*/
	/* public function printviewAction(){
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$iOwnerId = $_SESSION['OWNER_ID'];
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList;
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		//echo $sExportType;exit;
		$this->view->sExportType = $sExportType; 
			if($sExportType ==''){
				$sExportType = 14;
			}
		$sCodeReport = $this->_request->getParam('hdn_code_report',"");	
		//Nhan bien truyen vao tu form
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_fromdate',""));	
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_todate',""));	
		$sDocType = $this->_request->getParam('hdn_doc_type_name',"");
		$sSigner = $this->_request->getParam('hdn_signer_position_name',"");
		$sUnitName = $this->_request->getParam('hdn_idea_unit_name',"");
		$sTextBook = $this->_request->getParam('hdn_text_book',"");
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;
		// Duong dan file rpt
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);		
		$my_report = str_replace("/", "\\", $path) . "rpt\\sent\\" . $sCodeReport . ".rpt";
		$sName ='';
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);						
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		//- DiscardSavedData - to refresh then read records		
		$creport->ReadRecords();
			//var_dump($arrFilterXmlValue);exit;	
		if($sCodeReport =='VBDI01' or $sCodeReport =='VBDI02' or $sCodeReport =='VBDI03' or $sCodeReport =='VBDI04'){
			$sName = trim($sDocType.$sSigner.$sUnitName.$sTextBook);
			$z = $creport->ParameterFields(1)->SetCurrentValue($sCodeReport);
			$z = $creport->ParameterFields(2)->SetCurrentValue($sName);
			$z = $creport->ParameterFields(3)->SetCurrentValue($dFromDate);
			$z = $creport->ParameterFields(4)->SetCurrentValue($dToDate);
			$z = $creport->ParameterFields(5)->SetCurrentValue((int)$iOwnerId);
			$z = $creport->ParameterFields(6)->SetCurrentValue($this->_request->getParam('hdn_fromdate',""));
			$z = $creport->ParameterFields(7)->SetCurrentValue($this->_request->getParam('hdn_todate',""));
			$z = $creport->ParameterFields(8)->SetCurrentValue($objDocFun->getNameUnitByIdUnitList($_SESSION['OWNER_ID']));
		}elseif ($sCodeReport =='VBDI05'){
			$sName = trim($sSigner.$sTextBook);
			$z = $creport->ParameterFields(1)->SetCurrentValue($sName);
			$z = $creport->ParameterFields(2)->SetCurrentValue($sDocType);
			$z = $creport->ParameterFields(3)->SetCurrentValue($dFromDate);
			$z = $creport->ParameterFields(4)->SetCurrentValue($dToDate);
			$z = $creport->ParameterFields(5)->SetCurrentValue((int)$iOwnerId);
			$z = $creport->ParameterFields(6)->SetCurrentValue($this->_request->getParam('hdn_fromdate',""));
			$z = $creport->ParameterFields(7)->SetCurrentValue($this->_request->getParam('hdn_todate',""));
			$z = $creport->ParameterFields(8)->SetCurrentValue($objDocFun->getNameUnitByIdUnitList($_SESSION['OWNER_ID']));
		}
		// Dinh dang file report ket xuat
		$report_file = 'report.pdf';
		if ($sExportType == 31){
			$report_file = 'report.pdf';
		}
		elseif ($sExportType == 14){
			$report_file = 'report.doc';
		}
		elseif ($sExportType == 29){
			$report_file = 'report.xls';
		}
		 
		// Duong dan file report	
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//echo $my_report_file; exit;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= $sExportType; // Type file
		$creport->Export(false);
		
		// doc file pdf len trinh duyet				
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].':8080/sys-doc-v3/public/'.$report_file;
		$this->view->my_report_file = $my_report_file; 		
		 
	}*/
	public function printviewAction(){
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$iOwnerId = $_SESSION['OWNER_ID'];
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList;
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		//echo $sExportType;exit;
		$this->view->sExportType = $sExportType; 
			if($sExportType ==''){
				$sExportType = 14;
			}
		$sCodeReport = $this->_request->getParam('hdn_code_report',"");	
		//Nhan bien truyen vao tu form
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_fromdate',""));	
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_todate',""));	
		$sDocType = $this->_request->getParam('hdn_doc_type_name',"");
		$sSigner = $this->_request->getParam('hdn_signer_position_name',"");
		$sUnitName = $this->_request->getParam('hdn_idea_unit_name',"");
		$sTextBook = $this->_request->getParam('hdn_text_book',"");
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;
		//Lay file template
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$sTemplateUrl = str_replace("/", "\\", $path) . "templates\\report-template\\sent\\" . $sCodeReport . "_template.htm";
		$v_html_header = $ojbSysLib->_read_file($sTemplateUrl);
		//Tao lop xu ly doi tuong
		$objSent = new Sent_modSent();
		$v_conten = '';
		$v_resul = '';
		if($sCodeReport =='VBDI01' or $sCodeReport =='VBDI02' or $sCodeReport =='VBDI03' or $sCodeReport =='VBDI04'){
			$sName = trim($sDocType.$sSigner.$sUnitName.$sTextBook);
			$arrResul = $objSent->DocSentReportVBDI01($sCodeReport,$iOwnerId,$sName,$dFromDate,$dToDate,$this->_request->getParam('hdn_fromdate',""),$this->_request->getParam('hdn_todate',""),$objDocFun->getNameUnitByIdUnitList($iOwnerId));
			if($sCodeReport =='VBDI01'){
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_DOC_TYPE']){
						$v_tr_group = $arrResul[$index]['C_DOC_TYPE'];
						$v_conten = $v_conten.'<tr><td colspan="8" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_DOC_TYPE'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
			if($sCodeReport =='VBDI02'){
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_SIGNER_POSITION_NAME']){
						$v_tr_group = $arrResul[$index]['C_SIGNER_POSITION_NAME'];
						$v_conten = $v_conten.'<tr><td colspan="8" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
			if($sCodeReport =='VBDI03'){
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_UNIT_NAME']){
						$v_tr_group = $arrResul[$index]['C_UNIT_NAME'];
						$v_conten = $v_conten.'<tr><td colspan="8" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_UNIT_NAME'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
			if($sCodeReport =='VBDI04'){
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_resul = str_replace("#BOOK_NAME#",$arrResul[0]['C_TEXT_BOOK'],$v_resul);
				for($index = 0;$index < sizeof($arrResul);$index++){
					$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';	
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
		}elseif ($sCodeReport =='VBDI05'){
			
			$sName = trim($sSigner.$sTextBook);
			$arrResul = $objSent->DocSentReportVBDI05($sName,$iOwnerId,$sDocType,$dFromDate,$dToDate,$this->_request->getParam('hdn_fromdate',""),$this->_request->getParam('hdn_todate',""),$objDocFun->getNameUnitByIdUnitList($iOwnerId));
			$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
			$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
			$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
			$v_resul = str_replace("#BOOK_NAME#",$arrResul[0]['C_TEXT_BOOK'],$v_resul);
			$v_tr_group = '';
			for($index = 0;$index < sizeof($arrResul);$index++){
				if($v_tr_group != $arrResul[$index]['C_DOC_TYPE']){
					$v_tr_group = $arrResul[$index]['C_DOC_TYPE'];
					$v_conten = $v_conten.'<tr><td colspan="8" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_DOC_TYPE'].'</b></td></tr>';
					$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';
				}else{
					$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SENT_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM_SYBBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SIGNER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVE_PLACE'].'</td></tr>';	
				}
			}
			$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);	
		}
		//var_dump($arrResul);
		//echo $v_resul; 
		//exit;		 
 		// Dinh dang file report ket xuat
		$report_file = 'report.doc';
		if ($sExportType == 31){
			$report_file = 'report.htm';
		}
		elseif ($sExportType == 14){
			$report_file = 'report.doc';
		}
		$my_report_file = str_replace("/", "\\", $path) . "public\\export\\" . $report_file;
		$ojbSysLib->_write_file($my_report_file,$v_resul);		
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].Sys_Init_Config::_setWebSitePath().'public/export/'.$report_file;
		$this->view->my_report_file = $my_report_file; 	 
	}
}
?>