<?php

/**
 * Модел за поръчки във фирма Седем ООД
 */
class seven_Inquiry extends core_Master  
{
	
    /**
     * Поддържани интерфейси
     */
    var $interfaces = 'doc_DocumentIntf';

    
    /**
     *  Заглавие на таблицата
     */
    var $title = "Запитване";
    
    var $listFields = 'id,title,date=Дата, createdBy, createdOn, modifiedBy, modifiedOn';
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
    var $singleTitle = "Запитване";


	/**
	 *  Единично заглавие
	 */
	var $abbr = 'ORS';
	
    
   /**
    * Нов темплейт за показване
    */
    var $singleLayoutFile = 'seven/tpl/SingleLayoutInquiry.shtml';    
	
    
	/**
	 * Описание на модела
	 */
	function description()
	{
		
		$this->FLD('date', 'date', 'caption=Запитване->Дата,mandatory');
		$this->FLD("title", "varchar(128)", 'caption=Запитване->Заглавие, mandatory');
		$this->FLD('comments', 'text', 'caption=Запитване->Пояснение');
		//$this->FLD('company_name', 'key(mvc=crm_Companies,select=name)', 'caption=Поръчка->До,mandatory');
		$this->FLD('from', 'set(office=Офис,agent=Агент,other=Други)', 'caption=Запитване->От,mandatory');
		$this->FLD('type', 'set(project=Проект,produce=Изработка,assembly=Монтаж,supply=Доставка)', 'caption=Запитване->За,mandatory');
		
		
		//$this->FLD('amount', 'varchar(30)', 'caption=Детайли за поръчката->Обща сума, mandatory');
		//$this->FLD('advance', 'varchar(30)', 'caption=Детайли за поръчката->Аванс');
		$this->FLD('projectfile', 'fileman_FileType(bucket=InquiryFiles)', 'caption=Запитване->Работен файл');
		$this->FLD('commentp', 'varchar(250)', 'caption=Запитване->Коментар към файла');
		
		//$this->FLD("termp", "time(uom=days,suggestions=1 ден|2 дена|3 дена)", 'caption=Срокове->Срок за изработка (в дни), mandatory');
		//$this->FLD("terma", "varchar(30)", 'caption=Срокове->Срок за монтаж (в дни)');
		
		//$this->FLD('aditionalcomments', 'text', 'caption=Коментар');
		//$this->FLD("sharedUsers", "keylist(mvc=core_Users,select=nick)", 'caption=Изпълнители');
				
			
		
		//$this->FLD("date", "date", 'caption=Поръчка->Последен срок, mandatory');
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
    
    
    /**
     *
     */
	static function on_AfterRecToVerbal($mvc, &$row, $rec)
    {
		
		//Пореден номер на офертата
        $row->numb = seven_Offers::getHandle($rec->id);
		
		
		
        $fromArr = type_Set::toArray($rec->from);
        if ($fromArr['office']) $row->office = tr('Офиса');
        if ($fromArr['agent']) $row->agent = tr('Външен агент');
		if ($fromArr['other']) $row->other = tr('Други');
		
		 $typeArr = type_Set::toArray($rec->type);
        if ($typeArr['project']) $row->project = tr('Проект');
        if ($typeArr['produce']) $row->produce = tr('Изработка');
		if ($typeArr['assembly']) $row->assembly = tr('Монтаж');
		if ($typeArr['supply']) $row->supply = tr('Доставка');
		
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
    
static function on_AfterPrepareEditForm($mvc, &$data)
{
	$today = date("d-m-Y");	

// ...
    $data->form->setDefault('date', $today); 
// ...
}

    /**
     *
     */
    function on_AfterSetupMVC($mvc, $res)
    {
        // Кофа за снимки
        $Bucket = cls::get('fileman_Buckets');
        $res .= $Bucket->createBucket('InquiryFiles', 'Файлове от Запитване', 'pdf,jpg,jpeg,tif,cdr,cdt', '5GB', 'user', 'every_one');
    }
        
}
