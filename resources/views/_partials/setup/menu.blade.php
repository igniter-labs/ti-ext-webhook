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
| `menu`       | `object` | The menu item object                                          |

### Webhook payload example

```json
{
    "action": "updated",
    "menu": {
        "menu_id": 88,
        "menu_name": "Roasted Chicken",
        "menu_description": "Rem sint qui offici",
        "menu_price": 8.94,
        "minimum_qty": 1,
        "menu_status": 1,
        "menu_priority": 0,
        "order_restriction": [
            "collection"
        ],
        "created_at": "2025-06-05T11:55:36.000000Z",
        "updated_at": "2025-06-05T11:56:53.000000Z",
        "is_pending_review": 0,
        "stock_qty": 0,
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
        ],
        "stocks": [],
        "ingredients": [],
        "media": [],
        "menu_options": [
            {
                "menu_option_id": 21,
                "option_id": 6,
                "menu_id": 88,
                "is_required": false,
                "priority": 0,
                "min_selected": 0,
                "max_selected": 0,
                "created_at": "2025-06-05T11:56:13.000000Z",
                "updated_at": "2025-06-05T11:56:13.000000Z",
                "option_name": "Toppers",
                "display_type": "select",
                "menu_option_values": [],
                "option": {
                    "option_id": 6,
                    "option_name": "Toppers",
                    "display_type": "select",
                    "priority": 0,
                    "created_at": "2024-07-10T09:17:55.000000Z",
                    "updated_at": "2024-07-10T09:17:55.000000Z",
                }
            },
            {
                "menu_option_id": 22,
                "option_id": 3,
                "menu_id": 88,
                "is_required": false,
                "priority": 0,
                "min_selected": 0,
                "max_selected": 0,
                "created_at": "2025-06-05T11:56:14.000000Z",
                "updated_at": "2025-06-05T11:56:14.000000Z",
                "option_name": "Size",
                "display_type": "radio",
                "menu_option_values": [],
                "option": {
                    "option_id": 3,
                    "option_name": "Size",
                    "display_type": "radio",
                    "priority": 0,
                    "created_at": "2023-07-04T20:01:05.000000Z",
                    "updated_at": "2023-07-04T20:01:05.000000Z",
                    "square_id": null,
                    "square_version": null
                }
            }
        ],
        "special": {
            "special_id": 9,
            "menu_id": 88,
            "start_date": "2025-07-17T21:22:00.000000Z",
            "end_date": "2025-08-29T21:22:00.000000Z",
            "special_price": 23,
            "special_status": 0,
            "type": "F",
            "validity": "period",
            "recurring_every": null,
            "recurring_from": null,
            "recurring_to": null,
            "created_at": null,
            "updated_at": null
        },
        "categories": [
            {
                "category_id": 1,
                "name": "Appetizer",
                "description": "Sed consequat, sapien in scelerisque egestas, neque nisi dapibus magna, non malesuada lectus ligula vel justo. Vestibulum felis nisi, tincidunt eu est quis, faucibus tincidunt ante.",
                "parent_id": null,
                "priority": 1,
                "status": 1,
                "nest_left": null,
                "nest_right": null,
                "permalink_slug": "appetizer",
                "created_at": "2023-07-04T20:01:05.000000Z",
                "updated_at": "2023-07-04T20:01:06.000000Z",
                "square_id": null,
                "square_version": null
            }
        ],
        "mealtimes": [
            {
                "mealtime_id": 1,
                "mealtime_name": "Breakfast",
                "start_time": "07:00:00",
                "end_time": "10:00:00",
                "mealtime_status": 1,
                "created_at": "2023-07-04T20:01:05.000000Z",
                "updated_at": "2023-07-04T20:01:05.000000Z"
            }
        ]
    }
}
```
