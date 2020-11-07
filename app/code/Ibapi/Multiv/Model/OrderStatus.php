<?php
namespace Ibapi\Multiv\Model;

class OrderStatus{
    
///    const ='rent_process';
    
    const RENT_PROCESS='rent_process';
    const RENT_RETURNED='deliver_return';
    const RETURN_IN_PROG='return_in_progress';
    const WAITING_REVIEW='waiting_review';
    const PARTIAL_RETURN='partial_return';
    const RENT_DEFAULT='rent_default';
    const FULL_DEFALT='rent_default_full';
    
    /*
INSERT INTO  `sales_order_status_state` (`status`, `state`) VALUES ('rent_process', 'processing');
INSERT INTO  `sales_order_status_state` (`status`, `state`) VALUES ('rent_returned', 'processing');
INSERT INTO   `sales_order_status_state` (`status`, `state`) VALUES ('return_in_progress', 'processing');
INSERT INTO    `sales_order_status_state` (`status`, `state`) VALUES ('waiting_review', 'processing');

     */
    
}