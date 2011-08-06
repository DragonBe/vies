<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function viesAction()
    {
        $countryCode = 'NL';
        $vatNumber = '008853150B02';
        $client = new Bas_Service_Vies();
        Zend_Debug::dump($client->getFunctions());
        Zend_Debug::dump($client->validateVat($countryCode, $vatNumber));
        Zend_Debug::dump($client->validateVatApprox($countryCode, $vatNumber, 'Bas Trucks BV'));
    }

    public function translateAction()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/plain; Charset=ISO-8859-1');
        $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename=translations.csv');
        $bootstrap = $this->getInvokeArg('bootstrap');
        $db = $bootstrap->getResource('db');
        
        $select = $db->select()
           ->from('Vertalingen')
           ->order('TekstEN');
        
        $result = $db->fetchAll($select);
        
        foreach ($result as $row) {
            echo sprintf('"%s","%s"',$row['TekstEN'], $row['TekstNL']) . PHP_EOL;
        }
    }


}





