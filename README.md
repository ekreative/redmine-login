# Redmine Login

## Install

    composer require ekreative/redmine_login
    
## Kernel
    
    new Ekreative\RedmineLoginBundle\EkreativeRedmineLoginBundle()
    
## Parameters

    parameters:
        redmine: 'https://redmine.org'
        
## Config

    ekreative_redmine_login:
        redmine: %redmine%
        
## Security

    security:
    
        providers:
            webservice:
                id: ekreative_redmine_login.provider
    
        firewalls:
            unsec:
                pattern: ^/login$
                security: false
    
            secured_area:
                pattern: ^/
                anonymous:
                simple_form:
                    authenticator: ekreative_redmine_login.authenticator
                    check_path:    login_check
                    login_path:    login
                    username_parameter: ekreative_redmine_login[username]
                    password_parameter: ekreative_redmine_login[password]
                simple_preauth:
                    authenticator: ekreative_redmine_login.api_authenticator
                logout:
                    path: /logout
    
        access_control:
            - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/, roles: ROLE_REDMINE }
            
## Make Request

    $projects = json_decode($this->get('ekreative_redmine_login.client_provider')->get($this->getUser())->get('projects.json')->getBody(), true)['projects']

