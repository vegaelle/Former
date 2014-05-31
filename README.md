# Former

Former is a simple and lightweight HTML form handler written in PHP. Its goal is
to avoid the developper to write unnecessary code.

## Usage

Form creation can be done in 2 ways: by extending the Former class, or by using
the build methods. The second choice is meant for dynamic forms.

### Creating a form

#### The classic way

Just write a class extending Former, and write public annotated properties in
it. Each property will be a field, and the annotations will define its behaviour
(field type, validators, filters).

Example:

    /**
     * @CSRF()
     * @Submit(value='Send')
     * @InlineFormRenderer(id='my-contact-form')
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
    
    $form = new ContactForm();

That’s it. Really. Your form has all information about its field types, given by
the \*Field annotations. Some field types has integrated validators, like
MailField that automatically validates the value. The Required annotation is an
alias to RequiredValidator. When validated, the value will then be filtered by
the \*Filter annotations. Each one will sanitize the value.

The fields also have renderers, as well as the form itself. These can be
replaced freely, as you can see in the $message field.

The form itself can has annotations. In this example, we add a hidden CSRF
field, and a submit button, in only one line.

The form instanciation can take 1 or 2 params: the URL to send the data (default
empty, that means that the form validates to the same page), and the HTTP method
(default to get).

#### The dynamic way

What if your form has conditional fields? Well, you may find easier to construct
it programmatically with this syntax:

    $form = new Former();
    $form->addCSRF();
    $form->addSubmit(array('value' =    'Send'));
    $form->setRenderer('InlineFormRenderer', array('id' =    'my-contact-form'));
    
    $field_options = array('title' =    'Your name');
    $validators = array('Required' =    null,
                        'LengthValidator' =    array('min' =    4, 'max' =    64)
                       );
    $form->addField('name', 'InputField', $field_options, $validators);
    
    $field_options = array('title' =    'Your e-mail');
    $validators = array('Required' =    null);
    $form->addField('email', 'MailField', $field_options, $validators);
    
    $field_options = array('title' =    'Your message');
    $validators = array('Required' =    null);
    $filters = array('HtmlFilter' =    array());
    $renderer = array('TextRenderer' =    array('class' =    'message-box'));
    $form->addField('message', 'TextField', $field_options,
                    $validators, $filters, $renderer);

### Using a form

Either your form has been created by the classic or dynamic way, the usage is
the same:

#### Rendering a form

    echo $form;

Wait, what? No $form->getForm()->render()->yolo()? Nope, what’s this shit for?

Your form will be rendered following the renderers. If you want a specific HTML,
set the right renderer at form creation time.

#### Receiving data

It’s great to show a form, but it’s currently the easiest thing to do when
you’re dealing with forms. So, how to work with data?

    if(count($_GET)) {
        if($form->validate($_GET)) {
            // do the job! Your form is valid.
            send_mail($form->name, $form->mail, $form->message);
            redirects_somewhere();
        }
    }
    echo $form;

No else statement, because if the form is invalid, we just have to show it back
to the user, with pre-filled values and error messages.

#### Pre-filling a form

When it comes to an update form, we like to pre-fill it data. All you have to do
is give that data (in an associative array) to the form constructor, or use the
setData() method (for the dynamic way).

    $data = array('name' =    'Alice',
                  'email' =    'alice@wonderla.nd',
                  'message' =    'Hi Bob, Just wanted to send you a dummy message.'
                 );
    // classic way
    $form = new ContactForm($data);
    
    // dynamic way
    $form = new Former();
    // add fields
    $form->setData($data);

When rendered, the form will be pre-filled. The data will be validated, but not
filtered.
