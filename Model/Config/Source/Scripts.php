<?php

namespace Mtools\SpamBlocker\Model\Config\Source;

class Scripts implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $options = [];
        $scripts = [
            '/\p{Common}+/u' => 'Common',
            '/\p{Arabic}+/u' => 'Arabic',
            '/\p{Armenian}+/u' => 'Armenian',
            '/\p{Bengali}+/u' => 'Bengali',
            '/\p{Bopomofo}+/u' => 'Bopomofo',
            '/\p{Braille}+/u' => 'Braille',
            '/\p{Buhid}+/u' => 'Buhid',
            '/\p{Canadian_Aboriginal}+/u' => 'Canadian_Aboriginal',
            '/\p{Cherokee}+/u' => 'Cherokee',
            '/\p{Cyrillic}+/u' => 'Cyrillic',
            '/\p{Devanagari}+/u' => 'Devanagari',
            '/\p{Ethiopic}+/u' => 'Ethiopic',
            '/\p{Georgian}+/u' => 'Georgian',
            '/\p{Greek}+/u' => 'Greek',
            '/\p{Gujarati}+/u' => 'Gujarati',
            '/\p{Gurmukhi}+/u' => 'Gurmukhi',
            '/\p{Han}+/u' => 'Han',
            '/\p{Hangul}+/u' => 'Hangul',
            '/\p{Hanunoo}+/u' => 'Hanunoo',
            '/\p{Hebrew}+/u' => 'Hebrew',
            '/\p{Hiragana}+/u' => 'Hiragana',
            '/\p{Inherited}+/u' => 'Inherited',
            '/\p{Kannada}+/u' => 'Kannada',
            '/\p{Katakana}+/u' => 'Katakana',
            '/\p{Khmer}+/u' => 'Khmer',
            '/\p{Lao}+/u' => 'Lao',
            '/\p{Latin}+/u' => 'Latin',
            '/\p{Limbu}+/u' => 'Limbu',
            '/\p{Malayalam}+/u' => 'Malayalam',
            '/\p{Mongolian}+/u' => 'Mongolian',
            '/\p{Myanmar}+/u' => 'Myanmar',
            '/\p{Ogham}+/u' => 'Ogham',
            '/\p{Oriya}+/u' => 'Oriya',
            '/\p{Runic}+/u' => 'Runic',
            '/\p{Sinhala}+/u' => 'Sinhala',
            '/\p{Syriac}+/u' => 'Syriac',
            '/\p{Tagalog}+/u' => 'Tagalog',
            '/\p{Tagbanwa}+/u' => 'Tagbanwa',
            '/\p{TaiLe}+/u' => 'TaiLe',
            '/\p{Tamil}+/u' => 'Tamil',
            '/\p{Telugu}+/u' => 'Telugu',
            '/\p{Thaana}+/u' => 'Thaana',
            '/\p{Thai}+/u' => 'Thai',
            '/\p{Tibetan}+/u' => 'Tibetan',
            '/\p{Yi}+/u' => 'Yi',
        ];

        foreach ($scripts as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $options;
    }

}
