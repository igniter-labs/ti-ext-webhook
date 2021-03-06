## Menu Item Action

This webhook action will `create`, `fetch` or `delete` a menu item when the webhook url is called.

**Webhook action name:** `menu`

### Usage

1. You must always set the webhook `action` parameter.

### List menu items
List all menu items in the order that they were created.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |

#### Payload example
```json
{
  "action": "list"
}
```
#### Response
```html
Status: 200 OK
```
```json
[
  {
  "id": "94894",
  "group": {},
  "address": {},
  "addresses": []
 }
]
```
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
Status: 201 Created
```
```json
{
  "id": "94894", 
  "menu_name": "Puff Puff",
  "menu_price": "2.00"
}
```
### Retrieve a menu item
Retrieves a menu item.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `menu_id` | `string` | **Required**. The ID of the menu item to retrieve. |

#### Payload example
```json
{
  "action": "fetch",
  "menu_id": "1"
}
```
#### Response
```html
Status: 200 OK
```
```json
{
  "menu_id": "1",
  "menu_name": "Puff Puff",
  "menu_price": "2.00"
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
  "menu_id": "1",
  "menu_name": "Puff Puff",
  "menu_price": "2.00"
}
```
