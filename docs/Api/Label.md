Trello Label API
======================

### Find a label by id
```php
$api->labels()->show(string $id, array $params)
```

### Create a label
```php
$api->labels()->create(array $params)
```

### Update a label
```php
$api->labels()->update(string $id, array $params)
```

### Update a label name
```php
$api->labels()->setName(string $id, string $name)
```

### Update a label color
```php
$api->labels()->setColor(string $id, string $color)
```

