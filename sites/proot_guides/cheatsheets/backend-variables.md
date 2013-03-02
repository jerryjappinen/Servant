
# Backend variables

The backend code is a little peculiar in the sense that almost all information is stored in global variables. This cheatsheet tells you what they include at a glance.

The backend is [run in phases](?category=backend&id=basics), to which these global variables roughly correspong. They are listed here in order of availability. In addition to these, we have a global `$errors` array.



## $paths & $p

#### **Example print-out**

	$paths = array(
		'bare' => array(
			'root' => 'path/to/proot/'
			'use' => 'use/'
			'index' => 'backend/'
			'helpers' => 'backend/helpers/'
			'core' => 'backend/core/'
			'hacks' => 'backend/hacks/'
			'utilities' => 'backend/utilities/'
			'actions' => 'backend/actions/'
			'errors' => 'backend/errors/'
			'cache' => 'cache/'
			'apps' => 'apps/'
			'tools' => 'tools/'
		),

		'domain' => array(
			'root' => '/path/to/proot/'
			'use' => '/path/to/proot/use/'
			'index' => '/path/to/proot/backend/'
			'helpers' => '/path/to/proot/backend/helpers/'
			'core' => '/path/to/proot/backend/core/'
			'hacks' => '/path/to/proot/backend/hacks/'
			'utilities' => '/path/to/proot/backend/utilities/'
			'actions' => '/path/to/proot/backend/actions/'
			'errors' => '/path/to/proot/backend/errors/'
			'cache' => '/path/to/proot/cache/'
			'apps' => '/path/to/proot/apps/'
			'tools' => '/path/to/proot/tools/'
		),

		'server' => array(
			'root' => '/Users/eiskis/Documents/Development/path/to/proot/'
			'index' => '/Users/eiskis/Documents/Development/path/to/proot/backend/'
			'helpers' => '/Users/eiskis/Documents/Development/path/to/proot/backend/helpers/'
			'core' => '/Users/eiskis/Documents/Development/path/to/proot/backend/core/'
			'hacks' => '/Users/eiskis/Documents/Development/path/to/proot/backend/hacks/'
			'utilities' => '/Users/eiskis/Documents/Development/path/to/proot/backend/utilities/'
			'actions' => '/Users/eiskis/Documents/Development/path/to/proot/backend/actions/'
			'errors' => '/Users/eiskis/Documents/Development/path/to/proot/backend/errors/'
			'cache' => '/Users/eiskis/Documents/Development/path/to/proot/cache/'
			'apps' => '/Users/eiskis/Documents/Development/path/to/proot/apps/'
			'tools' => '/Users/eiskis/Documents/Development/path/to/proot/tools/'
		)
	);



## $available

Lists of available resources in the system.

#### **Example print-out**

	$available = array(

		'apps' => array(
			'baseline',
			'dump',
			'eiskis',
			'proot',
			'radiate'
		),

		'actions' => array(

			'files' => array(
				'get',
				'list',
				'remove',
				'save',
				'upload'
			),

			'database' => array(
				'add',
				'count',
				'get',
				'link',
				'list',
				'remove',
				'unlink',
				'update'
			),

			'web' => array(
				'behavior',
				'client',
				'template',
				'style'
			)

		)

	);



## $constants

Global, system-wide constants.

[See full details](?category=backend&id=constants).

#### **Example print-out**

	$constants = array();



## $settings

Global system settings that commonly vary per installation.

[See full details](?category=backend&id=settings).

#### **Example print-out**

	$settings = array();



## $request

Digging for and normalizing all the necessary info from the original request (accept headers, language, time, HTTP method etc.).

#### **Example print-out**

	$request = array();



## $choices

Taking in input parameters, and dropping anything that's suspicious or unnecessary.

#### **Example print-out**

	$choices = array();



## $input

Taking in user input (validation and priorization).

#### **Example print-out**

	$input = array();



## $app

Extract the selected app from the app package, and find out what we need about it.

#### **Example print-out**

	$input = array();



## $action

Run the selected backend action.

#### **Example print-out**

	$action = array();



## $response

Contents of the response, including both headers and body content.

#### **Example print-out**

	$response = array();



## $headers

Valid HTTP header strings generated based on response contents.

#### **Example print-out**

	$headers = array();



## $output

Composed, valid body content ready to be outputted to client.

#### **Example print-out**

	$output = array();
