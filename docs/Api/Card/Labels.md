Trello Card Labels API
======================

### Set a given card&#039;s labels by label color name
```php
$api->cards()->labels()->set(string $id, array $labels)
```

### Create a new Label on a Card
```php
$api->cards()->labels()->create(string $id, array $params)
```

### Add a given card&#039;s label by label ID
```php
$api->cards()->labels()->attach(string $id, string $labelId)
```

### Remove a given label from a given card by label color name
```php
$api->cards()->labels()->remove(string $id, string $label)
```

### Remove a given label from a given card by label ID
```php
$api->cards()->labels()->detach(string $id, string $labelId)
```

