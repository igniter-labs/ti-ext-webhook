## Menu Item Event

This webhook event is triggered when a menu item is `created`, `updated`, `stock_updated` or `deleted`.
The `stock_updated` action will ONLY be triggered during the checkout process

**Webhook event name:** `menu`

### Usage

1. To get started, you need to add your receiving URL endpoint, from the third party provider or service you want to
   use.
2. Once you have this URL, please place it into the **Payload URL** field.
3. For a better recognition of the webhook URL, we recommend filling in the **Webhook Name** field.
4. After you've added your **Payload URL**, click the **Save** button to save your entry.
5. That's it! Now you are able to receive data on the URL once the event fires.

### Webhook payload

| Key              | Type     | Description                                                  |
| ---------------- | -------- | ------------------------------------------------------------ |
| `action`         | `string` | The event type performed. Can be one of: `created` - A new menu item was created, `updated` - The menu item information was updated, `stock_updated` - The menu item stock was updated, `deleted` - An existing menu item was deleted. |
| `menu`       | `object` | The menu item itself                                          |

### Webhook payload example

```json
{
  "action": "created",
  "menu": {
    "special": {},
    "mealtime": {},
    "categories": [],
    "menu_options": [],
    "locations": []  
  }
}
```
