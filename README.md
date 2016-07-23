Exchange Rates
==============

Агрегатор курсов валют с разных источников.

Для загрузки курсов запустить:

    $ bin/console rates:load

REST API по роутам:

Name | Method | Scheme | Host | Path
---- | ------ | ------ | ---- | ----
get_currency | GET | ANY | ANY | /api/currencies/{currency}.{_format}
post_currency | POST | ANY | ANY | /api/currencies.{_format}
put_currency | PUT | ANY | ANY | /api/currencies/{currency}.{_format}
patch_currency | PATCH | ANY | ANY | /api/currencies/{currency}.{_format}
delete_currency | DELETE | ANY | ANY | /api/currencies/{currency}.{_format}
get_currencies | GET | ANY | ANY | /api/currencies.{_format}
get_exchangerate | GET | ANY | ANY | /api/exchangerates/{rate}.{_format}
get_exchangerates | GET | ANY | ANY | /api/exchangerates.{_format}
get_exchangeratessource | GET | ANY | ANY | /api/exchangeratessources/{source}.{_format}
post_exchangeratessource | POST | ANY | ANY | /api/exchangeratessources.{_format}
put_exchangeratessource | PUT | ANY | ANY | /api/exchangeratessources/{source}.{_format}
patch_exchangeratessource | PATCH | ANY | ANY | /api/exchangeratessources/{source}.{_format}
delete_exchangeratessource | DELETE | ANY | ANY | /api/exchangeratessources/{source}.{_format}
get_exchangeratessources | GET | ANY | ANY | /api/exchangeratessources.{_format}