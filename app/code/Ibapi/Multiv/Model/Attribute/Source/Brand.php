<?php 
namespace Ibapi\Multiv\Model\Attribute\Source;

class Brand extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Get all options ['label' => __(''), 'value' => ''],
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('None'), 'value' => ''],
                ['label' => __('ADRIANNA PAPELL'), 'value' => 'ADRIANNA PAPELL'],
                ['label' => __('AIDAN MATTOX'), 'value' => 'AIDAN MATTOX'],
                ['label' => __('ALAÏA'), 'value' => 'ALAÏA'],
                ['label' => __('ALBERTA FERRETI'), 'value' => 'ALBERTA FERRETI'],
                ['label' => __('ALBERTA FERRETTI'), 'value' => 'ALBERTA FERRETTI'],
                ['label' => __('ALEXANDER McQUEEN'), 'value' => 'ALEXANDER McQUEEN'],
                ['label' => __('ALEXIS'), 'value' => 'ALEXIS'],
                ['label' => __('ALICE + OLIVIA'), 'value' => 'ALICE + OLIVIA'],
                ['label' => __('ANNA RACHELE BLACK LABEL'), 'value' => 'ANNA RACHELE BLACK LABEL'],
                ['label' => __('ANNA RACHELE'), 'value' => 'ANNA RACHELE'],
                ['label' => __('BADGLEY MISCHKA'), 'value' => 'BADGLEY MISCHKA'],
                ['label' => __('BCBGMAXAZRIA'), 'value' => 'BCBGMAXAZRIA'],
                ['label' => __('BELLA RHAPSODY'), 'value' => 'BELLA RHAPSODY'],
                ['label' => __('CAVALLI CLASS'), 'value' => 'CAVALLI CLASS'],
                ['label' => __('COS'), 'value' => 'COS'],
                ['label' => __('CEDRIC CHARLIER'), 'value' => 'CEDRIC CHARLIER'],
                ['label' => __('CHIARA P'), 'value' => 'CHIARA P'],
                ['label' => __('CHRISTIAN PELIZZARI'), 'value' => 'CHRISTIAN PELIZZARI'],
                ['label' => __('CHRISTIAN PELLIZZARI'), 'value' => 'CHRISTIAN PELLIZZARI'],
                ['label' => __('DIESEL'), 'value' => 'DIESEL'],
                ['label' => __('DAVID MEISTER'), 'value' => 'DAVID MEISTER'],
                ['label' => __('DELVAUX'), 'value' => 'DELVAUX'],
                ['label' => __('DIANE KRÜGER'), 'value' => 'DIANE KRÜGER'],
                ['label' => __('DIANE VON FUSTENBERG'), 'value' => 'DIANE VON FUSTENBERG'],
                ['label' => __('DIANE VON FURSTENBERG'), 'value' => 'DIANE VON FURSTENBERG'],
                ['label' => __('DOLCE GABBANA'), 'value' => 'DOLCE GABBANA'],
                ['label' => __('DOLCE & GABBANA'), 'value' => 'DOLCE & GABBANA'],
                ['label' => __('EBENE BY P. ASSULINE'), 'value' => 'EBENE BY P. ASSULINE'],
                ['label' => __('ELIE TAHARI'), 'value' => 'ELIE TAHARI'],
                ['label' => __('ELISABETA FRANCHI'), 'value' => 'ELISABETA FRANCHI'],
                ['label' => __('ELISABETTA FRANCHI'), 'value' => 'ELISABETTA FRANCHI'],
                ['label' => __('EMPORIO ARMANI'), 'value' => 'EMPORIO ARMANI'],
                ['label' => __('GANNI'), 'value' => 'GANNI'],
                ['label' => __('GENTRYPORTOFINO'), 'value' => 'GENTRYPORTOFINO'],
                ['label' => __('GUCCI'), 'value' => 'GUCCI'],
                ['label' => __('HALSTON HERIATGE'), 'value' => 'HALSTON HERIATGE'],
                ['label' => __('HALSTON HERITAGE'), 'value' => 'HALSTON HERITAGE'],
                ['label' => __('HELMUT LANG'), 'value' => 'HELMUT LANG'],
                ['label' => __('HERVE LEGER'), 'value' => 'HERVE LEGER'],
                ['label' => __('HÔTEL PARTICULIER'), 'value' => 'HÔTEL PARTICULIER'],
                ['label' => __('IKKS'), 'value' => 'IKKS'],
                ['label' => __('IRO'), 'value' => 'IRO'],
                ['label' => __('JIMMY CHOO'), 'value' => 'JIMMY CHOO'],
                ['label' => __('JOSEPH'), 'value' => 'JOSEPH'],
                ['label' => __('KARL LAGERFELD PARIS'), 'value' => 'KARL LAGERFELD PARIS'],
                ['label' => __('KENZO'), 'value' => 'KENZO'],
                ['label' => __('LANCEL'), 'value' => 'LANCEL'],
                ['label' => __('LAUNDRY BY SHELLI SEGAL'), 'value' => 'LAUNDRY BY SHELLI SEGAL'],
                ['label' => __('LEETHA'), 'value' => 'LEETHA'],
                ['label' => __('LOUIS VUITTON'), 'value' => 'LOUIS VUITTON'],
                ['label' => __('LOVE MOSCHINO'), 'value' => 'LOVE MOSCHINO'],
                ['label' => __('MAISON MARCIELA'), 'value' => 'MAISON MARCIELA'],
                ['label' => __('MAISON MARGIELA'), 'value' => 'MAISON MARGIELA'],
                ['label' => __('MAJE'), 'value' => 'MAJE'],
                ['label' => __('MARC JACOB'), 'value' => 'MARC JACOB'],
                ['label' => __('MARC JACOBS'), 'value' => 'MARC JACOBS'],
                ['label' => __('MARCIANO'), 'value' => 'MARCIANO'],
                ['label' => __('MARIA LUCIA HOHAN'), 'value' => 'MARIA LUCIA HOHAN'],
                ['label' => __('MONIQUE LHUILLIER'), 'value' => 'MONIQUE LHUILLIER'],
                ['label' => __('MOSCHINO COUTURE'), 'value' => 'MOSCHINO COUTURE'],
                ['label' => __('NINA RICCI'), 'value' => 'NINA RICCI'],
                ['label' => __('NORA BARTH'), 'value' => 'NORA BARTH'],
                ['label' => __('ROBERTO CAVALLI'), 'value' => 'ROBERTO CAVALLI'],
                ['label' => __('RODO'), 'value' => 'RODO'],
                ['label' => __('SANDRO'), 'value' => 'SANDRO'],
                ['label' => __('SCAPA'), 'value' => 'SCAPA'],
                ['label' => __('SELF PORTRAIT'), 'value' => 'SELF PORTRAIT'],
                ['label' => __('SOANI'), 'value' => 'SOANI'],
                ['label' => __('SOHO DE LUXE'), 'value' => 'SOHO DE LUXE'],
                ['label' => __('SPACE STYLE CONCEPT'), 'value' => 'SPACE STYLE CONCEPT'],
                ['label' => __('STELLA McCARTNEY'), 'value' => 'STELLA McCARTNEY'],
                ['label' => __('TADASHI SHOJI'), 'value' => 'TADASHI SHOJI'],
                ['label' => __('TERI JON'), 'value' => 'TERI JON'],
                ['label' => __('THIERRY MUGLER'), 'value' => 'THIERRY MUGLER'],
                ['label' => __('THOMAS WILDE'), 'value' => 'THOMAS WILDE'],
                ['label' => __('VIVIENNE WESTWOOD'), 'value' => 'VIVIENNE WESTWOOD'],
                ['label' => __('YVES ST LAURENT'), 'value' => 'YVES ST LAURENT'],
                ['label' => __('ZADIG & VOLTAIRE'), 'value' => 'ZADIG & VOLTAIRE'],
                ['label' => __('VELVET'), 'value' => 'VELVET'],
                ['label' => __('VERSACE'), 'value' => 'VERSACE'],
            ];
        }
        return $this->_options;
    }
}