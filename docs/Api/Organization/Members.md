Trello Board Members API
======================

### Get a given board&#039;s members
```php
$api->organization()->members()->all(string $id, array $params)
```

### Remove a given member from a given board
```php
$api->organization()->members()->remove(string $id, string $memberId)
```

### Filter members related to a given board
```php
$api->organization()->members()->filter(string $id, string|array $filter)
```

### Get a member&#039;s cards related to a given board
```php
$api->organization()->members()->cards(string $id, string $memberId, array $params)
```

### Add member to a given board
```php
$api->organization()->members()->invite(string $id, string $email, string $fullName, string $role)
```

### Get members invited to a given board
```php
$api->organization()->members()->invited(string $id, array $params)
```

### Get a field related to a member invited to a given board
```php
$api->organization()->members()->getInvitedMemberField(string $id, string $field)
```

### Set the role of a user or an organization on a given board
```php
$api->organization()->members()->setRole(string $id, string $memberOrOrganization, $role)
```

