<?php
namespace Application\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\InputFilter\InputInterface;

class FormElementStaticText extends AbstractHelper
{
    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormElement
     */
    public function __invoke(ElementInterface $element = null, InputInterface $filter = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $filter);
    }

    public function render(ElementInterface $element, InputInterface $filter = null)
    {
        $name = $element->getName();
        if ($name === null || $name === '') {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none given',
                __METHOD__
            ));
        }

        $attributes          = $element->getAttributes();
        $attributes['name']  = $name;

        return sprintf(
            '<span %s>%s</span>',
            $this->createAttributesString($attributes),
            $this->getValue($element)
        );
    }

    /**
     * Create a string of all attribute/value pairs
     *
     * Escapes all attribute values
     *
     * @param  array $attributes
     * @return string
     */
    public function createAttributesString(array $attributes)
    {
        $attributes = $this->prepareAttributes($attributes);
        $escape     = $this->getEscapeHtmlHelper();
        $strings    = array();
        foreach ($attributes as $key => $value) {
            $key = strtolower($key);
            if (!$value && isset($this->booleanAttributes[$key])) {
                // Skip boolean attributes that expect empty string as false value
                if ('' === $this->booleanAttributes[$key]['off']) {
                    continue;
                }
            }

            //check if attribute is translatable
            if (isset($this->translatableAttributes[$key]) && !empty($value)) {
                if (($translator = $this->getTranslator()) !== null) {
                    $value = $translator->translate(
                            $value, $this->getTranslatorTextDomain()
                    );
                }
            }

            //@TODO Escape event attributes like AbstractHtmlElement view helper does in htmlAttribs ??
            $strings[] = sprintf('%s="%s"', $escape($key), $escape($value));
        }
        return implode(' ', $strings);
    }

    /**
     * Prepare attributes for rendering
     *
     * Ensures appropriate attributes are present (e.g., if "name" is present,
     * but no "id", sets the latter to the former).
     *
     * Removes any invalid attributes
     *
     * @param  array $attributes
     * @return array
     */
    protected function prepareAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $attribute = strtolower($key);

            if (!isset($this->validGlobalAttributes[$attribute])
                && !isset($this->validTagAttributes[$attribute])
                && 'data-' != substr($attribute, 0, 5)
            ) {
                // Invalid attribute for the current tag
                unset($attributes[$key]);
                continue;
            }

            // Normalize attribute key, if needed
            if ($attribute != $key) {
                unset($attributes[$key]);
                $attributes[$attribute] = $value;
            }

            // Normalize boolean attribute values
            if (isset($this->booleanAttributes[$attribute])) {
                $attributes[$attribute] = $this->prepareBooleanAttributeValue($attribute, $value);
            }
        }

        return $attributes;
    }

    protected function getValue(ElementInterface $element)
    {
        $value = $element->getValue();

        if (!is_object($value)) {
            return $value;
        }

        if (method_exists($value, '__toString')) {
            return $value->toString();
        }

        switch (get_class($value)) {
            case 'DateTime':
                return $value->format('d-m-Y h:i:s');
            default:
                throw new \Exception(get_class($value));
        }
    }
}
