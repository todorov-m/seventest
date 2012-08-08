<?php



/**
 * class seven_Setup
 *
 * Инсталиране/Деинсталиране на пакета за фирма Седем ЕООД
 *
 *
 * @category  bgerp
 * @package   cams
 * @author    Milen Georgiev <milen@download.bg>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 */
class seven_Setup
{
    
    /**
     * Версия на пакета
     */
    var $version = '0.1';
    
    
    /**
     * Мениджър - входна точка в пакета
     */
    var $startCtr = 'seven_Offers';
    
    
    /**
     * Екшън - входна точка в пакета
     */
    var $startAct = 'default';
    
    
    /**
     * Описание на модула
     */
    var $info = "Пакет с документи за фирма Седем ЕООД";
    
    
    /**
     * Инсталиране на пакета
     */
    function install()
    {
        $managers = array(
            'seven_Offers',
            'seven_Inquiry',
			'seven_Orders',
            'seven_Contracts',
            'seven_Protocols',
            'seven_Jobs',
            );
        
        // Роля за power-user на този модул
        $role = 'sales';

        $html = core_Roles::addRole($role) ? "<li style='color:green'>Добавена е роля <b>$role</b></li>" : '';
        
        $instances = array();
        
        foreach ($managers as $manager) {
            $instances[$manager] = &cls::get($manager);
            $html .= $instances[$manager]->setupMVC();
        }
        
//        core_Classes::add('cams_driver_UIC');
        
        $Menu = cls::get('bgerp_Menu');
        $Menu->addItem(1, 'Продажби', 'Седем ЕООД', 'seven_Offers', 'default', "{$role}, admin");
        
        return $html;
    }
    
    
    /**
     * Де-инсталиране на пакета
     */
    function deinstall()
    {
        // Изтриване на пакета от менюто
        $res .= bgerp_Menu::remove($this);
        
        return $res;
    }
}