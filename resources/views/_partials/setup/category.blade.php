## Category Event

This webhook event is triggered when a category is `created`, `updated` or `deleted`.

**Webhook event name:** `category`

### Usage

1. To get started, you need to add your receiving URL endpoint, from the third party provider or service you want to
   use.
2. Once you have this URL, please place it into the **Payload URL** field.
3. For a better recognition of the webhook URL, we recommend filling in the **Webhook Name** field.
4. After you've added your **Payload URL**, click the **Save** button to save your entry.
5. You can now select the event type you want to receive notifications for. In this case, select **Categories**.
5. That's it! Now you are able to receive data on the URL once the event fires.

### Webhook payload

| Key              | Type     | Description                                                  |
| ---------------- | -------- | ------------------------------------------------------------ |
| `action`         | `string` | The event type performed. Can be one of: `created` - A new category was created, `updated` - The category information was updated, `deleted` - An existing category was deleted. |
| `category`       | `object` | The category object                                          |

### Webhook payload example

```json
{
    "action": "updated",
    "category": {
        "category_id": 11,
        "name": "Test category",
        "description": "Voluptas sit sint od",
        "parent_id": 10,
        "priority": 12,
        "status": 1,
        "nest_left": 4,
        "nest_right": 5,
        "permalink_slug": "test-category",
        "created_at": "2025-05-18T17:25:02.000000Z",
        "updated_at": "2025-06-05T11:34:51.000000Z",
        "media": [
            {
                "id": 8,
                "disk": "public",
                "name": "68417c6568e3a119700657.jpg",
                "file_name": "test-category.jpg",
                "mime_type": "image\/jpeg",
                "size": 74240,
                "tag": "thumb",
                "custom_properties": [],
                "priority": 6,
                "created_at": "2025-06-05T11:15:49.000000Z",
                "updated_at": "2025-06-05T11:15:49.000000Z",
                "path": "https:\/\/example.com\/storage\/media\/attachments\/public\/684\/17c\/656\/68417c6568e3a119700657.jpg",
                "extension": "jpg"
            }
        ],
        "locations": [
            {
                "location_id": 1,
                "location_name": "Default",
                "location_email": "admin@domain.tld",
                "description": null,
                "location_address_1": "24 Restaurant Pl",
                "location_address_2": null,
                "location_city": "Chelmsford",
                "location_state": null,
                "location_postcode": "CM1 4UG",
                "location_country_id": null,
                "location_telephone": "8765456789",
                "location_lat": 51.3894172,
                "location_lng": 0.1845193,
                "location_radius": null,
                "location_status": 1,
                "permalink_slug": "default",
                "is_default": false,
                "created_at": "2023-07-04T20:01:05.000000Z",
                "updated_at": "2025-06-01T11:02:43.000000Z",
                "is_auto_lat_lng": 1,
                "location_thumb": "https:\/\/tasty.test\/storage\/media\/attachments\/public\/66b\/260\/aab\/thumb_3_0_0_contain_48a4b15e.jpg",
                "media": [
                    {
                        "id": 3,
                        "disk": "public",
                        "name": "66b260aab3be4631201551.jpg",
                        "file_name": "default.jpg",
                        "mime_type": "image\/jpeg",
                        "size": 74240,
                        "tag": "thumb",
                        "custom_properties": [],
                        "priority": 2,
                        "created_at": "2024-08-06T17:43:06.000000Z",
                        "updated_at": "2024-08-06T17:43:06.000000Z",
                        "path": "https:\/\/tasty.test\/storage\/media\/attachments\/public\/66b\/260\/aab\/66b260aab3be4631201551.jpg",
                        "extension": "jpg"
                    }
                ]
            }
        ]
    }
}
```
