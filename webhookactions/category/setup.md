## Category Action

This webhook action will `create`, `update` or `delete` a category when the webhook url is called.

**Webhook action name:** `category`

### Usage

1. You must always set the webhook `action` parameter.

### Create a category

Creates a new category.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `name` | `string` | **Required**. The category name |

#### Payload example
```json
{
  "action": "create",
  "name": "Appetizer"
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
### Update a category
Updates a category.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `category_id` | `string` | **Required**. The ID of the category to retrieve. |

#### Payload example
```json
{
  "action": "update",
  "category_id": "1"
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
### Delete a category
Deletes a category.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `category_id` | `string` | **Required**. The ID of the category to retrieve. |

#### Payload example
```json
{
  "action": "delete",
  "category_id": "1"
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
