<?php
namespace Sohoa\Framework\Form\Validate {

    class Max extends Validate
    {
        protected $_detail = 'The given value is too long, need <= %s char';
        protected $max = 0;

        protected function _valid($data, $argument)
        {
            $this->_form[$this->_currentName]->setAttribute('type' , 'numeric');

            if (in_array('getOptions', get_class_methods($this->_parent))) {
                throw new Exception("You cant set Max validator on item %s", 0, array(get_class($this->_parent)));
            }
            $this->max = array_shift($argument);
            $this->_form[$this->_currentName]->setAttribute('type' , 'numeric');
            $this->_form[$this->_currentName]->setAttribute('max' , $this->max);

            return (intval($data) <= intval($this->max));
        }

        protected function getDetail()
        {
            return [
                'object'  => get_class($this),
                'message' => sprintf($this->_detail, $this->max),
                'args'    => ['max' => $this->max]
            ];
        }
    }
}