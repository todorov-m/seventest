<?php



/**
*
*/


class seven_Jobs extends core_Master  
{
	
    /**
     * Поддържани интерфейси
     */
    var $interfaces = 'doc_DocumentIntf, doc_ContragentDataIntf';

    /**
     *  Заглавие на таблицата
     */
    var $title = "Задания";
    
     var $listFields = 'id,title,sharedUsers, createdBy, createdOn, modifiedBy, modifiedOn';
    /**
     * Права
     */
    var $canRead = 'ceo, jobs, sales';
    
    
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
    var $canView = 'ceo, jobs, sales';
    
    
    /**
     *  
     */
    var $canList = 'ceo, jobs, sales';
	var $canSingle = 'jobs';
	
	var $canWrite = 'ceo, sales';
    
    /**
     *  
     */
    var $canDelete = 'admin';
    
   var $loadList = "doc_DocumentPlg, plg_Printing, doc_ActivatePlg, plg_RowTools, seven_Wrapper,fileman_Files";
    
    /**
     * Заглавие в единствено число
     */
    var $singleTitle = "Задание";

    
	var $abbr = 'ORD';
	/**
	 * 
	 */
	
    
   /**
     * Нов темплейт за показване
     */
   var $singleLayoutFile = 'seven/tpl/SingleLayoutJobs.shtml';    
	
    
	/**
	 * Описание на модела
	 */
	function description()
	{
		$this->FLD("title", "varchar(128)", 'caption=Заявка->Заглавие, mandatory');
		$this->FLD('from', 'set(office=Офис,agent=Агент,other=Други)', 'caption=Заявка->От,mandatory');
		$this->FLD('type', 'set(project=Проект,produce=Изработка,assembly=Монтаж,supply=Доставка)', 'caption=Заявка->За,mandatory');
		
		$this->FLD('comments', 'text', 'caption=Детайли за Заявката->Пояснение');
		
		//$this->FLD('amount', 'varchar(30)', 'caption=Детайли за поръчката->Обща сума, mandatory');
		//$this->FLD('advance', 'varchar(30)', 'caption=Детайли за поръчката->Аванс');
		$this->FLD('projectfile', 'fileman_FileType(bucket=JobsFiles)', 'caption=Детайли за Заявката->Работен файл');
		$this->FLD('commentp', 'varchar(250)', 'caption=Детайли за Заявката->Коментар към файла');
		
		$this->FLD("termp", "varchar(30)", 'caption=Срокове->Срок за изработка (в дни), mandatory');
		$this->FLD("terma", "varchar(30)", 'caption=Срокове->Срок за монтаж (в дни)');
		
		//$this->FLD('aditionalcomments', 'text', 'caption=Допълнителен коментар към Заявката');
		$this->FLD("sharedUsers", "keylist(mvc=core_Users,select=nick)", 'caption=Изпълнители');
				
			
		
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
    
	static function on_AfterRecToVerbal($mvc, &$row, $rec)
    {
	
	
		//Пореден номер на офертата
        $row->numb = seven_Jobs::getHandle($rec->id);
		
        $fromArr = type_Set::toArray($rec->from);
        if ($fromArr['office']) $row->office = tr('Офиса');
        if ($fromArr['agent']) $row->agent = tr('Външен агент');
		if ($fromArr['other']) $row->other = tr('Други');
		
		 $typeArr = type_Set::toArray($rec->type);
        if ($typeArr['project']) $row->project = tr('Проект');
        if ($typeArr['produce']) $row->produce = tr('Изработка');
		if ($typeArr['assembly']) $row->assembly = tr('Монтаж');
		if ($typeArr['supply']) $row->supply = tr('Доставка');
		
				
			//показва връзка към файла с проекта
			$file= fileman_Download::getDownloadUrl($rec->projectfile);
			$row->orderfile= $file;
			
			
			//Зареждаме нужните променливи 
        $originId = $rec->originId;
        $threadId = $rec->threadId;
        $folderId = $rec->folderId;
       
        
        // Определяме треда от originId
        if($originId && !$threadId) {
            $threadId = doc_Containers::fetchField($originId, 'threadId');
        }
        
        //Определяме папката от треда
        if($threadId && !$folderId) {
            $folderId = doc_Threads::fetchField($threadId, 'folderId');
        }
        
        if ($threadId) {
            //Взема данните на получателя от треда
            $contragentData = doc_Threads::getContragentData($threadId);
        }
        
        //Ако създаваме нов тред, определяме данните на контрагента от ковъра на папката
        if (!$threadId && $folderId) {
            $contragentData = doc_Folders::getContragentData($folderId);
        }
        
        //Ако сме открили някакви данни за контрагента
        if ($contragentData) {
            
            //Заместваме данните в полетата с техните стойности. Първо се заместват данните за потребителя
            $row->cname = tr($contragentData->company);
            $row->place     = tr($contragentData->place);
            
            //Адрес. Прави опит да вземе адреса на компанията. Ако няма тогава взема персоналния.
            $row->caddress = tr($contragentData->address ? $contragentData->address : $contragentData->pAddress);
            
            //Имейл. Прави опит да вземе имейл-а на компанията. Ако няма тогава взема персоналния.
            $row->email = $contragentData->email;
            //$row->pemail = $contragentData->id;
                          
               
        }
              
			
			
		
	}
	
    /**
     * Потребителите, с които е споделен този документ
     *
     * @return string keylist(mvc=core_Users)
     * @see doc_DocumentIntf::getShared()
     */
     static function getShared($id)
    {
        return static::fetchField($id, 'sharedUsers');
    }
	
	
    function on_AfterSetupMVC($mvc, $res)
    {
        // Кофа за снимки
        $Bucket = cls::get('fileman_Buckets');
        $res .= $Bucket->createBucket('JobsFiles', 'Файлове от Задание', 'pdf,jpg,jpeg,tif,cdr,cdt', '5GB', 'user', 'every_one');
        
    }
        
}
