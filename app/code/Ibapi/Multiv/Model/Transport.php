<?php
namespace Ibapi\Multiv\Model;

class Transport extends  \Zend_Mail_Transport_File implements \Magento\Framework\Mail\TransportInterface
{

	
	/**
	 * @var \Magento\Framework\Mail\MessageInterface
	 */
	protected $_message;
	
	/**
	 * @param MessageInterface $message
	 * @param null $parameters
	 * @throws \InvalidArgumentException
	 */
	public function __construct(\Magento\Framework\Mail\MessageInterface $message)
	{
		if (!$message instanceof \Zend_Mail) {
			throw new \InvalidArgumentException('The message should be an instance of \Zend_Mail');
		}
		$options=[];
		$options['callback'] = array($this, 'defaultCallback');
		$this->setOptions($options);
		
		$this->_message = $message;
///		$this->body=$this->_message->getBody();
	///$this->_buildBody()
	}
	/**
	 * Send a mail using this transport
	 * @return void
	 * @throws \Magento\Framework\Exception\MailException
	 */
	public function sendMessage()
	{
		try {
			
			$this->_path='mail/';
			$rs=$this->_message->getBody();
			if(!is_string($rs)){
			    $rs=print_r($rs,1);
			}
			file_put_contents('logs/mail_'.time().'.txt', $rs,FILE_APPEND);
			
			//$this->send($this->_message);
//			$this->_sendMail();

		} catch (\Exception $e) {
			throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
		}
	}
    public function getMessage()
    {}

	
	
}