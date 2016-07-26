# Restful Client Component - CHANGELOG

## v 1.3

- Change: Removed implicit definition of option `CURLOPT_COOKIEJAR` creating a temporary file on each single
request. **Attention: This is a non-BC change and therefore may break your code in rare cases**, 
i.e. only if you somehow make use of the cookie data collected in files written to /tmp/CURLCOOKIE[tmp-name]. 
Please note that using the option `CURLOPT_COOKIEJAR` for itself just dumps received cookie data and doesn't 
*send* any, so it seemed reasonable to make this change in favor of not getting a polluted /tmp directory.
It is very easy though to re-activate the original behavior by using the new method `setCurlOpt`:

```
    $restfulClient = new RestfulClient();
    $restfulClient->setCurlOpt(CURLOPT_COOKIEJAR, tempnam("/tmp", "CURLCOOKIE"));
```


- Feature: Added method `setCurlOpt($name, $value)` allowing the definition of arbitrary curl options 
(curl clients only).

## v 1.2

- Feature: Added method `setTimeout($timeout)` (curl clients only).