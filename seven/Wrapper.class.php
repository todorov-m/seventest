<?php



/**
 * Клас 'orders_Wrapper'
 *
 * Поддържа системното меню и табове-те на пакета 'Core'
 *
 *
 * @category  bgerp
 * @package   orders
 * @author    Milen Georgiev <milen@download.bg>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 * @link
 */
class seven_Wrapper extends plg_ProtoWrapper
{
    
    /**
     * Описание на опаковката на пакета 'seven'
     */
    function description()
    {
        Mode::set('pageMenu', 'Седем ЕООД');
         
        $this->TAB('seven_Inquiry', 'Запитвания');
		$this->TAB('seven_Orders', 'Поръчки до 100лв.');
        $this->TAB('seven_Offers', 'Оферти');
        $this->TAB('seven_Jobs', 'Задания');
        $this->TAB('seven_Contracts', 'Договори');
        $this->TAB('seven_Protocols', 'Протоколи');
		

        $this->title = 'Seven';
 
     }
}