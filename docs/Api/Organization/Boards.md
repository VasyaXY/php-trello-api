Trello Member Boards API
======================

### Get boads related to a given member
```php
$api->organization()->boards()->all(string $id, array $params)
```

### Filter boards related to a given member
```php
$api->organization()->boards()->filter(string $id, string|array $filter)
```

### Get boads a given member is invited to
```php
$api->organization()->boards()->invitedTo(string $id, array $params)
```

### Get a field of a boad a given member is invited to
```php
$api->organization()->boards()->invitedToField(string $id, $field)
```

### Pin a boad for a given member
```php
$api->organization()->boards()->pin(string $id, string $boardId)
```

### Unpin a boad for a given member
```php
$api->organization()->boards()->unpin(string $id, string $boardId)
```

### Board Backgrounds API
```php
$api->organization()->boards()->backgrounds()
```

### Board Stars API
```php
$api->organization()->boards()->stars()
```

