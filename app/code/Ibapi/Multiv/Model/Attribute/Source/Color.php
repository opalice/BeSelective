<?php 
namespace Ibapi\Multiv\Model\Attribute\Source;

class Color extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['label' => __('ARGENTE'), 'value' => 'ARGENTE'],
                ['label' => __('BEIGE'), 'value' => 'BEIGE'],
                ['label' => __('BLANC'), 'value' => 'BLANC'],
                ['label' => __('BLEU'), 'value' => 'BLEU'],
                ['label' => __('CHAMPAGNE'), 'value' => 'CHAMPAGNE'],
                ['label' => __('DORE'), 'value' => 'DORE'],
                ['label' => __('GRIS'), 'value' => 'GRIS'],
                ['label' => __('JAUNE'), 'value' => 'JAUNE'],
                ['label' => __('MARRON'), 'value' => 'MARRON'],
                ['label' => __('NOIR'), 'value' => 'NOIR'],
                ['label' => __('ORANGE'), 'value' => 'ORANGE'],
                ['label' => __('ROSE'), 'value' => 'ROSE'],
                ['label' => __('ROUGE'), 'value' => 'ROUGE'],
                ['label' => __('VERT'), 'value' => 'VERT'],
                ['label' => __('VIOLET'), 'value' => 'VIOLET'],
                ['label' => __('MOTIF IMPRIME'), 'value' => 'MOTIF IMPRIME'],
            ];
        }
        return $this->_options;
    }
}