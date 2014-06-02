<?php
/**
 * Former example
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */

require_once('former/former.php');


/**
 * @Submit(value='Send')
 * @FormRenderer(id='my-contact-form')
 */
class ContactForm extends Former
{
    
    /**
     * @InputField(title='Your name')
     * @Required()
     * @LengthValidator(min=4,max=64)
     */
    public $name;
    
    /**
     * @MailField(title='Your e-mail')
     * @Required()
     */
    public $email;
    
    /**
     * @TextField(title='Your message')
     * @Required()
     * @HtmlFilter()
     * @TextRenderer(class='message-box')
     */
    public $message;

}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Former example</title>
    </head>
    <body>
<?php

$data = array('name' => 'Alice',
              'email' => 'alice@in@wonderla.nd',
              'message' => 'Hi Bob, just wanted to try an </textarea><h1>XSS injection</h1> on you ♥'
             );

$form = new ContactForm($data);

if(count($_GET) && $form->validate($_GET)) {
    // do the job! Your form is valid.
    // send_mail($form->name, $form->mail, $form->message);
    // redirects_somewhere();
    echo 'Great, your form is valid.';
} else {
    /*echo '<pre>';
    var_dump($form);
    echo '</pre>';*/
    echo $form;
}
?>
    </body>
</html>
