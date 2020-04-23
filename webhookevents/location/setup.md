## Location Event

This webhook event is triggered when a location is `created`, `updated` or `deleted`.

**Webhook event name:** `location`

### Usage

1. To get started, you need to add your receiving URL endpoint, from the third party provider or service you want to use. 
2. Once you have this URL, please place it into the **Payload URL** field.
3. For a better recognition of the webhook URL, we recommend filling in the **Webhook Name** field. 
4. After you've added your **Payload URL**, click the **Save** button to save your entry.
5. That's it! Now you are able to recieve data on the URL once the event fires.

### Webhook payload

| Key         | Type     | Description                                                  |
| ----------- | -------- | ------------------------------------------------------------ |
| `action`    | `string` | The event type performed. Can be one of: `created` - A new location was created, `updated` - The location information was updated, `deleted` - An existing location was deleted. |
| `location`  | `object` | The location itself                                          |

### Webhook payload example

```json
{
  "action": "created",
  "location": {
    "country": {},
    "working_hours": [],
    "delivery_areas": [],
    "tables": []
  }
}
```