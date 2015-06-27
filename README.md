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
        
## Routing

    ekreative_redmine_login:
        resource: "@EkreativeRedmineLoginBundle/Resources/config/routing.yml"

        
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
                    username_parameter: login[username]
                    password_parameter: login[password]
                simple_preauth:
                    authenticator: ekreative_redmine_login.api_authenticator
                logout:
                    path: /logout
    
        access_control:
            - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/, roles: ROLE_REDMINE }
            
## Security

You can secure your own routes using two roles

* ROLE_REDMINE
* ROLE_REDMINE_ADMIN

            
## Make Request

    $projects = json_decode($this->get('ekreative_redmine_login.client_provider')->get($this->getUser())->get('projects.json')->getBody(), true)['projects']

## Login as api user
    
    POST /login HTTP/1.1
    Content-Type: application/json
    
    {
        "login": {
            "username": "username",
            "password": "password"
        }
    }

Response

    200 OK
    
    {
      "user": {
        "id": 1,
        "username": "username",
        "firstName": "Name",
        "lastName": "Last",
        "email": "user@domin.com",
        "createdAt": "2000-01-01T00:00:00+00:00",
        "lastLoginAt": "2000-01-01T00:00:00+00:00",
        "apiKey": "your_api_key",
        "status": 1
      }
    }
    
Logged in:

    GET /admin HTTP/1.1
    Host: 127.0.0.1:8000
    X-API-Key: your_api_key

### Api Doc

If you want the api doc to be listed then use [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle/blob/master/Resources/doc/index.md)

#### Kernel

    new Nelmio\ApiDocBundle\NelmioApiDocBundle()
    
#### Routing
    
    NelmioApiDocBundle:
        resource: "@NelmioApiDocBundle/Resources/config/routing.yml"
        prefix:   /api/doc

#### Security

Replace the standard `unsec`

    unsec:
        pattern: ^/(login$|api/doc)
        security: false
