# simple_php_api

This API is super simple.

It takes 1 string, no key, just a string.

It stores the string into a JSON array in the same filepath as the script is in, data.json.

You can also get from this API all data in this file.

Here are example GET and POST requests.

curl -X GET "https://domain.com/api.php?auth-token=PUT_KEY_HERE"

curl -X POST https://domain.com/api.php \
  -H "Content-Type: application/json" \
  -H "Auth-Token: PUT_KEY_HERE" \
  -d '"Data"'

  
