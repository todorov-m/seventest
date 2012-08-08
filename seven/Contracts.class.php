<?php



/**
 * 
 */
class seven_Contracts extends core_Master
{
	
    /**
     * Поддържани интерфейси
     */
    var $interfaces = 'doc_DocumentIntf, email_DocumentIntf,doc_ContragentDataIntf';


    /**
     *  Заглавие на документите
     */
    var $title = "Договори";
    

    /**
     * Права
     */
    var $canRead = 'ceo,sales';
    
    
    /**
     *  
     */
    var $canEdit = 'ceo,sales';
    var $canEmail = 'Headquater, sales';
    var $canSend = 'Headquater, sales';
    
    /**
     *  
     */
    var $canAdd = 'ceo,sales';
    
    
    /**
     *  
     */
    var $canView = 'ceo,sales';
    
    
    /**
     *  
     */
    var $canList = 'ceo,sales';
    

    /**
     *  
     */
    var $canDelete = 'admin';
    

    var $loadList = "plg_RowTools, seven_Wrapper, plg_Sorting, doc_DocumentPlg, plg_ExportCsv,
					doc_EmailCreatePlg, doc_ActivatePlg, bgerp_plg_Blank, plg_Printing, plg_State,plg_Search";
   
    /**
     * Заглавие в единствено число
     */
    var $singleTitle = "Договор";

    
	var $abbr = 'MCS';

	/**
	 * 
	 */
	var $canWrite = 'admin,  ';
    
   /**
     * Нов темплейт за показване
     */
    var $singleLayoutFile = 'seven/tpl/SingleLayoutContracts.shtml';    
	
    
	/**
	 * Описание на модела
	 */
	function description()
	{
		$this->FLD('today', 'date', 'caption=Атрибути на Договора->Дата,mandatory');
		$this->FLD("title", "varchar(128)", 'caption=Атрибути на Договора->Заглавие, mandatory');
		$this->FLD("position1", "varchar(128)", 'caption=Позиция 1->Заглавие, mandatory');
		$this->FLD("desc1", "varchar(128)", 'caption=Позиция 1->Описание');
		$this->FLD("amount1", "varchar(30)", 'caption=Позиция 1->Количество, mandatory');
		$this->FLD("cost1", "double(decimals=2)", 'caption=Позиция 1->Цена, mandatory');
		//$this->FLD("project1", "fileman_FileType(bucket=OffersFiles)", 'caption=Позиция 1->Проект');
		$this->FLD("position2", "varchar(128)", 'caption=Позиция 2->Заглавие');
		$this->FLD("desc2", "varchar(128)", 'caption=Позиция 2->Описание');
		$this->FLD("amount2", "varchar(30)", 'caption=Позиция 2->Количество');
		$this->FLD("cost2", "double(decimals=2)", 'caption=Позиция 2->Цена');
		//$this->FLD("project2", "fileman_FileType(bucket=OffersFiles)", 'caption=Позиция 2->Проект');
		$this->FLD("position3", "varchar(128)", 'caption=Позиция 3->Заглавие');
		$this->FLD("desc3", "varchar(128)", 'caption=Позиция 3->Описание');
		$this->FLD("amount3", "varchar(30)", 'caption=Позиция 3->Количество');
		$this->FLD("cost3", "double(decimals=2)", 'caption=Позиция 3->Цена');
		//$this->FLD("project3", "fileman_FileType(bucket=OffersFiles)", 'caption=Позиция 3->Проект');
		$this->FLD("position4", "varchar(128)", 'caption=Позиция 4->Заглавие');
		$this->FLD("desc4", "varchar(128)", 'caption=Позиция 4->Описание');
		$this->FLD("amount4", "varchar(30)", 'caption=Позиция 4->Количество');
		$this->FLD("cost4", "double(decimals=2)", 'caption=Позиция 4->Цена');
		//$this->FLD("project4", "fileman_FileType(bucket=OffersFiles)", 'caption=Позиция 4->Проект');

	//	$this->FLD("amount", "varchar(30)", 'caption=Допълнителна информация->Обща сума (без ДДС)');
		$this->FLD('sdds', 'set(dds1=включи ДДС)', 'caption=Допълнителна информация->ДДС');
	//	$this->FLD("all", "varchar(30)", 'caption=Допълнителна информация->Всичко');
		
		//$this->FLD("advance", "varchar(30)", 'caption=Допълнителна информация->Аванс');
		$this->FLD("term", "time(suggestions=5 дни| 10 дни| 20 дни)", 'caption=Срокове->Изработка');
		$this->FLD("warranty", "varchar(30)", 'caption=Срокове->Гаранция');


		//$this->setDbUnique('number');
	
	} 
	
	
	

    /**
     * Интерфейсен метод на doc_ContragentDataIntf
     * Връща тялото наимей по подразбиране
     */
	static function getDefaultEmailBody($id)
    {
        $handle = self::getHandle($id);
        
        //Създаваме шаблона
        $tpl = new ET(tr("Моля запознайте се с приложения договор:") . "\n#[#handle#]");
        
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
        
        $subject = $this->getVerbal($rec, 'title');
        
        $row = new stdClass();
        
        $row->title = $subject;
        
        $row->author = $this->getVerbal($rec, 'createdBy');
        
        $row->authorId = $rec->createdBy;
        
        $row->state = $rec->state;
        
        return $row;
    }
    
	/**
     * Fancy ефект за картинката и извличане атрибутите на фирмата
     *
     */
	  static function on_AfterRecToVerbal($mvc, &$row, $rec)
    {
       
		//Пореден номер на офертата
        $row->numb = seven_Offers::getHandle($rec->id);

       /* // Fancy ефект за картинката
        $Fancybox = cls::get('fancybox_Fancybox');

        $tArr = array(200, 150);
        $mArr = array(800, 600);

        if($rec->project1) {
            $row->image = $Fancybox->getImage($rec->project1, $tArr, $mArr);
        } 
		if($rec->project2) {
            $row->image2 = $Fancybox->getImage($rec->project2, $tArr, $mArr);
        } 
		if($rec->project3) {
            $row->image3 = $Fancybox->getImage($rec->project3, $tArr, $mArr);
        } 
		if($rec->project4) {
            $row->image4 = $Fancybox->getImage($rec->project4, $tArr, $mArr);
        } 
		*/
		/* Извличане атрибутите на фирмата от папката в кошто е създаден документа
	взема данните за контрагента от треда или от папката ако е начало на тред
	*/

	
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
         $contragentData1 = doc_Folders::getContragentData($folderId);
        //Ако сме открили някакви данни за контрагента
        if ($contragentData) {
            
            //Заместваме данните в полетата с техните стойности. Първо се заместват данните за потребителя
            $row->cname = tr($contragentData->company);
            //$row->pname      = tr($contragentData->name);
           // $rec->country   = tr($contragentData->country);
           $row->pname     = $contragentData1->companyId;
            $row->place     = tr($contragentData->place);
            
            //Телефонен номер. Ако има се взема от компанията, aко няма, от мобилния. В краен случай от персоналния (домашен).
            $row->tel = $contragentData->tel;
            $row->mtel = $contragentData->pMobile;
            
            if (!$row->tel) $row->tel = $contragentData->pTel;
            
            //Факс. Прави опит да вземе факса на компанията. Ако няма тогава взема персоналния.
            $row->fax = $contragentData->fax ? $contragentData->fax : $contragentData->pFax;
            
            //Адрес. Прави опит да вземе адреса на компанията. Ако няма тогава взема персоналния.
            $row->caddress = tr($contragentData->address ? $contragentData->address : $contragentData->pAddress);
            
            //Имейл. Прави опит да вземе имейл-а на компанията. Ако няма тогава взема персоналния.
            $row->email = $contragentData->email;
            //$row->pemail = $contragentData->id;
                           
               
        }
              
        
        // Общи суми по позиции
        if($rec->position1) {
        	$amount1 = $rec->amount1;
        	$cost1 = $rec->cost1;
        	$cost1 = number_format($cost1, 2, '. ', '');
        	$all1= $amount1 * $cost1;
        	$all= number_format($all1, 2, ', ', '');
        	$row->all1 = $all;
        }
        if($rec->position2) {
        	$amount2 = $rec->amount2;
        	$cost2 = $rec->cost2;
        	$cost2 = number_format($cost2, 2, '. ', '');
        	$all2= $amount2 * $cost2;
        	$all= number_format($all2, 2, ', ', '');
        	$row->all2 = $all;
        }
        if($rec->position3) {
        	$amount3 = $rec->amount3;
        	$cost3 = $rec->cost3;
        	$cost3 = number_format($cost3, 2, '. ', '');
        	$all3= $amount3 * $cost3;
        	$all= number_format($all3, 2, ', ', '');
        	$row->all3 = $all;
        }
        if($rec->position4) {
        	$amount4 = $rec->amount4;
        	$cost4 = $rec->cost4;
        	$cost4 = number_format($cost4, 2, '. ', '');
        	$all4= $amount4 * $cost4;
        	$all= number_format($all4, 2, ', ', '');
        	$row->all4 = $all;
        }
	
	$totalSum = $all1+$all2+$all3+$all4;
	$totalSum = number_format($totalSum, 2, ', ', '');
	$row->totalSum = $totalSum;
	
	  // ДДС
        if ($rec->sdds){
                
        $dds = $totalSum * 0.20;
        $dds = number_format($dds, 2, ', ', '');
        $row->dds = $dds;
        
        // totalSumPlusDds
        $totalSumPlusDds = $totalSum * 1.20;
        $totalSumPlusDds = number_format($totalSumPlusDds, 2, ', ', '');
	$row->all = $totalSumPlusDds;
        }
        
       
	}
	

	 /**
     * Дефиниране на стойности по подразбиране
     *
     */
     static function on_AfterPrepareEditForm($mvc, &$data)
{
	$today = date("d-m-Y");	

// ...
   $data->form->setDefault('warranty', 12); 
   $data->form->setDefault('today', $today); 
// ...
}
// Добавяне на бутони към toolbar
 static function on_AfterPrepareSingleToolbar($mvc, &$res, $data)
    {
        if (($data->rec->state != 'draft') && ($data->rec->state != 'rejected')) {
            if (haveRole('crm')) {
               // $data->toolbar->addBtn('Договор', array('seven_Contracts', 'add', 'originId' => $data->rec->originId),'class=btn-add button'); 
                $data->toolbar->addBtn('Задание', array('seven_Jobs', 'add', 'threadId' => $data->rec->containerId), 'class=btn-open button');
                $data->toolbar->addBtn('Протокол', array('seven_Protocols', 'add', 'originId' => $data->rec->originId), 'class=btn-clone button');
            }
            if (haveRole('jobs')) {
                $data->toolbar->addBtn('Протокол', array('seven_Protocols', 'add', 'originId' => $data->rec->originId), 'class=btn-clone button');    
            }
         }
    }



/*
    static function getShared($id)
    {
        return static::fetchField($id, 'sharedUsers');
    }
    */
    function on_AfterSetupMVC($mvc, $res)
    {
        // Кофа за снимки
       // $Bucket = cls::get('fileman_Buckets');
       // $res .= $Bucket->createBucket('OffersFiles', 'Файлове от оферти', 'jpg,jpeg,png,gif', '1GB', 'user', 'every_one');
        
    }
        
}
