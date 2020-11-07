<?php 
namespace Ibapi\Multiv\Model\Attribute\Source;

class Size extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Get all options
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('None'), 'value' => ''],
                ['label' => __('32'), 'value' => '32'],
                ['label' => __('34'), 'value' => '34'],
                ['label' => __('36'), 'value' => '36'],
                ['label' => __('38'), 'value' => '38'],
                ['label' => __('40'), 'value' => '40'],
                ['label' => __('42'), 'value' => '42'],
                ['label' => __('44'), 'value' => '44'],
                ['label' => __('46'), 'value' => '46'],
            ];
        }
        return $this->_options;
    }
}