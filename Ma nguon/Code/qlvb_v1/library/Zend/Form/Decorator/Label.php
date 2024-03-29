<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/Abstract.php';

/**
 * Zend_Form_Decorator_Label
 *
 * Accepts the options:
 * - separator: separator to use between label and content (defaults to PHP_EOL)
 * - placement: whether to append or prepend label to content (defaults to prepend)
 * - tag: if set, used to wrap the label in an additional HTML tag
 * - opt(ional)Prefix: a prefix to the label to use when the element is optional
 * - opt(iona)lSuffix: a suffix to the label to use when the element is optional
 * - req(uired)Prefix: a prefix to the label to use when the element is required
 * - req(uired)Suffix: a suffix to the label to use when the element is required
 *
 * Any other options passed will be used as HTML attributes of the label tag.
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Label.php 8648 2008-03-07 19:19:28Z matthew $
 */
class Zend_Form_Decorator_Label extends Zend_Form_Decorator_Abstract
{
    /**
     * Default placement: prepend
     * @var string
     */
    protected $_placement = 'PREPEND';

    /**
     * HTML tag with which to surround label
     * @var string
     */
    protected $_tag;

    /**
     * Set element ID
     * 
     * @param  string $id 
     * @return Zend_Form_Decorator_Label
     */
    public function setId($id)
    {
        $this->setOption('id', $id);
        return $this;
    }

    /**
     * Retrieve element ID (used in 'for' attribute)
     *
     * If none set in decorator, looks first for element 'id' attribute, and 
     * defaults to element name.
     * 
     * @return string
     */
    public function getId()
    {
        $id = $this->getOption('id');
        if (null === $id) {
            if (null !== ($element = $this->getElement())) {
                if (isset($element->id)) {
                    $id = $element->id;
                    $this->setId($id);
                } else {
                    $id = $element->getName();
                    $this->setId($id);
                }
            }
        }

        return $id;
    }

    /**
     * Set HTML tag with which to surround label
     * 
     * @param  string $tag 
     * @return Zend_Form_Decorator_Label
     */
    public function setTag($tag)
    {
        $this->_tag = (string) $tag;
        return $this;
    }

    /**
     * Get HTML tag, if any, with which to surround label
     * 
     * @return void
     */
    public function getTag()
    {
        if (null === $this->_tag) {
            $tag = $this->getOption('tag');
            if (null !== $tag) {
                $this->removeOption('tag');
                $this->setTag($tag);
            }
            return $tag;
        }

        return $this->_tag;
    }

    /**
     * Get class with which to define label
     *
     * Appends either 'optional' or 'required' to class, depending on whether 
     * or not the element is required.
     * 
     * @return string
     */
    public function getClass()
    {
        $class = null;
        if (null !== ($element = $this->getElement())) {
            $class = $element->getAttrib('class');
        }

        if (null === $class) {
            $class = '';
        }

        $decoratorClass = $this->getOption('class');
        if (!empty($decoratorClass)) {
            $class .= ' ' . $decoratorClass;
        }

        $type  = $element->isRequired() ? 'required' : 'optional';

        if (!strstr($class, $type)) {
            $class .= ' ' . $type;
            $class = trim($class);
        }

        return $class;
    }

    /**
     * Load an optional/required suffix/prefix key
     * 
     * @param  string $key 
     * @return void
     */
    protected function _loadOptReqKey($key)
    {
        if (!isset($this->$key)) {
            $value = $this->getOption($key);
            $this->$key = (string) $value;
            if (null !== $value) {
                $this->removeOption($key);
            }
        }
    }

    /**
     * Overloading
     *
     * Currently overloads:
     *
     * - getOptPrefix()
     * - getOptSuffix()
     * - reqOptPrefix()
     * - reqOptSuffix()
     * 
     * @param  string $method 
     * @param  array $args 
     * @return mixed
     * @throws Zend_Form_Exception for unsupported methods
     */
    public function __call($method, $args)
    {
        if ((12 == strlen($method))
            && ('get' == substr($method, 0, 3))
            && (('Prefix' == substr($method, -6))
                || ('Suffix' == substr($method, -6))))
        {
            $position = substr($method, -6);
            $type     = strtolower(substr($method, 3, 3));
            switch ($type) {
                case 'req':
                    $key = 'required' . $position;
                    break;
                case 'opt':
                    $key = 'optional' . $position;
                    break;
                default:
                    require_once 'Zend/Form/Exception.php';
                    throw new Zend_Form_Exception(sprintf('Invalid method "%s" called in Label decorator', $method));
            }

            if (null === ($element = $this->getElement())) {
                $this->_loadOptReqKey($key);
            } elseif (isset($element->$key)) {
                $this->$key = (string) $element->$key;
            } else {
                $this->_loadOptReqKey($key);
            }

            return $this->$key;
        }

        require_once 'Zend/Form/Exception.php';
        throw new Zend_Form_Exception(sprintf('Invalid method "%s" called in Label decorator', $method));
    }

    /**
     * Get label to render
     * 
     * @return void
     */
    public function getLabel()
    {
        if (null === ($element = $this->getElement())) {
            return '';
        }

        $label = $element->getLabel();
        $label = trim($label);

        if (empty($label)) {
            return '';
        }

        if (null !== ($translator = $element->getTranslator())) {
            $label = $translator->translate($label);
        }

        $optPrefix = $this->getOptPrefix();
        $optSuffix = $this->getOptSuffix();
        $reqPrefix = $this->getReqPrefix();
        $reqSuffix = $this->getReqSuffix();
        $separator = $this->getSeparator();

        if (!empty($label)) {
            if ($element->isRequired()) {
                $label = $reqPrefix . $label . $reqSuffix;
            } else {
                $label = $optPrefix . $label . $optSuffix;
            }
        }

        return $label;
    }


    /**
     * Render a label
     * 
     * @param  string $content 
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $label     = $this->getLabel();
        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $tag       = $this->getTag();
        $id        = $this->getId();
        $class     = $this->getClass();
        $options   = $this->getOptions();


        if (empty($label) && empty($tag)) {
            return $content;
        }

        if (!empty($label)) {
            $options['class'] = $class;
            $label = $view->formLabel($element->getName(), trim($label), $options); 
        }

        if (null !== $tag) {
            require_once 'Zend/Form/Decorator/HtmlTag.php';
            $decorator = new Zend_Form_Decorator_HtmlTag();
            $decorator->setOptions(array('tag' => $tag));
            $label = $decorator->render($label);
        }

        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $label;
            case self::PREPEND:
                return $label . $separator . $content;
        }
    }
}
