## Menu Item Action

This webhook action will `create`, `update` or `delete` a menu item when the webhook url is called.

**Webhook action name:** `menu`

### Usage

1. You must always set the webhook `action` parameter.

### Create a menu item

Creates a new menu item.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `menu_name` | `string` | **Required**. The menu item name |
| `menu_price` | `string` | **Required**. The menu item price |

#### Payload example
```json
{
  "action": "create",
  "menu_name": "email@domain.tld",
  "menu_price": "John"
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
### Update a menu item
Updates a menu item.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `menu_id` | `string` | **Required**. The ID of the menu item to retrieve. |

#### Payload example
```json
{
  "action": "update",
  "menu_id": "1"
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
### Delete a menu item
Deletes a menu item.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `menu_id` | `string` | **Required**. The ID of the menu item to retrieve. |

#### Payload example
```json
{
  "action": "delete",
  "menu_id": "1"
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
