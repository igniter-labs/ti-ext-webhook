## Customer Action

This webhook action will `create`, `fetch` or `delete` a customer when the webhook url is called.

**Webhook action name:** `customer`

### Usage

1. You must always set the webhook `action` parameter.
2. The customer_id or email parameter is always required.
3. All the other parameters are optional.

### List customers
List all customers in the order that they were created.

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
### Create a customer
Creates a new customer.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `email` | `string` | **Required**. The customer email |
| `password` | `string` | The customer password, leave blank generate password |
| `first_name` | `string` | The first name of the customer |
| `last_name` | `string` | The last name of the customer |
| `telephone` | `string` | The telephone of the customer |
| `group_id` | `integer` | The ID of the group that this customer will belong to. |
| `is_activated` | `boolean` | Either `true` to activate the customer or `false` to deactivate it. Default: `false`. |
| `send_welcome_email` | `boolean` | Either `true` to send a welcome email when creating a new customer. Default: `false`. |

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
### Fetch a customer
Fetches a customer.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `email` | `string` | The customer email, **optional** when `customer_id` is used |
| `customer_id` | `string` | The customer ID, **optional** when `email` is used |

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
### Delete a customer
Deletes a customer.

#### Parameters
| Key        | Type     | Description                                                  |
| ---------- | -------- | ------------------------------------------------------------ |
| `action`   | `string` | **Required**. The webhook action to run. |
| `email` | `string` | The customer email, **optional** when `customer_id` is used |
| `customer_id` | `string` | The customer ID, **optional** when `email` is used |

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
