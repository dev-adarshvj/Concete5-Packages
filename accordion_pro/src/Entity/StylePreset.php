<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Src\Entity;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Package\AccordionPro\Src\Helper;
use Concrete\Package\AccordionPro\Src\StylePresets;
use Database;
use Core;
use Package;

/**
 * @Entity
 * @Table(name="AccordionStylePreset")
 * */
class StylePreset
{
    const defaultBackgroundColor = '#f7f7f7';
    const defaultColor = '#333333';
    const defaultColorHover = '#447618';
    
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $presetId;

    /**
     * @Column(type="string") * */
    protected $presetName = '';

    /**
     * @Column(type="boolean") * */
    protected $isSystemPreset = false;

    /**
     * @Column(type="string") * */
    protected $titleBackgroundColorNormal = '';

    /**
     * @Column(type="string") * */
    protected $titleBackgroundColorHover = '';

    /**
     * @Column(type="string") * */
    protected $titleBackgroundColorActive = '';

    /**
     * @Column(type="string") * */
    protected $titleTextColorNormal = '';

    /**
     * @Column(type="string") * */
    protected $titleTextColorHover = '';

    /**
     * @Column(type="string") * */
    protected $titleTextColorActive = '';

    /**
     * @Column(type="string") * */
    protected $titleTextFontWeight = '';

    /**
     * @Column(type="string") * */
    protected $titleTextFontSize = '';

    /**
     * @Column(type="string") * */
    protected $titleTextDecoration = '';

    /**
     * @Column(type="string") * */
    protected $titleIconNormal = '';

    /**
     * @Column(type="string") * */
    protected $titleIconActive = '';

    /**
     * @Column(type="string") * */
    protected $titleIconOrientation = '';

    /**
     * @Column(type="string") * */
    protected $titleIconPadding = '';

    /**
     * @Column(type="string") * */
    protected $titleIconColorNormal = '';

    /**
     * @Column(type="string") * */
    protected $titleIconBackgroundColorNormal = '';

    /**
     * @Column(type="string") * */
    protected $titleIconColorHover = '';

    /**
     * @Column(type="string") * */
    protected $titleIconBackgroundColorHover = '';

    /**
     * @Column(type="string") * */
    protected $titleIconColorActive = '';

    /**
     * @Column(type="string") * */
    protected $titleIconBackgroundColorActive = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderBottomColor = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderTopColor = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderLeftColor = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderRightColor = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderBottomWidth = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderTopWidth = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderLeftWidth = '';

    /**
     * @Column(type="string") * */
    protected $titleBorderRightWidth = '';

    /**
     * @Column(type="string") * */
    protected $titlePaddingLeft = '';

    /**
     * @Column(type="string") * */
    protected $titlePaddingRight = '';

    /**
     * @Column(type="string") * */
    protected $titlePaddingBottom = '';

    /**
     * @Column(type="string") * */
    protected $titlePaddingTop = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderBottomColor = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderTopColor = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderLeftColor = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderRightColor = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderBottomWidth = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderTopWidth = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderLeftWidth = '';

    /**
     * @Column(type="string") * */
    protected $contentBorderRightWidth = '';

    /**
     * @Column(type="string") * */
    protected $contentPaddingLeft = '';

    /**
     * @Column(type="string") * */
    protected $contentPaddingRight = '';

    /**
     * @Column(type="string") * */
    protected $contentPaddingBottom = '';

    /**
     * @Column(type="string") * */
    protected $contentPaddingTop = '';
    
    /**
     * @Column(type="string") * */
    protected $titleHeight = '';
    
    /**
     * @Column(type="string") * */
    protected $titleLineHeight = '';
    
    /**
     * @Column(type="string") * */
    protected $titleBorderTopStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $titleBorderBottomStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $titleBorderLeftStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $titleBorderRightStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $titleMarginTop = '';
    
    /**
     * @Column(type="string") * */
    protected $titleMarginBottom = '';
    
    /**
     * @Column(type="string") * */
    protected $titleMarginLeft = '';
    
    /**
     * @Column(type="string") * */
    protected $titleMarginRight = '';
    
    /**
     * @Column(type="string") * */
    protected $titleIconMarginTop = '';
    
    /**
     * @Column(type="string") * */
    protected $titleIconMarginBottom = '';
    
    /**
     * @Column(type="string") * */
    protected $titleIconMarginLeft = '';
    
    /**
     * @Column(type="string") * */
    protected $titleIconMarginRight = '';
    
    /**
     * @Column(type="string") * */
    protected $contentBorderTopStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $contentBorderBottomStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $contentBorderLeftStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $contentBorderRightStyle = '';
    
    /**
     * @Column(type="string") * */
    protected $contentMarginTop = '';
    
    /**
     * @Column(type="string") * */
    protected $contentMarginBottom = '';
    
    /**
     * @Column(type="string") * */
    protected $contentMarginLeft = '';
    
    /**
     * @Column(type="string") * */
    protected $contentMarginRight = '';
    
    public function getPresetId()
    {
        return $this->presetId;
    }

    public function getPresetName()
    {
        return $this->presetName;
    }

    public function getIsSystemPreset()
    {
        return $this->isSystemPreset;
    }

    public function getTitleBackgroundColorNormal()
    {
        return $this->titleBackgroundColorNormal;
    }

    public function getTitleBackgroundColorHover()
    {
        return $this->titleBackgroundColorHover;
    }

    public function getTitleBackgroundColorActive()
    {
        return $this->titleBackgroundColorActive;
    }

    public function getTitleTextColorNormal()
    {
        return $this->titleTextColorNormal;
    }

    public function getTitleTextColorHover()
    {
        return $this->titleTextColorHover;
    }

    public function getTitleTextColorActive()
    {
        return $this->titleTextColorActive;
    }

    public function getTitleTextFontWeight()
    {
        return $this->titleTextFontWeight;
    }

    public function getTitleTextFontSize()
    {
        return $this->titleTextFontSize;
    }

    public function getTitleTextDecoration()
    {
        return $this->titleTextDecoration;
    }

    public function getTitleIconNormal()
    {
        return $this->titleIconNormal;
    }

    public function getTitleIconActive()
    {
        return $this->titleIconActive;
    }

    public function getTitleIconOrientation()
    {
        return $this->titleIconOrientation;
    }

    public function getTitleIconPadding()
    {
        return $this->titleIconPadding;
    }

    public function getTitleIconColorNormal()
    {
        return $this->titleIconColorNormal;
    }

    public function getTitleIconBackgroundColorNormal()
    {
        return $this->titleIconBackgroundColorNormal;
    }

    public function getTitleIconColorHover()
    {
        return $this->titleIconColorHover;
    }

    public function getTitleIconBackgroundColorHover()
    {
        return $this->titleIconBackgroundColorHover;
    }

    public function getTitleIconColorActive()
    {
        return $this->titleIconColorActive;
    }

    public function getTitleIconBackgroundColorActive()
    {
        return $this->titleIconBackgroundColorActive;
    }

    public function getTitleBorderBottomColor()
    {
        return $this->titleBorderBottomColor;
    }

    public function getTitleBorderTopColor()
    {
        return $this->titleBorderTopColor;
    }

    public function getTitleBorderLeftColor()
    {
        return $this->titleBorderLeftColor;
    }

    public function getTitleBorderRightColor()
    {
        return $this->titleBorderRightColor;
    }

    public function getTitleBorderBottomWidth()
    {
        return $this->titleBorderBottomWidth;
    }

    public function getTitleBorderTopWidth()
    {
        return $this->titleBorderTopWidth;
    }

    public function getTitleBorderLeftWidth()
    {
        return $this->titleBorderLeftWidth;
    }

    public function getTitleBorderRightWidth()
    {
        return $this->titleBorderRightWidth;
    }

    public function getTitlePaddingLeft()
    {
        return $this->titlePaddingLeft;
    }

    public function getTitlePaddingRight()
    {
        return $this->titlePaddingRight;
    }

    public function getTitlePaddingBottom()
    {
        return $this->titlePaddingBottom;
    }

    public function getTitlePaddingTop()
    {
        return $this->titlePaddingTop;
    }

    public function getContentBorderBottomColor()
    {
        return $this->contentBorderBottomColor;
    }

    public function getContentBorderTopColor()
    {
        return $this->contentBorderTopColor;
    }

    public function getContentBorderLeftColor()
    {
        return $this->contentBorderLeftColor;
    }

    public function getContentBorderRightColor()
    {
        return $this->contentBorderRightColor;
    }

    public function getContentBorderBottomWidth()
    {
        return $this->contentBorderBottomWidth;
    }

    public function getContentBorderTopWidth()
    {
        return $this->contentBorderTopWidth;
    }

    public function getContentBorderLeftWidth()
    {
        return $this->contentBorderLeftWidth;
    }

    public function getContentBorderRightWidth()
    {
        return $this->contentBorderRightWidth;
    }

    public function getContentPaddingLeft()
    {
        return $this->contentPaddingLeft;
    }

    public function getContentPaddingRight()
    {
        return $this->contentPaddingRight;
    }

    public function getContentPaddingBottom()
    {
        return $this->contentPaddingBottom;
    }

    public function getContentPaddingTop()
    {
        return $this->contentPaddingTop;
    }

    private function getDefaultTitleBackgroundColorNormal()
    {
        return "transparent";
    }

    private function getDefaultTitleBackgroundColorHover()
    {
        return "transparent";
    }

    private function getDefaultTitleBackgroundColorActive()
    {
        return "transparent";
    }

    private function getDefaultTitleTextColorNormal()
    {
        return self::defaultColor;
    }

    private function getDefaultTitleTextColorHover()
    {
        return self::defaultColorHover;
    }

    private function getDefaultTitleTextColorActive()
    {
        return self::defaultColorHover;
    }

    private function getDefaultTitleTextFontWeight()
    {
        return "normal";
    }

    private function getDefaultTitleTextFontSize()
    {
        return "1em";
    }

    private function getDefaultTitleTextDecoration()
    {
        return "none";
    }

    private function getDefaultTitleIconNormal()
    {
        return "f107";
    }

    private function getDefaultTitleIconActive()
    {
        return "f106";
    }

    private function getDefaultTitleIconOrientation()
    {
        return "right";
    }

    private function getDefaultTitleIconPadding()
    {
        return "0px";
    }

    private function getDefaultTitleIconColorNormal()
    {
        return self::defaultColor;
    }

    private function getDefaultTitleIconBackgroundColorNormal()
    {
        return self::defaultBackgroundColor;
    }

    private function getDefaultTitleIconColorHover()
    {
        return self::defaultColorHover;
    }

    private function getDefaultTitleIconBackgroundColorHover()
    {
        return self::defaultBackgroundColor;
    }

    private function getDefaultTitleIconColorActive()
    {
        return self::defaultColorHover;
    }

    private function getDefaultTitleIconBackgroundColorActive()
    {
        return self::defaultBackgroundColor;
    }

    private function getDefaultTitleBorderBottomColor()
    {
        return "transparent";
    }

    private function getDefaultTitleBorderTopColor()
    {
        return "transparent";
    }

    private function getDefaultTitleBorderLeftColor()
    {
        return "transparent";
    }

    private function getDefaultTitleBorderRightColor()
    {
        return "transparent";
    }

    private function getDefaultTitleBorderBottomWidth()
    {
        return "0px";
    }

    private function getDefaultTitleBorderTopWidth()
    {
        return "0px";
    }

    private function getDefaultTitleBorderLeftWidth()
    {
        return "0px";
    }

    private function getDefaultTitleBorderRightWidth()
    {
        return "0px";
    }

    private function getDefaultTitlePaddingLeft()
    {
        return "0px";
    }

    private function getDefaultTitlePaddingRight()
    {
        return "0px";
    }

    private function getDefaultTitlePaddingBottom()
    {
        return "0px";
    }

    private function getDefaultTitlePaddingTop()
    {
        return "0px";
    }

    private function getDefaultContentBorderBottomColor()
    {
        return "transparent";
    }

    private function getDefaultContentBorderTopColor()
    {
        return "transparent";
    }

    private function getDefaultContentBorderLeftColor()
    {
        return "transparent";
    }

    private function getDefaultContentBorderRightColor()
    {
        return "transparent";
    }

    private function getDefaultContentBorderBottomWidth()
    {
        return "0px";
    }

    private function getDefaultContentBorderTopWidth()
    {
        return "0px";
    }

    private function getDefaultContentBorderLeftWidth()
    {
        return "0px";
    }

    private function getDefaultContentBorderRightWidth()
    {
        return "0px";
    }

    private function getDefaultContentPaddingLeft()
    {
        return "0px";
    }

    private function getDefaultContentPaddingRight()
    {
        return "0px";
    }

    private function getDefaultContentPaddingBottom()
    {
        return "0px";
    }

    private function getDefaultContentPaddingTop()
    {
        return "10px";
    }
    
    private function getDefaultPresetName()
    {
        return t("New Preset");
    }
    
    public function getDefaultTitleHeight()
    {
        return "auto";
    }

    public function getDefaultTitleLineHeight()
    {
        return "normal";
    }

    public function getDefaultTitleBorderTopStyle()
    {
        return "solid";
    }

    public function getDefaultTitleBorderBottomStyle()
    {
        return "solid";
    }

    public function getDefaultTitleBorderLeftStyle()
    {
        return "solid";
    }

    public function getDefaultTitleBorderRightStyle()
    {
        return "solid";
    }

    public function getDefaultTitleMarginTop()
    {
        return "0px";
    }

    public function getDefaultTitleMarginBottom()
    {
        return "0px";
    }

    public function getDefaultTitleMarginLeft()
    {
        return "0px";
    }

    public function getDefaultTitleMarginRight()
    {
        return "0px";
    }

    public function getDefaultTitleIconMarginTop()
    {
        return "0px";
    }

    public function getDefaultTitleIconMarginBottom()
    {
        return "0px";
    }

    public function getDefaultTitleIconMarginLeft()
    {
        return "0px";
    }

    public function getDefaultTitleIconMarginRight()
    {
        return "0px";
    }

    public function getDefaultContentBorderTopStyle()
    {
        return "solid";
    }

    public function getDefaultContentBorderBottomStyle()
    {
        return "solid";
    }

    public function getDefaultContentBorderLeftStyle()
    {
        return "solid";
    }

    public function getDefaultContentBorderRightStyle()
    {
        return "solid";
    }

    public function getDefaultContentMarginTop()
    {
        return "0px";
    }

    public function getDefaultContentMarginBottom()
    {
        return "0px";
    }

    public function getDefaultContentMarginLeft()
    {
        return "0px";
    }

    public function getDefaultContentMarginRight()
    {
        return "0px";
    }
    
    public function getTitleHeight()
    {
        return $this->titleHeight;
    }

    public function getTitleLineHeight()
    {
        return $this->titleLineHeight;
    }

    public function getTitleBorderTopStyle()
    {
        return $this->titleBorderTopStyle;
    }

    public function getTitleBorderBottomStyle()
    {
        return $this->titleBorderBottomStyle;
    }

    public function getTitleBorderLeftStyle()
    {
        return $this->titleBorderLeftStyle;
    }

    public function getTitleBorderRightStyle()
    {
        return $this->titleBorderRightStyle;
    }

    public function getTitleMarginTop()
    {
        return $this->titleMarginTop;
    }

    public function getTitleMarginBottom()
    {
        return $this->titleMarginBottom;
    }

    public function getTitleMarginLeft()
    {
        return $this->titleMarginLeft;
    }

    public function getTitleMarginRight()
    {
        return $this->titleMarginRight;
    }

    public function getTitleIconMarginTop()
    {
        return $this->titleIconMarginTop;
    }

    public function getTitleIconMarginBottom()
    {
        return $this->titleIconMarginBottom;
    }

    public function getTitleIconMarginLeft()
    {
        return $this->titleIconMarginLeft;
    }

    public function getTitleIconMarginRight()
    {
        return $this->titleIconMarginRight;
    }

    public function getContentBorderTopStyle()
    {
        return $this->contentBorderTopStyle;
    }

    public function getContentBorderBottomStyle()
    {
        return $this->contentBorderBottomStyle;
    }

    public function getContentBorderLeftStyle()
    {
        return $this->contentBorderLeftStyle;
    }

    public function getContentBorderRightStyle()
    {
        return $this->contentBorderRightStyle;
    }

    public function getContentMarginTop()
    {
        return $this->contentMarginTop;
    }

    public function getContentMarginBottom()
    {
        return $this->contentMarginBottom;
    }

    public function getContentMarginLeft()
    {
        return $this->contentMarginLeft;
    }

    public function getContentMarginRight()
    {
        return $this->contentMarginRight;
    }

    public function setTitleHeight($titleHeight)
    {
        if (Helper::isValidHeight($titleHeight)) {
            $this->titleHeight = $titleHeight;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleLineHeight($titleLineHeight)
    {
        if (Helper::isValidLineHeight($titleLineHeight)) {
            $this->titleLineHeight = $titleLineHeight;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleBorderTopStyle($titleBorderTopStyle)
    {
        if (Helper::isValidBorderStyle($titleBorderTopStyle)) {
            $this->titleBorderTopStyle = $titleBorderTopStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleBorderBottomStyle($titleBorderBottomStyle)
    {
        if (Helper::isValidBorderStyle($titleBorderBottomStyle)) {
            $this->titleBorderBottomStyle = $titleBorderBottomStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleBorderLeftStyle($titleBorderLeftStyle)
    {
        if (Helper::isValidBorderStyle($titleBorderLeftStyle)) {
            $this->titleBorderLeftStyle = $titleBorderLeftStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleBorderRightStyle($titleBorderRightStyle)
    {
        if (Helper::isValidBorderStyle($titleBorderRightStyle)) {
            $this->titleBorderRightStyle = $titleBorderRightStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleMarginTop($titleMarginTop)
    {
        if (Helper::isValidMargin($titleMarginTop)) {
            $this->titleMarginTop = $titleMarginTop;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleMarginBottom($titleMarginBottom)
    {
        if (Helper::isValidMargin($titleMarginBottom)) {
            $this->titleMarginBottom = $titleMarginBottom;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleMarginLeft($titleMarginLeft)
    {
        if (Helper::isValidMargin($titleMarginLeft)) {
            $this->titleMarginLeft = $titleMarginLeft;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleMarginRight($titleMarginRight)
    {
        if (Helper::isValidMargin($titleMarginRight)) {
            $this->titleMarginRight = $titleMarginRight;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleIconMarginTop($titleIconMarginTop)
    {
        if (Helper::isValidMargin($titleIconMarginTop)) {
            $this->titleIconMarginTop = $titleIconMarginTop;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleIconMarginBottom($titleIconMarginBottom)
    {
        if (Helper::isValidMargin($titleIconMarginBottom)) {
            $this->titleIconMarginBottom = $titleIconMarginBottom;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleIconMarginLeft($titleIconMarginLeft)
    {
        if (Helper::isValidMargin($titleIconMarginLeft)) {
            $this->titleIconMarginLeft = $titleIconMarginLeft;
            
            return true;
        } else {
            return false;
        }
    }

    public function setTitleIconMarginRight($titleIconMarginRight)
    {
        if (Helper::isValidMargin($titleIconMarginRight)) {
            $this->titleIconMarginRight = $titleIconMarginRight;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentBorderTopStyle($contentBorderTopStyle)
    {
        if (Helper::isValidBorderStyle($contentBorderTopStyle)) {
            $this->contentBorderTopStyle = $contentBorderTopStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentBorderBottomStyle($contentBorderBottomStyle)
    {
        if (Helper::isValidBorderStyle($contentBorderBottomStyle)) {
            $this->contentBorderBottomStyle = $contentBorderBottomStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentBorderLeftStyle($contentBorderLeftStyle)
    {
        if (Helper::isValidBorderStyle($contentBorderLeftStyle)) {
            $this->contentBorderLeftStyle = $contentBorderLeftStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentBorderRightStyle($contentBorderRightStyle)
    {
        if (Helper::isValidBorderStyle($contentBorderRightStyle)) {
            $this->contentBorderRightStyle = $contentBorderRightStyle;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentMarginTop($contentMarginTop)
    {
        if (Helper::isValidMargin($contentMarginTop)) {
            $this->contentMarginTop = $contentMarginTop;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentMarginBottom($contentMarginBottom)
    {
        if (Helper::isValidMargin($contentMarginBottom)) {
            $this->contentMarginBottom = $contentMarginBottom;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentMarginLeft($contentMarginLeft)
    {
        if (Helper::isValidMargin($contentMarginLeft)) {
            $this->contentMarginLeft = $contentMarginLeft;
            
            return true;
        } else {
            return false;
        }
    }

    public function setContentMarginRight($contentMarginRight)
    {
        if (Helper::isValidMargin($contentMarginRight)) {
            $this->contentMarginRight = $contentMarginRight;
            
            return true;
        } else {
            return false;
        }
    }

    public function applyDefaults()
    {
        $this->setPresetName($this->getDefaultPresetName());
        $this->setTitleBackgroundColorNormal($this->getDefaultTitleBackgroundColorNormal());
        $this->setTitleBackgroundColorHover($this->getDefaultTitleBackgroundColorHover());
        $this->setTitleBackgroundColorActive($this->getDefaultTitleBackgroundColorActive());
        $this->setTitleTextColorNormal($this->getDefaultTitleTextColorNormal());
        $this->setTitleTextColorHover($this->getDefaultTitleTextColorHover());
        $this->setTitleTextColorActive($this->getDefaultTitleTextColorActive());
        $this->setTitleTextFontWeight($this->getDefaultTitleTextFontWeight());
        $this->setTitleTextFontSize($this->getDefaultTitleTextFontSize());
        $this->setTitleTextDecoration($this->getDefaultTitleTextDecoration());
        $this->setTitleIconNormal($this->getDefaultTitleIconNormal());
        $this->setTitleIconActive($this->getDefaultTitleIconActive());
        $this->setTitleIconOrientation($this->getDefaultTitleIconOrientation());
        $this->setTitleIconPadding($this->getDefaultTitleIconPadding());
        $this->setTitleIconColorNormal($this->getDefaultTitleIconColorNormal());
        $this->setTitleIconBackgroundColorNormal($this->getDefaultTitleIconBackgroundColorNormal());
        $this->setTitleIconColorHover($this->getDefaultTitleIconColorHover());
        $this->setTitleIconBackgroundColorHover($this->getDefaultTitleIconBackgroundColorHover());
        $this->setTitleIconColorActive($this->getDefaultTitleIconColorActive());
        $this->setTitleIconBackgroundColorActive($this->getDefaultTitleIconBackgroundColorActive());
        $this->setTitleBorderBottomColor($this->getDefaultTitleBorderBottomColor());
        $this->setTitleBorderTopColor($this->getDefaultTitleBorderTopColor());
        $this->setTitleBorderLeftColor($this->getDefaultTitleBorderLeftColor());
        $this->setTitleBorderRightColor($this->getDefaultTitleBorderRightColor());
        $this->setTitleBorderBottomWidth($this->getDefaultTitleBorderBottomWidth());
        $this->setTitleBorderTopWidth($this->getDefaultTitleBorderTopWidth());
        $this->setTitleBorderLeftWidth($this->getDefaultTitleBorderLeftWidth());
        $this->setTitleBorderRightWidth($this->getDefaultTitleBorderRightWidth());
        $this->setTitlePaddingLeft($this->getDefaultTitlePaddingLeft());
        $this->setTitlePaddingRight($this->getDefaultTitlePaddingRight());
        $this->setTitlePaddingBottom($this->getDefaultTitlePaddingBottom());
        $this->setTitlePaddingTop($this->getDefaultTitlePaddingTop());
        $this->setContentBorderBottomColor($this->getDefaultContentBorderBottomColor());
        $this->setContentBorderTopColor($this->getDefaultContentBorderTopColor());
        $this->setContentBorderLeftColor($this->getDefaultContentBorderLeftColor());
        $this->setContentBorderRightColor($this->getDefaultContentBorderRightColor());
        $this->setContentBorderBottomWidth($this->getDefaultContentBorderBottomWidth());
        $this->setContentBorderTopWidth($this->getDefaultContentBorderTopWidth());
        $this->setContentBorderLeftWidth($this->getDefaultContentBorderLeftWidth());
        $this->setContentBorderRightWidth($this->getDefaultContentBorderRightWidth());
        $this->setContentPaddingLeft($this->getDefaultContentPaddingLeft());
        $this->setContentPaddingRight($this->getDefaultContentPaddingRight());
        $this->setContentPaddingBottom($this->getDefaultContentPaddingBottom());
        $this->setContentPaddingTop($this->getDefaultContentPaddingTop());
        $this->setTitleHeight($this->getDefaultTitleHeight());
        $this->setTitleLineHeight($this->getDefaultTitleLineHeight());
        $this->setTitleBorderTopStyle($this->getDefaultTitleBorderTopStyle());
        $this->setTitleBorderBottomStyle($this->getDefaultTitleBorderBottomStyle());
        $this->setTitleBorderLeftStyle($this->getDefaultTitleBorderLeftStyle());
        $this->setTitleBorderRightStyle($this->getDefaultTitleBorderRightStyle());
        $this->setTitleMarginTop($this->getDefaultTitleMarginTop());
        $this->setTitleMarginBottom($this->getDefaultTitleMarginBottom());
        $this->setTitleMarginLeft($this->getDefaultTitleMarginLeft());
        $this->setTitleMarginRight($this->getDefaultTitleMarginRight());
        $this->setTitleIconMarginTop($this->getDefaultTitleIconMarginTop());
        $this->setTitleIconMarginBottom($this->getDefaultTitleIconMarginBottom());
        $this->setTitleIconMarginLeft($this->getDefaultTitleIconMarginLeft());
        $this->setTitleIconMarginRight($this->getDefaultTitleIconMarginRight());
        $this->setContentBorderTopStyle($this->getDefaultContentBorderTopStyle());
        $this->setContentBorderBottomStyle($this->getDefaultContentBorderBottomStyle());
        $this->setContentBorderLeftStyle($this->getDefaultContentBorderLeftStyle());
        $this->setContentBorderRightStyle($this->getDefaultContentBorderRightStyle());
        $this->setContentMarginTop($this->getDefaultContentMarginTop());
        $this->setContentMarginBottom($this->getDefaultContentMarginBottom());
        $this->setContentMarginLeft($this->getDefaultContentMarginLeft());
        $this->setContentMarginRight($this->getDefaultContentMarginRight());
    }

    /**
     *
     * @param integer $presetId
     * @return boolean
     */
    public function setPresetId($presetId)
    {
        $this->presetId = $presetId;
        
        return true;
    }

    /**
     *
     * @param string $presetName
     * @return boolean
     */
    public function setPresetName($presetName)
    {
        if (strlen($presetName) > 0) {
            $this->presetName = $presetName;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param boolean $isSystemPreset
     * @return boolean
     */
    public function setIsSystemPreset($isSystemPreset)
    {
        if (intval($isSystemPreset) === 1 || intval($isSystemPreset) === 0) {
            $this->isSystemPreset = $isSystemPreset;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBackgroundColorNormal
     * @return boolean
     */
    public function setTitleBackgroundColorNormal($titleBackgroundColorNormal)
    {
        if (Helper::isValidColor($titleBackgroundColorNormal)) {
            $this->titleBackgroundColorNormal = $titleBackgroundColorNormal;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBackgroundColorHover
     * @return boolean
     */
    public function setTitleBackgroundColorHover($titleBackgroundColorHover)
    {
        if (Helper::isValidColor($titleBackgroundColorHover)) {
            $this->titleBackgroundColorHover = $titleBackgroundColorHover;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBackgroundColorActive
     * @return boolean
     */
    public function setTitleBackgroundColorActive($titleBackgroundColorActive)
    {
        if (Helper::isValidColor($titleBackgroundColorActive)) {
            $this->titleBackgroundColorActive = $titleBackgroundColorActive;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleTextColorNormal
     * @return boolean
     */
    public function setTitleTextColorNormal($titleTextColorNormal)
    {
        if (Helper::isValidColor($titleTextColorNormal)) {
            $this->titleTextColorNormal = $titleTextColorNormal;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleTextColorHover
     * @return boolean
     */
    public function setTitleTextColorHover($titleTextColorHover)
    {
        if (Helper::isValidColor($titleTextColorHover)) {
            $this->titleTextColorHover = $titleTextColorHover;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleTextColorActive
     * @return boolean
     */
    public function setTitleTextColorActive($titleTextColorActive)
    {
        if (Helper::isValidColor($titleTextColorActive)) {
            $this->titleTextColorActive = $titleTextColorActive;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleTextFontWeight
     * @return boolean
     */
    public function setTitleTextFontWeight($titleTextFontWeight)
    {
        if (Helper::isValidFontWeight($titleTextFontWeight)) {
            $this->titleTextFontWeight = $titleTextFontWeight;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleTextFontSize
     * @return boolean
     */
    public function setTitleTextFontSize($titleTextFontSize)
    {
        if (Helper::isValidFontSize($titleTextFontSize)) {
            $this->titleTextFontSize = $titleTextFontSize;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleTextDecoration
     * @return boolean
     */
    public function setTitleTextDecoration($titleTextDecoration)
    {
        if (Helper::isValidTextDecoration($titleTextDecoration)) {
            $this->titleTextDecoration = $titleTextDecoration;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconNormal
     * @return boolean
     */
    public function setTitleIconNormal($titleIconNormal)
    {
        if (Helper::isValidFontAwesomeIcon($titleIconNormal)) {
            $this->titleIconNormal = $titleIconNormal;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconActive
     * @return boolean
     */
    public function setTitleIconActive($titleIconActive)
    {
        if (Helper::isValidFontAwesomeIcon($titleIconActive)) {
            $this->titleIconActive = $titleIconActive;
            
            return true;
        } else {
            return false;
        }
    }
    
    public function getOrientations()
    {
        return array(
            "left" => t("Left"),
            "right" => t("Right")
        );
    }
    
    /**
     *
     * @param string $titleIconOrientation
     * @return boolean
     */
    public function setTitleIconOrientation($titleIconOrientation)
    {
        if (in_array($titleIconOrientation, Helper::getKeys($this->getOrientations()))) {
            $this->titleIconOrientation = $titleIconOrientation;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconPadding
     * @return boolean
     */
    public function setTitleIconPadding($titleIconPadding)
    {
        if (Helper::isValidPadding($titleIconPadding)) {
            $this->titleIconPadding = $titleIconPadding;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconColorNormal
     * @return boolean
     */
    public function setTitleIconColorNormal($titleIconColorNormal)
    {
        if (Helper::isValidColor($titleIconColorNormal)) {
            $this->titleIconColorNormal = $titleIconColorNormal;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconBackgroundColorNormal
     * @return boolean
     */
    public function setTitleIconBackgroundColorNormal($titleIconBackgroundColorNormal)
    {
        if (Helper::isValidColor($titleIconBackgroundColorNormal)) {
            $this->titleIconBackgroundColorNormal = $titleIconBackgroundColorNormal;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconColorHover
     * @return boolean
     */
    public function setTitleIconColorHover($titleIconColorHover)
    {
        if (Helper::isValidColor($titleIconColorHover)) {
            $this->titleIconColorHover = $titleIconColorHover;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconBackgroundColorHover
     * @return boolean
     */
    public function setTitleIconBackgroundColorHover($titleIconBackgroundColorHover)
    {
        if (Helper::isValidColor($titleIconBackgroundColorHover)) {
            $this->titleIconBackgroundColorHover = $titleIconBackgroundColorHover;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconColorActive
     * @return boolean
     */
    public function setTitleIconColorActive($titleIconColorActive)
    {
        if (Helper::isValidColor($titleIconColorActive)) {
            $this->titleIconColorActive = $titleIconColorActive;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleIconBackgroundColorActive
     * @return boolean
     */
    public function setTitleIconBackgroundColorActive($titleIconBackgroundColorActive)
    {
        if (Helper::isValidColor($titleIconBackgroundColorActive)) {
            $this->titleIconBackgroundColorActive = $titleIconBackgroundColorActive;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderBottomColor
     * @return boolean
     */
    public function setTitleBorderBottomColor($titleBorderBottomColor)
    {
        if (Helper::isValidColor($titleBorderBottomColor)) {
            $this->titleBorderBottomColor = $titleBorderBottomColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderTopColor
     * @return boolean
     */
    public function setTitleBorderTopColor($titleBorderTopColor)
    {
        if (Helper::isValidColor($titleBorderTopColor)) {
            $this->titleBorderTopColor = $titleBorderTopColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderLeftColor
     * @return boolean
     */
    public function setTitleBorderLeftColor($titleBorderLeftColor)
    {
        if (Helper::isValidColor($titleBorderLeftColor)) {
            $this->titleBorderLeftColor = $titleBorderLeftColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderRightColor
     * @return boolean
     */
    public function setTitleBorderRightColor($titleBorderRightColor)
    {
        if (Helper::isValidColor($titleBorderRightColor)) {
            $this->titleBorderRightColor = $titleBorderRightColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderBottomWidth
     * @return boolean
     */
    public function setTitleBorderBottomWidth($titleBorderBottomWidth)
    {
        if (Helper::isValidBorderWidth($titleBorderBottomWidth)) {
            $this->titleBorderBottomWidth = $titleBorderBottomWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderTopWidth
     * @return boolean
     */
    public function setTitleBorderTopWidth($titleBorderTopWidth)
    {
        if (Helper::isValidBorderWidth($titleBorderTopWidth)) {
            $this->titleBorderTopWidth = $titleBorderTopWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderLeftWidth
     * @return boolean
     */
    public function setTitleBorderLeftWidth($titleBorderLeftWidth)
    {
        if (Helper::isValidBorderWidth($titleBorderLeftWidth)) {
            $this->titleBorderLeftWidth = $titleBorderLeftWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titleBorderRightWidth
     * @return boolean
     */
    public function setTitleBorderRightWidth($titleBorderRightWidth)
    {
        if (Helper::isValidBorderWidth($titleBorderRightWidth)) {
            $this->titleBorderRightWidth = $titleBorderRightWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titlePaddingLeft
     * @return boolean
     */
    public function setTitlePaddingLeft($titlePaddingLeft)
    {
        if (Helper::isValidPadding($titlePaddingLeft)) {
            $this->titlePaddingLeft = $titlePaddingLeft;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titlePaddingRight
     * @return boolean
     */
    public function setTitlePaddingRight($titlePaddingRight)
    {
        if (Helper::isValidPadding($titlePaddingRight)) {
            $this->titlePaddingRight = $titlePaddingRight;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titlePaddingBottom
     * @return boolean
     */
    public function setTitlePaddingBottom($titlePaddingBottom)
    {
        if (Helper::isValidPadding($titlePaddingBottom)) {
            $this->titlePaddingBottom = $titlePaddingBottom;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $titlePaddingTop
     * @return boolean
     */
    public function setTitlePaddingTop($titlePaddingTop)
    {
        if (Helper::isValidPadding($titlePaddingTop)) {
            $this->titlePaddingTop = $titlePaddingTop;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderBottomColor
     * @return boolean
     */
    public function setContentBorderBottomColor($contentBorderBottomColor)
    {
        if (Helper::isValidColor($contentBorderBottomColor)) {
            $this->contentBorderBottomColor = $contentBorderBottomColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderTopColor
     * @return boolean
     */
    public function setContentBorderTopColor($contentBorderTopColor)
    {
        if (Helper::isValidColor($contentBorderTopColor)) {
            $this->contentBorderTopColor = $contentBorderTopColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderLeftColor
     * @return boolean
     */
    public function setContentBorderLeftColor($contentBorderLeftColor)
    {
        if (Helper::isValidColor($contentBorderLeftColor)) {
            $this->contentBorderLeftColor = $contentBorderLeftColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderRightColor
     * @return boolean
     */
    public function setContentBorderRightColor($contentBorderRightColor)
    {
        if (Helper::isValidColor($contentBorderRightColor)) {
            $this->contentBorderRightColor = $contentBorderRightColor;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderBottomWidth
     * @return boolean
     */
    public function setContentBorderBottomWidth($contentBorderBottomWidth)
    {
        if (Helper::isValidBorderWidth($contentBorderBottomWidth)) {
            $this->contentBorderBottomWidth = $contentBorderBottomWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderTopWidth
     * @return boolean
     */
    public function setContentBorderTopWidth($contentBorderTopWidth)
    {
        if (Helper::isValidBorderWidth($contentBorderTopWidth)) {
            $this->contentBorderTopWidth = $contentBorderTopWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderLeftWidth
     * @return boolean
     */
    public function setContentBorderLeftWidth($contentBorderLeftWidth)
    {
        if (Helper::isValidBorderWidth($contentBorderLeftWidth)) {
            $this->contentBorderLeftWidth = $contentBorderLeftWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentBorderRightWidth
     * @return boolean
     */
    public function setContentBorderRightWidth($contentBorderRightWidth)
    {
        if (Helper::isValidBorderWidth($contentBorderRightWidth)) {
            $this->contentBorderRightWidth = $contentBorderRightWidth;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentPaddingLeft
     * @return boolean
     */
    public function setContentPaddingLeft($contentPaddingLeft)
    {
        if (Helper::isValidPadding($contentPaddingLeft)) {
            $this->contentPaddingLeft = $contentPaddingLeft;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentPaddingRight
     * @return boolean
     */
    public function setContentPaddingRight($contentPaddingRight)
    {
        if (Helper::isValidPadding($contentPaddingRight)) {
            $this->contentPaddingRight = $contentPaddingRight;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $contentPaddingBottom
     * @return boolean
     */
    public function setContentPaddingBottom($contentPaddingBottom)
    {
        if (Helper::isValidPadding($contentPaddingBottom)) {
            $this->contentPaddingBottom = $contentPaddingBottom;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $contentPaddingTop
     * @return boolean
     */
    public function setContentPaddingTop($contentPaddingTop)
    {
        if (Helper::isValidPadding($contentPaddingTop)) {
            $this->contentPaddingTop = $contentPaddingTop;
            
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param array $arrSettings
     *
     * @return Error
     */
    public function apply($arrSettings)
    {
        $errors = Core::make('helper/validation/error');
        
        if (isset($arrSettings["presetId"])) {
            $this->setPresetId($arrSettings["presetId"]);
        } else {
            $errors->add(t('You must specify a preset id.'));
        }
        
        if (isset($arrSettings["presetName"])) {
            if ($this->setPresetName($arrSettings["presetName"]) === false) {
                $errors->add(t('You must specify a valid preset name.'));
            }
        } else {
            $errors->add(t('You must specify a preset name.'));
        }
        
        if (isset($arrSettings["isSystemPreset"])) {
            if ($this->setIsSystemPreset($arrSettings["isSystemPreset"]) === false) {
                $errors->add(t('You must specify a valid preset type.'));
            }
        } else {
            $errors->add(t('You must specify a preset type.'));
        }
        
        if (isset($arrSettings["titleBackgroundColorNormal"])) {
            if ($this->setTitleBackgroundColorNormal($arrSettings["titleBackgroundColorNormal"]) === false) {
                $errors->add(t('You must specify a valid color for normal title background.'));
            }
        } else {
            $errors->add(t('You must specify a color for normal title background.'));
        }
        
        if (isset($arrSettings["titleBackgroundColorHover"])) {
            if ($this->setTitleBackgroundColorHover($arrSettings["titleBackgroundColorHover"]) === false) {
                $errors->add(t('You must specify a valid color for hovered title background.'));
            }
        } else {
            $errors->add(t('You must specify a color for hovered title background.'));
        }
        
        if (isset($arrSettings["titleBackgroundColorActive"])) {
            if ($this->setTitleBackgroundColorActive($arrSettings["titleBackgroundColorActive"]) === false) {
                $errors->add(t('You must specify a valid color for active title background.'));
            }
        } else {
            $errors->add(t('You must specify a color for active title background.'));
        }
        
        if (isset($arrSettings["titleTextColorNormal"])) {
            if ($this->setTitleTextColorNormal($arrSettings["titleTextColorNormal"]) === false) {
                $errors->add(t('You must specify a valid color for normal title text.'));
            }
        } else {
            $errors->add(t('You must specify a color for normal title text.'));
        }
        
        if (isset($arrSettings["titleTextColorHover"])) {
            if ($this->setTitleTextColorHover($arrSettings["titleTextColorHover"]) === false) {
                $errors->add(t('You must specify a valid color for hovered title text.'));
            }
        } else {
            $errors->add(t('You must specify a color for hovered title text.'));
        }
        
        if (isset($arrSettings["titleTextColorActive"])) {
            if ($this->setTitleTextColorActive($arrSettings["titleTextColorActive"]) === false) {
                $errors->add(t('You must specify a valid color for active title text.'));
            }
        } else {
            $errors->add(t('You must specify a color for active title text.'));
        }
        
        if (isset($arrSettings["titleTextFontWeight"])) {
            if ($this->setTitleTextFontWeight($arrSettings["titleTextFontWeight"]) === false) {
                $errors->add(t('You must specify a valid font weight for the title text.'));
            }
        } else {
            $errors->add(t('You must specify a font weight for the title text.'));
        }
        
        if (isset($arrSettings["titleTextDecoration"])) {
            if ($this->setTitleTextDecoration($arrSettings["titleTextDecoration"]) === false) {
                $errors->add(t('You must specify a valid text decoration for the title text.'));
            }
        } else {
            $errors->add(t('You must specify a text decoration for the title text.'));
        }
        
        if (isset($arrSettings["titleTextFontSize"])) {
            if ($this->setTitleTextFontSize($arrSettings["titleTextFontSize"]) === false) {
                $errors->add(t('You must specify a valid text font size for the title text.'));
            }
        } else {
            $errors->add(t('You must specify a text font size for the title text.'));
        }
        
        if (isset($arrSettings["titleIconNormal"])) {
            if ($this->setTitleIconNormal($arrSettings["titleIconNormal"]) === false) {
                $errors->add(t('You must specify a valid icon for the normal title icon.'));
            }
        } else {
            $errors->add(t('You must specify a icon for the normal title icon.'));
        }
        
        if (isset($arrSettings["titleIconActive"])) {
            if ($this->setTitleIconActive($arrSettings["titleIconActive"]) === false) {
                $errors->add(t('You must specify a valid icon for the active title icon.'));
            }
        } else {
            $errors->add(t('You must specify a icon for the active title icon.'));
        }
        
        if (isset($arrSettings["titleIconOrientation"])) {
            if ($this->setTitleIconOrientation($arrSettings["titleIconOrientation"]) === false) {
                $errors->add(t('You must specify a valid value for the title icon orientation.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title icon orientation.'));
        }
        
        if (isset($arrSettings["titleIconPadding"])) {
            if ($this->setTitleIconPadding($arrSettings["titleIconPadding"]) === false) {
                $errors->add(t('You must specify a valid value for the title icon padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title icon padding.'));
        }
        
        if (isset($arrSettings["titleIconColorNormal"])) {
            if ($this->setTitleIconColorNormal($arrSettings["titleIconColorNormal"]) === false) {
                $errors->add(t('You must specify a valid value for the normal title icon color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the normal title icon color.'));
        }
        
        if (isset($arrSettings["titleIconColorHover"])) {
            if ($this->setTitleIconColorHover($arrSettings["titleIconColorHover"]) === false) {
                $errors->add(t('You must specify a valid value for the hovered title icon color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the hovered title icon color.'));
        }
        
        if (isset($arrSettings["titleIconColorActive"])) {
            if ($this->setTitleIconColorActive($arrSettings["titleIconColorActive"]) === false) {
                $errors->add(t('You must specify a valid value for the active title icon color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the active title icon color.'));
        }
        
        if (isset($arrSettings["titleIconBackgroundColorNormal"])) {
            if ($this->setTitleIconBackgroundColorNormal($arrSettings["titleIconBackgroundColorNormal"]) === false) {
                $errors->add(t('You must specify a valid value for the normal title icon background color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the normal title icon background color.'));
        }
        
        if (isset($arrSettings["titleIconBackgroundColorHover"])) {
            if ($this->setTitleIconBackgroundColorHover($arrSettings["titleIconBackgroundColorHover"]) === false) {
                $errors->add(t('You must specify a valid value for the hovered title icon background color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the hovered title icon background color.'));
        }
        
        if (isset($arrSettings["titleIconBackgroundColorActive"])) {
            if ($this->setTitleIconBackgroundColorActive($arrSettings["titleIconBackgroundColorActive"]) === false) {
                $errors->add(t('You must specify a valid value for the active title icon background color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the active title icon background color.'));
        }
        
        if (isset($arrSettings["titleBorderBottomColor"])) {
            if ($this->setTitleBorderBottomColor($arrSettings["titleBorderBottomColor"]) === false) {
                $errors->add(t('You must specify a valid value for the title border bottom color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border bottom color.'));
        }
        
        if (isset($arrSettings["titleBorderTopColor"])) {
            if ($this->setTitleBorderTopColor($arrSettings["titleBorderTopColor"]) === false) {
                $errors->add(t('You must specify a valid value for the title border top color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border top color.'));
        }
        
        if (isset($arrSettings["titleBorderLeftColor"])) {
            if ($this->setTitleBorderLeftColor($arrSettings["titleBorderLeftColor"]) === false) {
                $errors->add(t('You must specify a valid value for the title border left color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border left color.'));
        }
        
        if (isset($arrSettings["titleBorderRightColor"])) {
            if ($this->setTitleBorderRightColor($arrSettings["titleBorderRightColor"]) === false) {
                $errors->add(t('You must specify a valid value for the title border right color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border right color.'));
        }
        
        if (isset($arrSettings["titleBorderBottomWidth"])) {
            if ($this->setTitleBorderBottomWidth($arrSettings["titleBorderBottomWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the title border bottom width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border bottom width.'));
        }
        
        if (isset($arrSettings["titleBorderTopWidth"])) {
            if ($this->setTitleBorderTopWidth($arrSettings["titleBorderTopWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the title border top width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border top width.'));
        }
        
        if (isset($arrSettings["titleBorderLeftWidth"])) {
            if ($this->setTitleBorderLeftWidth($arrSettings["titleBorderLeftWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the title border left width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border left width.'));
        }
        
        if (isset($arrSettings["titleBorderRightWidth"])) {
            if ($this->setTitleBorderRightWidth($arrSettings["titleBorderRightWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the title border right width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title border right width.'));
        }
        
        if (isset($arrSettings["titlePaddingLeft"])) {
            if ($this->setTitlePaddingLeft($arrSettings["titlePaddingLeft"]) === false) {
                $errors->add(t('You must specify a valid value for the title left padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title left padding.'));
        }
        
        if (isset($arrSettings["titlePaddingRight"])) {
            if ($this->setTitlePaddingRight($arrSettings["titlePaddingRight"]) === false) {
                $errors->add(t('You must specify a valid value for the title right padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title right padding.'));
        }
        
        if (isset($arrSettings["titlePaddingBottom"])) {
            if ($this->setTitlePaddingBottom($arrSettings["titlePaddingBottom"]) === false) {
                $errors->add(t('You must specify a valid value for the title bottom padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title bottom padding.'));
        }
        
        if (isset($arrSettings["titlePaddingTop"])) {
            if ($this->setTitlePaddingTop($arrSettings["titlePaddingTop"]) === false) {
                $errors->add(t('You must specify a valid value for the title top padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the title top padding.'));
        }
        
        if (isset($arrSettings["contentBorderBottomColor"])) {
            if ($this->setContentBorderBottomColor($arrSettings["contentBorderBottomColor"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border bottom color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border bottom color.'));
        }
        
        if (isset($arrSettings["contentBorderTopColor"])) {
            if ($this->setContentBorderTopColor($arrSettings["contentBorderTopColor"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border top color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border top color.'));
        }
        
        if (isset($arrSettings["contentBorderLeftColor"])) {
            if ($this->setContentBorderLeftColor($arrSettings["contentBorderLeftColor"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border left color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border left color.'));
        }
        
        if (isset($arrSettings["contentBorderRightColor"])) {
            if ($this->setContentBorderRightColor($arrSettings["contentBorderRightColor"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border right color.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border right color.'));
        }
        
        if (isset($arrSettings["contentBorderBottomWidth"])) {
            if ($this->setContentBorderBottomWidth($arrSettings["contentBorderBottomWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border bottom width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border bottom width.'));
        }
        
        if (isset($arrSettings["contentBorderTopWidth"])) {
            if ($this->setContentBorderTopWidth($arrSettings["contentBorderTopWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border top width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border top width.'));
        }
        
        if (isset($arrSettings["contentBorderLeftWidth"])) {
            if ($this->setContentBorderLeftWidth($arrSettings["contentBorderLeftWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border left width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border left width.'));
        }
        
        if (isset($arrSettings["contentBorderRightWidth"])) {
            if ($this->setContentBorderRightWidth($arrSettings["contentBorderRightWidth"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border right width.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents border right width.'));
        }
        
        if (isset($arrSettings["contentPaddingLeft"])) {
            if ($this->setContentPaddingLeft($arrSettings["contentPaddingLeft"]) === false) {
                $errors->add(t('You must specify a valid value for the contents left padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents left padding.'));
        }
        
        if (isset($arrSettings["contentPaddingRight"])) {
            if ($this->setContentPaddingRight($arrSettings["contentPaddingRight"]) === false) {
                $errors->add(t('You must specify a valid value for the contents right padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents right padding.'));
        }
        
        if (isset($arrSettings["contentPaddingBottom"])) {
            if ($this->setContentPaddingBottom($arrSettings["contentPaddingBottom"]) === false) {
                $errors->add(t('You must specify a valid value for the contents bottom padding.'));
            }
        } else {
            $errors->add(t('You must specify a value for the contents bottom padding.'));
        }
        
        if (isset($arrSettings["contentPaddingTop"])) {
            if ($this->setContentPaddingTop($arrSettings["contentPaddingTop"]) === false) {
                $errors->add(t('You must specify a valid value for the contents top padding.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents top padding.'));
        }
        
        if (isset($arrSettings["titleHeight"])) {
            if ($this->setTitleHeight($arrSettings["titleHeight"]) === false) {
                $errors->add(t('You must specify a valid value for the title height.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title height.'));
        }
        
        if (isset($arrSettings["titleLineHeight"])) {
            if ($this->setTitleLineHeight($arrSettings["titleLineHeight"]) === false) {
                $errors->add(t('You must specify a valid value for the title line height.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title line height.'));
        }
        
        if (isset($arrSettings["titleBorderTopStyle"])) {
            if ($this->setTitleBorderTopStyle($arrSettings["titleBorderTopStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the title border top style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title border top style.'));
        }
        
        if (isset($arrSettings["titleBorderBottomStyle"])) {
            if ($this->setTitleBorderBottomStyle($arrSettings["titleBorderBottomStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the title border bottom style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title border bottom style.'));
        }
        
        if (isset($arrSettings["titleBorderLeftStyle"])) {
            if ($this->setTitleBorderLeftStyle($arrSettings["titleBorderLeftStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the title border left style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title border left style.'));
        }
        
        if (isset($arrSettings["titleBorderRightStyle"])) {
            if ($this->setTitleBorderRightStyle($arrSettings["titleBorderRightStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the title border right style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title border right style.'));
        }
        
        if (isset($arrSettings["titleMarginTop"])) {
            if ($this->setTitleMarginTop($arrSettings["titleMarginTop"]) === false) {
                $errors->add(t('You must specify a valid value for the title margin top.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title margin top.'));
        }
        
        if (isset($arrSettings["titleMarginBottom"])) {
            if ($this->setTitleMarginBottom($arrSettings["titleMarginBottom"]) === false) {
                $errors->add(t('You must specify a valid value for the title margin bottom.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title margin bottom.'));
        }
        
        if (isset($arrSettings["titleMarginRight"])) {
            if ($this->setTitleMarginRight($arrSettings["titleMarginRight"]) === false) {
                $errors->add(t('You must specify a valid value for the title margin right.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title margin right.'));
        }
        
        if (isset($arrSettings["titleMarginLeft"])) {
            if ($this->setTitleMarginLeft($arrSettings["titleMarginLeft"]) === false) {
                $errors->add(t('You must specify a valid value for the title margin left.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title margin left.'));
        }
        
        if (isset($arrSettings["titleIconMarginTop"])) {
            if ($this->setTitleIconMarginTop($arrSettings["titleIconMarginTop"]) === false) {
                $errors->add(t('You must specify a valid value for the title icon margin top.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title icon margin top.'));
        }
        
        if (isset($arrSettings["titleIconMarginBottom"])) {
            if ($this->setTitleIconMarginBottom($arrSettings["titleIconMarginBottom"]) === false) {
                $errors->add(t('You must specify a valid value for the title icon margin bottom.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title icon margin bottom.'));
        }
        
        if (isset($arrSettings["titleIconMarginLeft"])) {
            if ($this->setTitleIconMarginLeft($arrSettings["titleIconMarginLeft"]) === false) {
                $errors->add(t('You must specify a valid value for the title icon margin left.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title icon margin left.'));
        }
        
        if (isset($arrSettings["titleIconMarginRight"])) {
            if ($this->setTitleIconMarginRight($arrSettings["titleIconMarginRight"]) === false) {
                $errors->add(t('You must specify a valid value for the title icon margin right.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the title icon margin right.'));
        }
        
        if (isset($arrSettings["contentBorderTopStyle"])) {
            if ($this->setContentBorderTopStyle($arrSettings["contentBorderTopStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border top style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents border top style.'));
        }
        
        if (isset($arrSettings["contentBorderBottomStyle"])) {
            if ($this->setContentBorderBottomStyle($arrSettings["contentBorderBottomStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border bottom style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents border bottom style.'));
        }
        
        if (isset($arrSettings["contentBorderRightStyle"])) {
            if ($this->setContentBorderRightStyle($arrSettings["contentBorderRightStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border right style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents border right style.'));
        }
        
        if (isset($arrSettings["contentBorderLeftStyle"])) {
            if ($this->setContentBorderLeftStyle($arrSettings["contentBorderLeftStyle"]) === false) {
                $errors->add(t('You must specify a valid value for the contents border left style.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents border top style.'));
        }
        
        if (isset($arrSettings["contentMarginTop"])) {
            if ($this->setContentMarginTop($arrSettings["contentMarginTop"]) === false) {
                $errors->add(t('You must specify a valid value for the contents top margin.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents top margin.'));
        }
        
        if (isset($arrSettings["contentMarginBottom"])) {
            if ($this->setContentMarginBottom($arrSettings["contentMarginBottom"]) === false) {
                $errors->add(t('You must specify a valid value for the contents bottom margin.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents bottom margin.'));
        }
        
        if (isset($arrSettings["contentMarginLeft"])) {
            if ($this->setContentMarginLeft($arrSettings["contentMarginLeft"]) === false) {
                $errors->add(t('You must specify a valid value for the contents left margin.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents left margin.'));
        }
        
        if (isset($arrSettings["contentMarginRight"])) {
            if ($this->setContentMarginRight($arrSettings["contentMarginRight"]) === false) {
                $errors->add(t('You must specify a valid value for the contents right margin.'));
            }
        } else {
            $errors->add(t('You must specify a valid value for the contents right margin.'));
        }
        
        return $errors;
    }
    
    public function getLessVariables($returnAll = false)
    {
        $lessVars = array();
        
        $lessVars["presetId"] = $this->getPresetId();
        $lessVars["titleBackgroundColorNormal"] = $this->getTitleBackgroundColorNormal();
        $lessVars["titleBackgroundColorHover"] = $this->getTitleBackgroundColorHover();
        $lessVars["titleBackgroundColorActive"] = $this->getTitleBackgroundColorActive();
        $lessVars["titleTextColorNormal"] = $this->getTitleTextColorNormal();
        $lessVars["titleTextColorHover"] = $this->getTitleTextColorHover();
        $lessVars["titleTextColorActive"] = $this->getTitleTextColorActive();
        $lessVars["titleTextFontWeight"] = $this->getTitleTextFontWeight();
        $lessVars["titleTextFontSize"] = $this->getTitleTextFontSize();
        $lessVars["titleTextDecoration"] = $this->getTitleTextDecoration();
        $lessVars["titleIconNormal"] = $this->getTitleIconNormal();
        $lessVars["titleIconActive"] = $this->getTitleIconActive();
        $lessVars["titleIconPadding"] = $this->getTitleIconPadding();
        $lessVars["titleIconColorNormal"] = $this->getTitleIconColorNormal();
        $lessVars["titleIconBackgroundColorNormal"] = $this->getTitleIconBackgroundColorNormal();
        $lessVars["titleIconColorHover"] = $this->getTitleIconColorHover();
        $lessVars["titleIconBackgroundColorHover"] = $this->getTitleIconBackgroundColorHover();
        $lessVars["titleIconColorActive"] = $this->getTitleIconColorActive();
        $lessVars["titleIconBackgroundColorActive"] = $this->getTitleIconBackgroundColorActive();
        $lessVars["titleBorderBottomColor"] = $this->getTitleBorderBottomColor();
        $lessVars["titleBorderTopColor"] = $this->getTitleBorderTopColor();
        $lessVars["titleBorderLeftColor"] = $this->getTitleBorderLeftColor();
        $lessVars["titleBorderRightColor"] = $this->getTitleBorderRightColor();
        $lessVars["titleBorderBottomWidth"] = $this->getTitleBorderBottomWidth();
        $lessVars["titleBorderTopWidth"] = $this->getTitleBorderTopWidth();
        $lessVars["titleBorderLeftWidth"] = $this->getTitleBorderLeftWidth();
        $lessVars["titleBorderRightWidth"] = $this->getTitleBorderRightWidth();
        $lessVars["titlePaddingLeft"] = $this->getTitlePaddingLeft();
        $lessVars["titlePaddingRight"] = $this->getTitlePaddingRight();
        $lessVars["titlePaddingBottom"] = $this->getTitlePaddingBottom();
        $lessVars["titlePaddingTop"] = $this->getTitlePaddingTop();
        $lessVars["contentBorderBottomColor"] = $this->getContentBorderBottomColor();
        $lessVars["contentBorderTopColor"] = $this->getContentBorderTopColor();
        $lessVars["contentBorderLeftColor"] = $this->getContentBorderLeftColor();
        $lessVars["contentBorderRightColor"] = $this->getContentBorderRightColor();
        $lessVars["contentBorderBottomWidth"] = $this->getContentBorderBottomWidth();
        $lessVars["contentBorderTopWidth"] = $this->getContentBorderTopWidth();
        $lessVars["contentBorderLeftWidth"] = $this->getContentBorderLeftWidth();
        $lessVars["contentBorderRightWidth"] = $this->getContentBorderRightWidth();
        $lessVars["contentPaddingLeft"] = $this->getContentPaddingLeft();
        $lessVars["contentPaddingRight"] = $this->getContentPaddingRight();
        $lessVars["contentPaddingBottom"] = $this->getContentPaddingBottom();
        $lessVars["contentPaddingTop"] = $this->getContentPaddingTop();
        $lessVars["titleHeight"] = $this->getTitleHeight();
        $lessVars["titleLineHeight"] = $this->getTitleLineHeight();
        $lessVars["titleBorderTopStyle"] = $this->getTitleBorderTopStyle();
        $lessVars["titleBorderBottomStyle"] = $this->getTitleBorderBottomStyle();
        $lessVars["titleBorderLeftStyle"] = $this->getTitleBorderLeftStyle();
        $lessVars["titleBorderRightStyle"] = $this->getTitleBorderRightStyle();
        $lessVars["titleMarginTop"] = $this->getTitleMarginTop();
        $lessVars["titleMarginBottom"] = $this->getTitleMarginBottom();
        $lessVars["titleMarginLeft"] = $this->getTitleMarginLeft();
        $lessVars["titleMarginRight"] = $this->getTitleMarginRight();
        $lessVars["titleIconMarginTop"] = $this->getTitleIconMarginTop();
        $lessVars["titleIconMarginBottom"] = $this->getTitleIconMarginBottom();
        $lessVars["titleIconMarginLeft"] = $this->getTitleIconMarginLeft();
        $lessVars["titleIconMarginRight"] = $this->getTitleIconMarginRight();
        $lessVars["contentBorderTopStyle"] = $this->getContentBorderTopStyle();
        $lessVars["contentBorderBottomStyle"] = $this->getContentBorderBottomStyle();
        $lessVars["contentBorderRightStyle"] = $this->getContentBorderRightStyle();
        $lessVars["contentBorderLeftStyle"] = $this->getContentBorderLeftStyle();
        $lessVars["contentMarginTop"] = $this->getContentMarginTop();
        $lessVars["contentMarginBottom"] = $this->getContentMarginBottom();
        $lessVars["contentMarginRight"] = $this->getContentMarginRight();
        $lessVars["contentMarginLeft"] = $this->getContentMarginLeft();
        
        if ($returnAll) {
            $lessVars["titleIconOrientation"] = $this->getTitleIconOrientation();
            $lessVars["presetName"] = $this->getPresetName();
            $lessVars["isSystemPreset"] = $this->getIsSystemPreset();
        }
        
        return $lessVars;
    }
    
    public function getLessVariablesAsString()
    {
        $lessCode = "";
        
        foreach ($this->getLessVariables() as $varName => $varValue) {
            $lessCode .= sprintf("@%s: %s;\n", $varName, $varValue);
        }
        
        return $lessCode;
    }
    
    public function getSettingsArray()
    {
        return array(
            array(
                "groupName" => t("General"),
                "items" => array(
                    array(
                        "name" => "presetName",
                        "value" => $this->getPresetName(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Name"),
                        "helptext" => null
                    )
                )
            ),
            array(
                "groupName" => t("Title"),
                "items" => array(
                    array(
                        "name" => "titleBackgroundColorNormal",
                        "value" => $this->getTitleBackgroundColorNormal(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Background color normal"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleHeight",
                        "value" => $this->getTitleHeight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Height"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleLineHeight",
                        "value" => $this->getTitleLineHeight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Line height"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleMarginTop",
                        "value" => $this->getTitleMarginTop(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("margin top"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleMarginBottom",
                        "value" => $this->getTitleMarginBottom(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("margin bottom"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleMarginRight",
                        "value" => $this->getTitleMarginRight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("margin right"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleMarginLeft",
                        "value" => $this->getTitleMarginLeft(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("margin left"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBackgroundColorHover",
                        "value" => $this->getTitleBackgroundColorHover(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Background color hover"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBackgroundColorActive",
                        "value" => $this->getTitleBackgroundColorActive(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Background color active"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleTextColorNormal",
                        "value" => $this->getTitleTextColorNormal(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Text color normal"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleTextColorHover",
                        "value" => $this->getTitleTextColorHover(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Text color hover"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleTextColorActive",
                        "value" => $this->getTitleTextColorActive(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Text color active"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleTextFontWeight",
                        "value" => $this->getTitleTextFontWeight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Text font weight"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleTextFontSize",
                        "value" => $this->getTitleTextFontSize(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Text font size"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleTextDecoration",
                        "value" => $this->getTitleTextDecoration(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Text decoration"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconNormal",
                        "value" => $this->getTitleIconNormal(),
                        "options" => array_merge(array("" => t("(None)")), Helper::getFontAwesomeIcons()),
                        "type" => "select",
                        "disabled" => false,
                        "label" => t("Icon normal"),
                        "helptext" => t("Click <a href=\"%s\" target=\"_blank\">here</a> to see all Font Awesome icons.", "http://www.fontawesome.io/icons/")
                    ),

                    array(
                        "name" => "titleIconActive",
                        "value" => $this->getTitleIconActive(),
                        "options" => array_merge(array("" => t("(None)")), Helper::getFontAwesomeIcons()),
                        "type" => "select",
                        "disabled" => false,
                        "label" => t("Icon active"),
                        "helptext" => t("Click <a href=\"%s\" target=\"_blank\">here</a> to see all Font Awesome icons.", "http://www.fontawesome.io/icons/")
                    ),

                    array(
                        "name" => "titleIconOrientation",
                        "value" => $this->getTitleIconOrientation(),
                        "options" => $this->getOrientations(),
                        "type" => "select",
                        "disabled" => false,
                        "label" => t("Icon orientation"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconPadding",
                        "value" => $this->getTitleIconPadding(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Icon padding"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconColorNormal",
                        "value" => $this->getTitleIconColorNormal(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Icon color normal"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconColorHover",
                        "value" => $this->getTitleIconColorHover(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Icon color hover"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconColorActive",
                        "value" => $this->getTitleIconColorActive(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Icon color active"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconBackgroundColorNormal",
                        "value" => $this->getTitleIconBackgroundColorNormal(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Icon background color active"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconBackgroundColorHover",
                        "value" => $this->getTitleIconBackgroundColorHover(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Icon background color active"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleIconBackgroundColorActive",
                        "value" => $this->getTitleIconBackgroundColorActive(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Icon background color active"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleIconMarginTop",
                        "value" => $this->getTitleIconMarginTop(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Icon margin top"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleIconMarginBottom",
                        "value" => $this->getTitleIconMarginBottom(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Icon margin bottom"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleIconMarginRight",
                        "value" => $this->getTitleIconMarginRight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Icon margin right"),
                        "helptext" => null
                    ),
                    
                    array(
                        "name" => "titleIconMarginLeft",
                        "value" => $this->getTitleIconMarginLeft(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Icon margin left"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderBottomColor",
                        "value" => $this->getTitleBorderBottomColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border bottom color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderTopColor",
                        "value" => $this->getTitleBorderTopColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border top color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderLeftColor",
                        "value" => $this->getTitleBorderLeftColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border left color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderRightColor",
                        "value" => $this->getTitleBorderRightColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border right color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderBottomWidth",
                        "value" => $this->getTitleBorderBottomWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border bottom width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderTopWidth",
                        "value" => $this->getTitleBorderTopWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border top width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderLeftWidth",
                        "value" => $this->getTitleBorderLeftWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border left width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderRightWidth",
                        "value" => $this->getTitleBorderRightWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border right width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderBottomStyle",
                        "value" => $this->getTitleBorderBottomStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border bottom style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderTopStyle",
                        "value" => $this->getTitleBorderTopStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border top style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderLeftStyle",
                        "value" => $this->getTitleBorderLeftStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border left style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titleBorderRightStyle",
                        "value" => $this->getTitleBorderRightStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border right style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titlePaddingLeft",
                        "value" => $this->getTitlePaddingLeft(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding left"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titlePaddingRight",
                        "value" => $this->getTitlePaddingRight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding right"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titlePaddingBottom",
                        "value" => $this->getTitlePaddingBottom(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding bottom"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "titlePaddingTop",
                        "value" => $this->getTitlePaddingTop(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding top"),
                        "helptext" => null
                    )
                )
            ),
            
            array(
                "groupName" => t("Content"),
                "items" => array(
                    array(
                        "name" => "contentBorderBottomColor",
                        "value" => $this->getContentBorderBottomColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border bottom color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderTopColor",
                        "value" => $this->getContentBorderTopColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border top color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderLeftColor",
                        "value" => $this->getContentBorderLeftColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border left color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderRightColor",
                        "value" => $this->getContentBorderRightColor(),
                        "options" => null,
                        "type" => "color",
                        "disabled" => false,
                        "label" => t("Border right color"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderBottomWidth",
                        "value" => $this->getContentBorderBottomWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border bottom width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderTopWidth",
                        "value" => $this->getContentBorderTopWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border top width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderLeftWidth",
                        "value" => $this->getContentBorderLeftWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border left width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderRightWidth",
                        "value" => $this->getContentBorderRightWidth(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border right width"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderBottomStyle",
                        "value" => $this->getContentBorderBottomStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border bottom style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderTopStyle",
                        "value" => $this->getContentBorderTopStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border top style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderLeftStyle",
                        "value" => $this->getContentBorderLeftStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border left style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentBorderRightStyle",
                        "value" => $this->getContentBorderRightStyle(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Border right style"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentPaddingLeft",
                        "value" => $this->getContentPaddingLeft(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding left"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentPaddingRight",
                        "value" => $this->getContentPaddingRight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding right"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentPaddingBottom",
                        "value" => $this->getContentPaddingBottom(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding bottom"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentPaddingTop",
                        "value" => $this->getContentPaddingTop(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Padding top"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentMarginLeft",
                        "value" => $this->getContentMarginLeft(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Margin left"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentMarginRight",
                        "value" => $this->getContentMarginRight(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Margin right"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentMarginBottom",
                        "value" => $this->getContentMarginBottom(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Margin bottom"),
                        "helptext" => null
                    ),

                    array(
                        "name" => "contentMarginTop",
                        "value" => $this->getContentMarginTop(),
                        "options" => null,
                        "type" => "text",
                        "disabled" => false,
                        "label" => t("Margin top"),
                        "helptext" => null
                    )
                )
            )
        );
    }

    public function remove()
    {
        $em = Database::connection()->getEntityManager();

        $em->remove($this);

        $em->flush();
    }

    public function save($recompileCss = false)
    {
        $em = Database::connection()->getEntityManager();

        $em->persist($this);

        $em->flush();
        
        if ($recompileCss) {
            StylePresets::getInstance()->compileCss();
        }
    }
    
    public function export()
    {
        $xmlFile = new \SimpleXMLElement('<AccordionPro></AccordionPro>');

        $xmlFile->addChild("VersionNumber", Package::getByHandle("accordion_pro")->getPackageVersion());

        $stylePreset = $xmlFile->addChild("StylePreset");

        foreach ($this->getLessVariables(true) as $varName => $value) {
            $stylePreset->addChild($varName, $value);
        }
        
        $xmlFileContent = Helper::convertSimpleXmlObjectToText($xmlFile);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $this->getPresetName() . '.xml"');
        header('Content-Length: ' . strlen($xmlFileContent));

        print $xmlFileContent;
        
        exit();
    }
}
