# Documentation

---

### Requirements
- composer

```
// var-dumper for vars during developement
composer require symfony/var-dumper
// fast-route for convinient couting
composer require nikic/fast-route
// twig for view rendering
composer require twig/twig
```

- PHP 8.5.6 

###  Framework

Auto loading setup:

```json
    "autoload": {
        "psr-4": {
            "Araecy\\Framework\\": "src/",
            "App\\": "app/"
        }
    },
```
# NameSpaces
"Araecy\\Framework\\": "src/",
- controllers
    - Renderer
- Http
    - Http request response handling
"App\\": "app/"
- Controllers
    - routing controllers

# public
- index.php
    - frontController

# routes
- web.php
    - GET :: index, return "home page"
    - GET :: books regex = {id:\d+}, return book id from url
    
