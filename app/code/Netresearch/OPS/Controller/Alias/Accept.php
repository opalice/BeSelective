<?php

namespace Netresearch\OPS\Controller\Alias;

class Accept extends \Netresearch\OPS\Controller\Alias
{
    /**
     * accept-action for Alias-generating iframe-response
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $this->oPSHelper->log(
            __(
                "Incoming accepted Ingenico ePayments Alias Feedback\n\nRequest Path: %1\nParams: %2\n",
                $this->getRequest()->getPathInfo(),
                serialize($params)
            )
        );
        $this->oPSAliasHelper->saveAlias($params);
/*
 * http://beselective.be/fr/ops/alias/accept/method/ops_cc/?
 * Alias.AliasId=EF211AF4-0168-4529-A8E8-0775E585AA75&Card.Bin=411111&Card.Brand=VISA&
 * Card.CardNumber=XXXXXXXXXXXX1111&Card.CardHolderName=ddx+dx&Card.Cvc=XXX&
 * Card.ExpiryDate=0219&Alias.NCError=0&Alias.NCErrorCardNo=0&
 * Alias.NCErrorCN=0&Alias.NCErrorCVC=0&Alias.NCErrorED=0&
 * Alias.OrderId=5&Alias.Status=0&Alias.StorePermanently=Y&RESPONSEFORMAT=JSON&SHASign=DBBA0E088DF5288F2CA4A1D2745D1153CFA56870
 */
        if (array_key_exists('Alias_OrderId', $params)) {
            $quote = $this->quoteQuoteFactory->create()->load($params['Alias_OrderId']);
            $this->updateAdditionalInformation($quote, $params);
        } else {
            $quote = $this->getQuote();
        }

        // OGNH-7 special handling for admin orders
        $this->oPSAliasHelper->setAliasToPayment(
            $quote->getPayment(),
            array_change_key_case($params, CASE_LOWER),
            false
        );

        // @codingStandardsIgnoreStart
        $result
            = sprintf(
                "<script type='application/javascript'>window.onload =  function() {  top.jQuery('body').trigger('alias:success', ['%s']); };/*top.jQuery('button.checkout').trigger('click');*/</script>",
                $params['Alias_AliasId']
            );
        // @codingStandardsIgnoreEnd

        return $this->getResponse()->setBody($result);
    }
}
