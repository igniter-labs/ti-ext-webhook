## Table Event

This webhook event is triggered when a table is `created`, `updated` or `deleted`.

**Webhook event name:** `table`

### Usage

1. To get started, you need to add your receiving URL endpoint, from the third party provider or service you want to
   use.
2. Once you have this URL, please place it into the **Payload URL** field.
3. For a better recognition of the webhook URL, we recommend filling in the **Webhook Name** field.
4. After you've added your **Payload URL**, click the **Save** button to save your entry.
5. That's it! Now you are able to receive data on the URL once the event fires.

### Webhook payload

| Key         | Type     | Description                                                  |
| ----------- | -------- | ------------------------------------------------------------ |
| `action`    | `string` | The event type performed. Can be one of: `created` - A new table was created, `updated` - The table information was updated, `deleted` - An existing table was deleted. |
| `table`  | `object` | The table object                                          |

### Webhook payload example

```json
{
    "action": "created",
    "table": {
        "priority": 0,
        "extra_capacity": 0,
        "id": 31,
        "dining_area_id": 2,
        "name": "Jasper Small",
        "min_capacity": 2,
        "max_capacity": 6,
        "shape": "rectangle",
        "is_enabled": true,
        "parent_id": null,
        "nest_left": 61,
        "nest_right": 62,
        "updated_at": "2025-06-05T12:44:07.000000Z",
        "created_at": "2025-06-05T12:44:07.000000Z"
    }
}
```
