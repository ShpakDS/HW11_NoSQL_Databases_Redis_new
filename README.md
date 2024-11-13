### **HW11. NoSQL Databases: Redis**

Build master - slave redis cluster.
Try all eviction strategies.
Write a wrapper for Redis Client that implements probabilistic cache.

## Setup

1. `docker compose up`
2. Open first terminal `docker exec -it php-app bash` and run `php main.php`
3. Open first terminal `docker exec -it php-app bash` and run `php main.php`

## Results

There was implemented probabilistic cache and its optimization

### First example:
`php main.php`
````
Retrieved from fallback
object(Content)#6 (3) {
["id"]=>
int(1)
["title"]=>
string(5) "Title"
["body"]=>
string(4) "Body"
}
````

### Second example:
`php main.php`
````
Retrieved from cache
object(Content)#6 (3) {
  ["id"]=>
  int(1)
  ["title"]=>
  string(5) "Title"
  ["body"]=>
  string(4) "Body"
}
````

### Third example:
`php main.php`
````
Another process updates the cache
Another process updates the cache
Retrieved from fallback
object(Content)#6 (3) {
  ["id"]=>
  int(1)
  ["title"]=>
  string(5) "Title"
  ["body"]=>
  string(4) "Body"
}
````