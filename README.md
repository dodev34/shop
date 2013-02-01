Install
====

Attention : Ce module ne sert absolument à rien ^^ pour le moment...

appKernel.php
```
new Mitch\Bundle\ShopBundle\MitchShopBundle(),
```

routing.yml
```
mitch_shop:
    resource: "@MitchShopBundle/Resources/config/routing.yml"
    prefix:   /shop
```

config.yml
```
# Assetic Configuration
assetic:
    bundles: [ MitchShopBundle ]
```
