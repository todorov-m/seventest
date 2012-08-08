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
		$this->FLD("today", "date", 'caption=Днес , mandatory');
		$this->FLD("title", "varchar(125)", 'caption=Заглавие');
		//$this->FLD("place", "varchar(125)", 'caption=Място на сключване, mandatory');
		//$this->FLD("contract", "varchar(250)", 'caption=Предмет на договора, mandatory');
		//$this->FLD("amount", "varchar(40)", 'caption=Сума по договора, mandatory');
		//$this->FLD("txtamount", "varchar(200)", 'caption=с думи, mandatory');
		$this->FLD("payment", "varchar(125)", 'caption=Начин на плащане:, mandatory');
		//$this->FLD("paymentact", "varchar(125)", 'caption=Плащане по:, mandatory');
		$this->FLD("time", "time(suggestions=5 дни| 10 дни| 20 дни)", 'caption=Срок за изпълнение, mandatory');
		$this->FLD("warranty", "varchar(30)", 'caption=Гаранция');
	}


 static function on_AfterRecToVerbal($mvc, &$row, $rec)
    {
       
		//Пореден номер на офертата
        $row->numb = seven_Contracts::getHandle($rec->id);

        
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
		
         //$contragentData1 = doc_Folders::getContragentData($rec->folderId);
		 //$contragentD = $contragentData1->fetch();
        //Ако сме открили някакви данни за контрагента
        if ($contragentData) {
            
            //Заместваме данните в полетата с техните стойности. Първо се заместват данните за потребителя
            $row->cname = tr($contragentData->company);
            //$row->pname      = tr($contragentData->name);
           // $rec->country   = tr($contragentData->country);
          // $row->pname     = $contragentD->companyId;
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
            
                           
               
        }
		$row->pname = $rec->originId;
		// Вземаме документа - оригинал , в случая оригиналната оферта
		$quotationDoc = doc_Containers::getDocument($rec->originId);
		// Вземаме записа на офертата
		$quotationRec = $quotationDoc->fetch();
		// Получаваме вербалното представяне на полетата на офертата
		$row->quotation = seven_Offers::recToVerbal($quotationRec);
		
				
		 // Общи суми по позиции
        if($quotationRec->position1) {
        	$amount1 = $quotationRec->amount1;
        	$cost1 = $quotationRec->cost1;
			$cost1 = number_format($cost1, 2, '. ', '');
        	$all1= $amount1 * $cost1;
        	$all= number_format($all1, 2, ', ', '');
        	$row->all1 = $all;
        }
        if($quotationRec->position2) {
        	$amount2 = $quotationRec->amount2;
        	$cost2 = $quotationRec->cost2;
        	$cost2 = number_format($cost2, 2, '. ', '');
        	$all2= $amount2 * $cost2;
        	$all= number_format($all2, 2, ', ', '');
        	$row->all2 = $all;
        }
        if($quotationRec->position3) {
        	$amount3 = $quotationRec->amount3;
        	$cost3 = $quotationRec->cost3;
        	$cost3 = number_format($cost3, 2, '. ', '');
        	$all3= $amount3 * $cost3;
        	$all= number_format($all3, 2, ', ', '');
        	$row->all3 = $all;
        }
        if($quotationRec->position4) {
        	$amount4 = $quotationRec->amount4;
        	$cost4 = $quotationRec->cost4;
        	$cost4 = number_format($cost4, 2, '. ', '');
        	$all4= $amount4 * $cost4;
        	$all= number_format($all4, 2, ', ', '');
        	$row->all4 = $all;
        }
	
	$totalSum = $all1+$all2+$all3+$all4;
	$totalSum = number_format($totalSum, 2, ', ', '');
	$row->totalSum = $totalSum;
	
	  // ДДС
        if ($quotationRec->sdds){
                
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
     * @todo Чака за документация...
  */
    function getDocumentRow($id)
    {
        
		$rec = $this->fetchField($id);
		 $row = new stdClass();
        
       // $row->title = $subject;
        
        $row->author = $this->getVerbal($rec, 'createdBy');
        
        $row->authorId = $rec->createdBy;
        
        $row->state = $rec->state;
		
       // $subject = $this->getVerbal($rec, '$id');
        
       
        
        return $row;
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

    // Вземаме документа - оригинал , в случая оригиналната оферта
		$quotationDoc = doc_Containers::getDocument($data->originId);
		// Вземаме записа на офертата
		$quotationRec = $quotationDoc->fetch();
		$data->form->setDefault('title', $quotationRec->title); 



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
