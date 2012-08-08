<?php



/**
 *
 */
class seven_Protocols extends core_Master
{
	
    /**
     * Поддържани интерфейси
     */
    var $interfaces = 'doc_DocumentIntf';

    /**
     *  Заглавие на таблицата
     */
    var $title = "Приемо-предавателен протоколи";
    
    
    /**
     * Права
     */
    var $canRead = 'ceo, sales';
    
    
    /**
     *  
     */
    var $canEdit = 'ceo, sales';
    
    
    /**
     *  
     */
    var $canAdd = 'ceo, sales ';
    
    
    /**
     *  
     */
    var $canView = 'ceo, sales ';
    
    
    /**
     *  
     */
    var $canList = 'ceo, sales ';
    
    
    /**
     *  
     */
    var $canDelete = 'admin';

    
    var $loadList = "doc_DocumentPlg, plg_Printing, doc_ActivatePlg, plg_RowTools, seven_Wrapper";
    
    
    /**
     * Заглавие в единствено число
     */
    var $singleTitle = "Приемо-предавателен протокол";

    
	var $abbr = 'PRS';
	

    /**
	 * 
	 */
	var $canWrite = 'ceo, sales';
    

    /**
     * Нов темплейт за показване
     */
    var $singleLayoutFile = 'seven/tpl/SingleLayoutProtocols.shtml';    
	
    
	/**
	 * Описание на модела
	 */
	function description()
	{
		$this->FLD("today", "date", 'caption=Днес , mandatory');
		$this->FLD("place", "varchar(128)", 'caption=място, mandatory');
		
		//$this->FLD('company_name', 'key(mvc=crm_Companies,select=name)', 'caption=Фирма,mandatory');
		$this->FLD('activity', 'set(ac1=Предаде и монтира,аc2=Изработи)', 'caption=Действие,mandatory');
		$this->FLD("product", "varchar(128)", 'caption=Продукт, mandatory');
			
	}

    /**
     * @todo Чака за документация...
  */
    function getDocumentRow($id)
    {
        $rec = $this->fetch($id);
        
        $subject = $this->getVerbal($rec, 'id');
        
        $row = new stdClass();
        
        $row->title = $subject;
        
        $row->author = $this->getVerbal($rec, 'createdBy');
        
        $row->authorId = $rec->createdBy;
        
        $row->state = $rec->state;
        
        return $row;
    }
    
	
	static function on_AfterRecToVerbal($mvc, &$row, $rec)
    {
	
        $activityArr = type_Set::toArray($rec->activity);
        if ($activityArr['ac1']) $row->ACC1 = tr('предаде и монтира');
        if ($activityArr['ac2']) $row->ACC2 = tr('изработи');
		
		//Извличане атрибутите на фирмата от папката в кошто е създаден документа
		 $folder = doc_Folders::fetch($rec->folderId);
       		
			
		$company = crm_Companies::fetch($folder->coverId);
			$row->cname = $company->name;
			$row->caddress = $company->address;	
			$row->ceik = $company->vatId;
			
		
	}
    /**
     * Потребителите, с които е споделен този документ
     *
     * @return string keylist(mvc=core_Users)
     * @see doc_DocumentIntf::getShared()
    
    static function getShared($id)
    {
        return static::fetchField($id, 'sharedUsers');
    }
     */
   
        
}
