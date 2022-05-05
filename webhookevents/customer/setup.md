## Customer Event

This webhook event is triggered when a customer is `created`, `updated` or `deleted`.

**Webhook event name:** `customer`

### Usage

1. To get started, you need to add your receiving URL endpoint, from the third party provider or service you want to
   use.
2. Once you have this URL, please place it into the **Payload URL** field.
3. For a better recognition of the webhook URL, we recommend filling in the **Webhook Name** field.
4. After you've added your **Payload URL**, click the **Save** button to save your entry.
5. That's it! Now you are able to receive data on the URL once the event fires.

### Webhook payload

| Key    | Type    | Description    |
| ------ | ------- | -------------- |
| `action` | `string` | The action performed. Can be one of: `created` - A new customer was created, `updated` - The custmer information was updated, `deleted` - An existing customer was deleted. |
| `customer` | `object` | The customer itself |

### Webhook payload example

```json
{
  "action": "created",
  "customer": {
    "group": {},
    "address": {},
    "addresses": []
  }
}
```
