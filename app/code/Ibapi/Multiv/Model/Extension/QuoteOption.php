<?php
namespace Ibapi\Multiv\Model\Extension;
use Magento\Framework\Model\AbstractExtensibleModel;
use Ibapi\Multiv\Api\Data\QuoteOptionInterface;
class QuoteOption extends AbstractExtensibleModel implements QuoteOptionInterface{

    var $pid;
    var $depoid;
    var $dt;
	public function getRentalDates(){
    

		$s= $this->getData(self::OPTION_KEY);

		return $s;
	}
	public function setRentalDates($param) {



		$this->setData(self::OPTION_KEY,$param);




	}
	public function getLastRequest(){
	    return [$this->dt,$this->pid,$this->depoid];
	}
	
	public function setLastRequest($dt,$pid,$depoid){
	    
	    $this->dt=$dt;
	    $this->pid=$pid;
	    $this->depoid=$depoid;
	}
	
	
	

}