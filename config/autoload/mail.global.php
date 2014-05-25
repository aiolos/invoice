<?php
return array(
    'mail' => array(
        'transport' => array(
            'options' => array(
                'name' => 'mail.server',
                'host' => 'mail.host',
                'port'=> 465,
                'connection_class' => 'login',
                'connection_config' => array(
                    'username' => 'username',
                    'password' => 'password',
                    'ssl'=> 'ssl', /* Ssl settings always lead to ssl or tls, even when setting set to false */
                ),
            )
        ),
    ),
);