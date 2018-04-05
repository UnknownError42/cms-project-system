<?php
return array(


    'doc-root'      => '/var/www/share',
    'archive-dir'   => 'archive',
    'delete-dir'   => '.papierkorb',

    'core'          => array(
	
		'cms'       => array(
            'path'      => '/var/www/vorlagen/cms-1.2.2.mm',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
	
        'cms-mas'       => array(
            'path'      => '/var/www/vorlagen/cms-1.2.2.mas',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
		
		'cms-developer-mas'       => array(
            'path'      => '/var/www/vorlagen/cms-1.3.5-dev',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
		
		'cms-developer-mm'       => array(
            'path'      => '/var/www/vorlagen/cms-1.3.5-dev',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
		
		'cms-mm'       => array(
            'path'      => '/var/www/vorlagen/cms-1.2.2.mm',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),

        'mage' => array(
            'path' => '/var/www/mage'
        ),
        
        'tmpl-1-mas'       => array(
            'path'      => '/var/www/vorlagen/template-001.mas',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
        
		'tmpl-1-mm'       => array(
            'path'      => '/var/www/vorlagen/template-001.mm',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
        
		
        'tmpl-2-mas'       => array(
            'path'      => '/var/www/vorlagen/template-002.mas',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
		
		'tmpl-2-mm'       => array(
            'path'      => '/var/www/vorlagen/template-002.mm',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
        
        
        'tmpl-3-mas'       => array(
            'path'      => '/var/www/vorlagen/template-003.mas',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
        
		 'tmpl-3-mm'       => array(
            'path'      => '/var/www/vorlagen/template-003.mm',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
		
        'tmpl-4-mas'       => array(
            'path'      => '/var/www/vorlagen/template-004.mas',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
		
		'tmpl-4-mm'       => array(
            'path'      => '/var/www/vorlagen/template-004.mm',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
        
        'tmpl-5-mas'       => array(
            'path'      => '/var/www/vorlagen/template-005.mas',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
		
		'tmpl-5-mm'       => array(
            'path'      => '/var/www/vorlagen/template-005.mm',
            'writable'  => array(
                'data',
                'public/cache',
                'public/media',
                'public/downloads'
            ),
            'tester-name' => 'R.Czaja',
            'tester-email' => 'romy.czaja@myartside.de'
        ),
        
    ),

    'db'            => array(
        'default'         => array(
            'adapter'       => 'Core\Db\Adapter\MySql',
            'host'          => 'localhost',
            'user'          => 'root',
            'pass'          => 'mas',
            'db'            => 'lighttpd',
        )
    ),


);