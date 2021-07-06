## Category Action

This webhook action will `create`, `fetch` or `delete` a category when the webhook url is called.

**Webhook action name:** `category`

### Usage

1. You must always set the webhook `action` parameter.

### List categories
List all categories in the order that they were created.

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
  "name": {},
  "description": {},
  "locations": []
 }
]
```
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
Status: 201 Created
```
```json
{
  "id": "94894", 
  "name": "Appetizer"
}
```
### Retrieve a category
Retrieves a category.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `category_id` | `string` | **Required**. The ID of the category to retrieve. |

#### Payload example
```json
{
  "action": "fetch",
  "category_id": "1"
}
```
#### Response
```html
Status: 200 OK
```
```json
{
  "category_id": "1",
  "name": "Appetizer"
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
  "category_id": "1",
  "name": "Appetizer"
}
```
