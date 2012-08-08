<?php



/**
*
*/


class seven_Orders extends core_Master  
{
	
    /**
     * Поддържани интерфейси
     */
    var $interfaces = 'doc_DocumentIntf';

    /**
     *  Заглавие на таблицата
     */
    var $title = "Поръчки до 100лв.";
    
    
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
    var $canAdd = 'ceo, sales';
    
    
    /**
     *  
     */
    var $canView = 'ceo, sales';
    
    
    /**
     *  
     */
    var $canList = 'ceo, sales';
	
	var $canWrite = 'ceo, sales';
    
    /**
     *  
     */
    var $canDelete = 'admin';
    
   var $loadList = "doc_DocumentPlg, plg_Printing, doc_ActivatePlg, plg_RowTools, seven_Wrapper";
    
    /**
     * Заглавие в единствено число
     */
    var $singleTitle = "Поръчка до 100 лв.";

    
	var $abbr = 'ORD';
	/**
	 * 
	 */
	
    
   /**
     * Нов темплейт за показване
     */
   var $singleLayoutFile = 'orders/tpl/SingleLayoutOrders.shtml';    
	
    
	/**
	 * Описание на модела
	 */
	function description()
	{
		$this->FLD("title", "varchar(128)", 'caption=Поръчка->Заглавие, mandatory');
		$this->FLD("dateorder", "date", 'caption=Поръчка->Дата, mandatory');
		//$this->FLD('company_name', 'key(mvc=crm_Companies,select=name)', 'caption=Поръчка->До,mandatory');
		$this->FLD('from', 'set(office=Офис,agent=Агент,other=Други)', 'caption=Поръчка->От,mandatory');
		//$this->FLD('type', 'set(project=Проект,produce=Изработка,assembly=Монтаж,supply=Доставка)', 'caption=Поръчка->За,mandatory');
		
		$this->FLD('comments', 'text', 'caption=Поръчка->Описание');
		//$this->FLD('amount', 'varchar(30)', 'caption=Детайли за поръчката->Обща сума, mandatory');
		//$this->FLD('advance', 'varchar(30)', 'caption=Детайли за поръчката->Аванс');
		$this->FLD('projectfile', 'fileman_FileType(bucket=OrdersFiles)', 'caption=Поръчка->Работен файл');
		$this->FLD('commentp', 'varchar(250)', 'caption=Поръчка->Коментар към файла');
		
		//$this->FLD("termp", "varchar(30)", 'caption=Срокове->Срок за изработка (в дни), mandatory');
		//$this->FLD("terma", "varchar(30)", 'caption=Срокове->Срок за монтаж (в дни)');
		
		//$this->FLD('aditionalcomments', 'text', 'caption=Допълнителен коментар към Заявката');
		//$this->FLD("sharedUsers", "keylist(mvc=core_users, title=nick)", 'caption=Изпълнители');
				
			
		
		
		//$this->FLD("project", "fileman_FileType(bucket=OffersFiles)", 'caption=Поръчка->Проект');
		//$this->FLD("executor", "keylist(mvc=core_Roles, type=Цех)", 'caption=Изпълнители');

		//$this->setDbUnique('number');
		
	} 
	

 /**
     * Интерфейсен метод на doc_ContragentDataIntf
     * Връща тялото наимей по подразбиране
     
	static function getDefaultEmailBody($id)
    {
        $handle = offer_Offers::getHandle($id);
        
        //Създаваме шаблона
        $tpl = new ET(tr("Моля запознайте се с приложената оферта:") . "\n[#handle#]");
        
        //Заместваме датата в шаблона
        $tpl->append($handle, 'handle');
        
        return $tpl->getContent();
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
	
	
	//Пореден номер на офертата
        $row->numb = seven_Orders::getHandle($rec->id);

        $fromArr = type_Set::toArray($rec->from);
        if ($fromArr['office']) $row->office = tr('Офиса');
        if ($fromArr['agent']) $row->agent = tr('Външен агент');
		if ($fromArr['other']) $row->other = tr('Други');
		
				
		//Извличане атрибутите на фирмата от папката в кошто е създаден документа
		 $folder = doc_Folders::fetch($rec->folderId);
       		
			
				
			//показва връзка към файла с проекта
			$file= fileman_Download::getDownloadUrl($rec->projectfile);
			$row->orderfile= $file;
		
	}
	
    static function on_AfterPrepareEditForm($mvc, &$data)
{
	$today = date("d-m-Y");	

// ...
    $data->form->setDefault('dateorder', $today); 
// ...
}
    function on_AfterSetupMVC($mvc, $res)
    {
        // Кофа за снимки
        $Bucket = cls::get('fileman_Buckets');
        $res .= $Bucket->createBucket('OrdersFiles', 'Файлове от заявки', 'pdf,jpg,jpeg,tif,cdr,cdt', '5GB', 'user', 'every_one');
        
    }
        
}
