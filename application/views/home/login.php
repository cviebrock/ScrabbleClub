<div class="page-header">
	<h1>Login</h1>
</div>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('text', 'username', 'Email',
	array($auth['username'], array('class'=>'span3 required')),
	array('error' => $auth['errors']->first('username'))
);

echo Form::field('password', 'password', 'Password',
	array(array('class'=>'span3 required')),
	array('error' => $auth['errors']->first('password'))
);

echo Form::field('checkbox', 'remember', 'Remember Me',
	array( 'yes', $auth['remember'] ),
	array('help' => '	Be careful checking this on a public computer')
);

echo Form::actions(array(
	Form::submit('Login', array('class' => 'btn-primary')),
	action_link_to_route('home', 'Back Home', array(), 'arrow-left')
));



echo Form::close();

