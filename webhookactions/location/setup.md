## Location Action

This webhook action will `create`, `fetch` or `delete` a location when the webhook url is called.

**Webhook action name:** `location`

### Usage

1. You must always set the webhook `action` parameter.
2. The location_id parameter is always required.
3. All the other parameters are optional.

### Create a location

Creates a new location.

#### Parameters

| Key                  | Type      | Description                                                  |
| -------------------- | --------- | ------------------------------------------------------------ |
| `action`             | `string`  | **Required**. The webhook action to run.                     |
|                      |           |                                                              |
| `password`           | `string`  | The location password, leave blank generate password         |
| `first_name`         | `string`  | The first name of the location                               |
| `last_name`          | `string`  | The last name of the location                                |
| `telephone`          | `string`  | The telephone of the location                                |
| `group_id`           | `integer` | The ID of the group that this location will belong to.       |
| `is_activated`       | `boolean` | Either `true` to activate the location or `false` to deactivate it. Default: `false`. |
| `send_welcome_email` | `boolean` | Either `true` to send a welcome email when creating a new location. Default: `false`. |

#### Payload example

```json
{
  "action": "create",
  "email": "email@domain.tld",
  "first_name": "John",
  "last_name": "Doe",
  "telephone": "123456789",
  "is_activated": true
}
```

#### Response

```html
Status: 201 Created
```

```json
{
  "id": "94894",
  "group": {},
  "address": {},
  "addresses": []
}
```

### Fetch a location

Fetches a location.

#### Parameters

| Key           | Type     | Description                                                 |
| ------------- | -------- | ----------------------------------------------------------- |
| `action`      | `string` | **Required**. The webhook action to run.                    |
| `email`       | `string` | The location email, **optional** when `location_id` is used |
| `location_id` | `string` | The location ID, **optional** when `email` is used          |

#### Payload example

```json
{
  "action": "fetch",
  "email": "example@domain.tld"
}
```

#### Response

```html
Status: 200 OK
```

```json
{
  "id": "94894",
  "group": {},
  "address": {},
  "addresses": []
}
```

### Delete a location

Deletes a location.

#### Parameters

| Key           | Type     | Description                                                 |
| ------------- | -------- | ----------------------------------------------------------- |
| `action`      | `string` | **Required**. The webhook action to run.                    |
| `email`       | `string` | The location email, **optional** when `location_id` is used |
| `location_id` | `string` | The location ID, **optional** when `email` is used          |

#### Payload example

```json
{
  "action": "delete",
  "email": "example@domain.tld"
}
```

#### Response

```html
Status: 200 OK
```

```json
{
  "id": "94894",
  "group": {},
  "address": {},
  "addresses": []
}
```