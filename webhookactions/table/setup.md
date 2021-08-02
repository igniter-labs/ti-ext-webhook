## Table Action

This webhook action will `create`, `update` or `delete` a table when the webhook url is called.

**Webhook action name:** `location`

### Usage

1. You must always set the webhook `action` parameter.
2. The table_id parameter is always required.
3. All the other parameters are optional.

### Create a table

Creates a new table.

#### Parameters

| Key                  | Type      | Description                                                  |
| -------------------- | --------- | ------------------------------------------------------------ |
| `action`             | `string`  | **Required**. The webhook action to run.                     |
| `table_name`         | `string`  | The name of the table                               |

#### Payload example

```json
{
  "action": "create",
  "table_name": "Table 1"
}
```

#### Response

```html
Status: 200 OK
```

```json
{
    "message": "ok"
}
```

### Update a table

Updates a table.

#### Parameters

| Key           | Type     | Description                                                 |
| ------------- | -------- | ----------------------------------------------------------- |
| `action`      | `string` | **Required**. The webhook action to run.                    |
| `table_id`      | `string` | **Required**. The table ID.                    |
| `table_name`         | `string`  | The name of the table                               |

#### Payload example

```json
{
  "action": "update",
  "table_id": "1",
  "table_name": "Table One"
}
```

#### Response

```html
Status: 200 OK
```

```json
{
    "message": "ok"
}
```

### Delete a table

Deletes a table.

#### Parameters

| Key           | Type     | Description                                                 |
| ------------- | -------- | ----------------------------------------------------------- |
| `action`      | `string` | **Required**. The webhook action to run.                    |
| `table_id`      | `string` | **Required**. The table ID.                    |

#### Payload example

```json
{
  "action": "delete",
    "table_id": "1"
}
```

#### Response

```html
Status: 200 OK
```

```json
{
    "message": "ok"
}
```
