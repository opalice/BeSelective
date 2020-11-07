<?php 
namespace Ibapi\Multiv\Model\Attribute\Source;

class Length extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['label' => __('COURTE'), 'value' => 'COURTE'],
                ['label' => __('3/4'), 'value' => '3/4'],
                ['label' => __('LONGUE'), 'value' => 'LONGUE'],
            ];
        }
        return $this->_options;
    }
}