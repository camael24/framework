<?php

namespace Sohoa\Framework\Form\Tests\Unit;

require_once __DIR__ . '/../Runner.php';

class Form extends \atoum\test
{
    // TODO : Add Button
    // TODO : Add File
    // TODO : type=numeric, required=required => dans le need(int) or need('required')
/*
   + <form>    Defines an HTML form for user input
   + <input>     Defines an input control
   + <textarea>  Defines a multiline input control (text area)
   + <label>     Defines a label for an <input> element
   - <fieldset>  Groups related elements in a form
   - <legend>    Defines a caption for a <fieldset> element
   + <select>    Defines a drop-down list
   + <optgroup>  Defines a group of related options in a drop-down list
   + <option>    Defines an option in a drop-down list
   - <button>    Defines a clickable button
   - <datalist>  Specifies a list of pre-defined options for input controls
   - <keygen>    Defines a key-pair generator field (for forms)
   - <output>    Defines the result of a calculation
*/
    public function testRadio()
    {
        $fwk      = new \Sohoa\Framework\Framework();
        $form     = $fwk->form('foo');
        $form
            ->action('/user/')
            ->method('post');
        $form[]   = (new \Sohoa\Framework\Form\Radio())
                    ->name('foo')
                    ->option('doo', 'bar', ['id' => 'hello'])
                    ->option('doo', 'bar');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('rpassword')
                    ->type('password')
                    ->label('Confirm password')
                    ->placeholder('Confirm Your password')
                    ->need('required|length:5:|max:5');

        $this->dump($form->render());

        $this
            ->string($form->render())
            ->length
                ->isIdenticalTo(413);
    }

    public function testSelect()
    {
        $fwk      = new \Sohoa\Framework\Framework();
        $form     = $fwk->form('foo');
        $form
            ->action('/user/')
            ->method('post');

        $form[]   = (new \Sohoa\Framework\Form\Select())
                    ->name('foo')
                    ->option('doo', 'bar', ['id' => 'hello'])
                    ->option('doo', 'bar')
                    ->group('Foobar')
                        ->option('a' , 'a', ['aaaaa' => 'bae'])
                        ->option('b' , 'b')
                        ->option('c' , 'c')
                    ->group('Wazaaaa')
                        ->option('a' , 'a')
                        ->option('b' , 'b')
                        ->option('c' , 'c')
                    ->root()
                        ->option('a' , 'a')
                        ->option('b' , 'b')
                        ->option('c' , 'c')
                    ->group('Qux')
                        ->option('a' , 'a')
                        ->option('b' , 'b')
                        ->option('c' , 'c')
                ;
        $this
            ->string($form->render())
            ->length
                ->isIdenticalTo(838);
    }

    public function testLoad()
    {

        $fwk      = new \Sohoa\Framework\Framework();
        $form     = $fwk->form('foo');
        $theme    = $form->getTheme();
        $validate = $form->getValidator();
        $this
            ->object($form)
                ->isInstanceOf('\Sohoa\Framework\Form\Form')
            ->object($theme)
                ->isInstanceOf('\Sohoa\Framework\Form\Theme\Bootstrap')
            ->object($validate)
                ->isInstanceOf('\Sohoa\Framework\Form\Validate\Check')
            ;

    }

    public function testInitForm()
    {
        $form = $this->_form();
        $this
            ->array($form->getChilds())
            ->hasSize(5);

        $this
            ->object($form['login'])
                ->isInstanceOf('\Sohoa\Framework\Form\Input')
                ->array($form['login']->getNeed())
                    ->hasSize(1)
                ->array($form['password']->getNeed())
                    ->hasSize(2)
            ;

    }

    public function testDataValidation()
    {
        $form       = $this->_form();
        $validate   = $form->getValidator();
        $data       = [
            'login'     => 52,
            'password'  => 'aaaaaaaaaaaa',
            'rpassword' => 'aaaaaaaaaaaa',
            'email'     => 'aaaaaaaaaaaa@bar.fr',
            'name'      => 'aaaaaaaaaaaa@bar.fr'
        ];
        $form->fill($data);
        $this
            ->boolean($validate->isValid())
            ->isTrue();

        $data       = [
            'login'     => 80,
            'password'  => 'aaaaaaaaaaaa',
            'rpassword' => 'aaaaaaaaaaaa',
            'email'     => 'aaaaaaaaaaaa@bar.fr',
            'name'      => 'aaaaaaaaaaaa@bar.fr'
        ];
        $form->fill($data);
        $this
            ->boolean($validate->isValid())
                ->isFalse()
            ->array($validate->getErrors())
                ->hasSize(1)
                ->string['login'][0]['object']->isIdenticalTo('Sohoa\Framework\Form\Validate\Praspel')
                ->string['login'][0]['message']->isIdenticalTo('The given value 80 do not match boundinteger(0, 52)')
                ->string['login'][0]['args']['realdom']->isIdenticalTo('boundinteger(0, 52)')
                ->integer['login'][0]['args']['value']->isIdenticalTo(80);

        $data       = [
            'login'     => 5,
            'password'  => 'aaaaa',
            'rpassword' => 'aaaaa',
            'email'     => 'aaa@bar.fr',
            'name'      => 'aaa@bar.fr'
        ];
        $this
            ->boolean($validate->isValid($data))
            ->isTrue();

        $data       = [
            'login'     => 5,
            'password'  => 'aaa',
            'rpassword' => 'aaaaa',
            'email'     => 'aaa@bar.fr',
            'name'      => 'aaa@bar.fr'
        ];
        $this
            ->boolean($validate->isValid($data))
            ->isFalse()
            ->array($validate->getErrors())
                ->hasSize(1)
                ->string['password'][0]['message']->isIdenticalTo('The given value is too long, need >= 5 char');

        $this
            ->boolean($validate->isValid(['aaaa' => 'a']))
            ->isFalse()
            ->array($validate->getErrors())
                ->hasSize(5)
                ->string['login'][0]['message']->isIdenticalTo('The given value NULL do not match boundinteger(0, 52)')

                ->string['password'][0]['message']->isIdenticalTo('This field is required')

                ->string['password'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')
                ->string['password'][1]['object']->isIdenticalTo('Sohoa\Framework\Form\Validate\Length')

                ->integer['password'][1]['args']['length']->isIdenticalTo(0)
                ->string['password'][1]['args']['max']->isIdenticalTo('')
                ->string['password'][1]['args']['min']->isIdenticalTo('5')

                ->string['rpassword'][0]['message']->isIdenticalTo('This field is required')
                ->string['rpassword'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')

                ->string['email'][0]['message']->isIdenticalTo('This field is required')
                ->string['email'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')
                ->string['email'][2]['message']->isIdenticalTo('The given value is not an valid email address')

                ->string['name'][0]['message']->isIdenticalTo('This field is required')
                ->string['name'][1]['message']->isIdenticalTo('The given value is too long, need >= 5 char')
        ;

    }

    protected function _form()
    {

        $fwk      = new \Sohoa\Framework\Framework();
        $form     = $fwk->form('foo');
        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('login')
                    ->label('Login')
                    ->placeholder('Your Login')
                    ->praspel('boundinteger(0, 52)');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('password')
                    ->type('password')
                    ->label('Password')
                    ->placeholder('Your password')
                    ->need('required|length:5:');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('rpassword')
                    ->type('password')
                    ->label('Confirm password')
                    ->placeholder('Confirm Your password')
                    ->need('required|length:5:');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('email')
                    ->type('email')
                    ->label('E-Mail')
                    ->placeholder('Your email we don\'t send spam !')
                    ->need('required|length:5:|email');

        $form[]   = (new \Sohoa\Framework\Form\Input())
                    ->id('name')
                    ->label('Name')
                    ->placeholder('Your name')
                    ->need('required|length:5:');

        return $form;
    }

}