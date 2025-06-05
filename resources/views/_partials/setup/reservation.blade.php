## Reservation Event

This webhook event is triggered when a reservation is `created`, `updated`, `status_added`, `assigned` or `deleted`.

**Webhook event name:** `reservation`

### Usage

1. To get started, you need to add your receiving URL endpoint, from the third party provider or service you want to
   use.
2. Once you have this URL, please place it into the **Payload URL** field.
3. For a better recognition of the webhook URL, we recommend filling in the **Webhook Name** field.
4. After you've added your **Payload URL**, click the **Save** button to save your entry.
5. That's it! Now you are able to receive data on the URL once the event fires.

### Webhook payload

| Key                    | Type     | Description                                                  |
| ---------------------- | -------- | ------------------------------------------------------------ |
| `action` | `string` | The event type performed. Can be one of: `created` - A new reservation was created, `updated` - The reservation was updated, `status_added` - A new reservation status was added, `deleted` - An existing reservation was deleted. |
| `reservation` | `object` | The reservation object                                         |

### Webhook payload example

```json
{
    "action": "created",
    "reservation": {
        "table_id": 0,
        "status_id": 8,
        "customer_id": null,
        "first_name": "Kirby",
        "last_name": "Velasquez",
        "email": "gemuho@mailinator.com",
        "telephone": "+447960286304",
        "location_id": 1,
        "guest_num": 2,
        "comment": "Aut ea ea sit volupt",
        "reserve_date": "2025-06-26T23:00:00.000000Z",
        "reserve_time": "03:15:00",
        "duration": 45,
        "hash": "5ce89d6aaf74ed54be4edd9262912f70",
        "ip_address": "172.18.0.6",
        "user_agent": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.15; rv:138.0) Gecko\/20100101 Firefox\/138.0",
        "updated_at": "2025-06-04T23:00:00.000000Z",
        "created_at": "2025-06-04T23:00:00.000000Z",
        "reservation_id": 65,
        "customer_name": "Kirby Velasquez",
        "table_name": "",
        "reservation_datetime": "2025-06-27T02:15:00.000000Z",
        "reservation_end_datetime": "2025-06-27T03:00:00.000000Z",
        "status_name": "Pending",
        "location": {
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
        },
        "tables": [],
        "status": {
            "status_id": 8,
            "status_name": "Pending",
            "status_comment": "Your table reservation is pending.",
            "notify_customer": false,
            "status_for": "reservation",
            "status_color": "",
            "created_at": "2023-07-04T20:01:05.000000Z",
            "updated_at": "2023-07-04T20:01:05.000000Z"
        }
    }
}
```
