<?php

/*
ejecutar con php -f example.php

**/
  
  
 // LOAD TEMPLATE ///////////////////////////////////////////////////////////////////////////////////////////

 include 'Form.php';
 
 $form = New Form;
 $form->SetMsgs('New Words, ...'); // Settings messages for captcha
 $form->ReturnOutput(false); // TRUE = return output, FALSE = echo output
 
 
  //LOAD TEMPLATE
 $form->AssignTemplate('template_example.tpl');
 
 
 
 // ADD ELEMENTS ////////////////////////////////////////////////////////////////////////////////////////////
 
 $form->AddElement('input', 'name', 'Name:', 'a'); 

  $form->HTML('name', 'class="myclass"'); //U can set html atributes for elements, like "class", "onclick"...

 $form->AddElement('select', 'sel1', 'Select:', '2', array('Frist'=>'1', 'Header'=>array('Second'=>'2', 'Text'=>'test'), 'Third'=>'3')); //HTML select
 
 $form->AddElement('password', 'heslo1', 'Heslo1:', ''); //HTML password
 $form->AddElement('password', 'heslo2', 'Heslo2:', ''); //HTML password
 
 $form->AddElement('checkbox', 'check', 'Checkbox:', FALSE, array('Prvy'=>'1')); //HTML checkbox
 $form->AddElement('radio', 'radios', 'Radios:', '3', array('First'=>'1', 'Second'=>'2', 'Third'=>'3'));  //HTML radio buttons
  
 $form->AddElement('submit', 'submitbutton', '','Submit form'); //HTML submit button
 $form->AddElement('captcha', 'captch', 'Captcha:', '');  //HTML catpcha generator
 
 
 // RULES ///////////////////////////////////////////////////////////////////////////////////////////////////
 
 $form->Rule('meno', 'MINSIZE', 'This element must have atleast {N} chars.|3'); //SET MINIMAL STRING LENGTH
 $form->Rule('meno', 'MAXSIZE', 'This element can have max {N} chars.|8'); //SET MAXIMUM STRING LENGTH 
 $form->Rule('meno', 'EMAIL', 'Please enter valid email adress!'); //This rule can validate your email inputs
 $form->Rule('heslo1', 'EQUAL', 'Passwords must be equals!|heslo2'); //Typical rule for validating passwords...
 $form->Rule('check', 'MUSTBE', 'Checkbox must be checked!'); //Elements must be checked... 
 $form->Rule('name', 'REGEXP', 'Enter valid string|/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/'); //RULE > REGULAR EXPRESSION, but please remeber.. you creating regexp for php + js ;)
  
  
  //Rule for catpcha has been generated automatically
  
 
 //VALIDATION 
 if($form->IsSubmit())
 {
     
   if($form->Valid())
   {
    
    $out .= 'Ok';  //Form is ok
   
   }
   
 }else
 {
 
   $form->GenerateForm(); //GENERATE HTML CODE
 
 } 
  

