## Order Event

This webhook event is triggered when an order is `created`, `updated`, `status_added`, `assigned` or `deleted`.

**Webhook event name:** `order`

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
| `action` | `string` | The event type performed. Can be one of: `placed` - A customer placed an order, `created` - A new order was created, `updated` - The order was updated, `status_added` - A new order status was added, `assigned` - An existing order was assigned to a staff member, `deleted` - An existing order was deleted. |
| `order` | `object` | The order object                                         |

### Webhook payload example

```json
{
    "action": "placed",
    "order": {
        "order_id": 117,
        "customer_id": null,
        "first_name": "Connor",
        "last_name": "Church",
        "email": "fujeqoc@mailinator.com",
        "telephone": "+447592245472",
        "location_id": 1,
        "address_id": null,
        "total_items": 2,
        "comment": "Optio ex vel optio",
        "payment": "cod",
        "order_type": "collection",
        "created_at": "2025-06-05T11:13:19.000000Z",
        "updated_at": "2025-06-05T12:11:31.000000Z",
        "order_time": "13:26",
        "order_date": "2025-06-04T23:00:00.000000Z",
        "order_total": 24,
        "status_id": 1,
        "ip_address": "172.18.0.6",
        "user_agent": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.15; rv:138.0) Gecko\/20100101 Firefox\/138.0",
        "assignee_id": null,
        "assignee_group_id": null,
        "invoice_prefix": "INV-2025-5-00",
        "invoice_date": "2025-06-05T12:11:31.000000Z",
        "hash": "521c63662bb631c38241fd3ae8cfa0e3",
        "processed": true,
        "status_updated_at": null,
        "assignee_updated_at": null,
        "order_time_is_asap": true,
        "delivery_comment": "Ea error laudantium",
        "customer_name": "Connor Church",
        "order_type_name": "Pick-up",
        "order_date_time": "2025-06-05T12:26:00.000000Z",
        "formatted_address": null,
        "status_name": "Received",
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
        "address": null,
        "status": {
            "status_id": 1,
            "status_name": "Received",
            "status_comment": "Your order has been received.",
            "notify_customer": true,
            "status_for": "order",
            "status_color": "#686663",
            "created_at": "2023-07-04T20:01:05.000000Z",
            "updated_at": "2023-07-04T20:01:05.000000Z"
        },
        "payment_method": {
            "order_fee": "0.00",
            "order_status": "1",
            "order_fee_type": "1",
            "payment_id": 1,
            "name": "Cash On Delivery",
            "code": "cod",
            "class_name": "Igniter\\PayRegister\\Payments\\Cod",
            "description": "Accept cash on delivery during checkout",
            "data": {
                "order_fee": "0.00",
                "order_status": "1",
                "order_fee_type": "1"
            },
            "status": 1,
            "is_default": true,
            "priority": 1,
            "created_at": "2023-07-04T20:01:17.000000Z",
            "updated_at": "2023-07-06T20:58:28.000000Z"
        },
        "menu_options": []
        "order_menus": [
            {
            "order_menu_id": 523,
            "order_id": 117,
            "menu_id": 77,
            "name": "ATA RICE",
            "quantity": 2,
            "price": 12,
            "subtotal": 24,
            "option_values": "s:101:\"O:28:\"Igniter\\Cart\\CartItemOptions\":2:{s:8:\"\u0000*\u0000items\";a:0:{}s:28:\"\u0000*\u0000escapeWhenCastingToString\";b:0;}\";",
            "comment": null,
            "menu_options": []
            }
        ],
        "order_totals": {
            {
                "order_total_id": 327,
                "order_id": 117,
                "code": "subtotal",
                "title": "Sub Total",
                "value": 0,
                "priority": 0,
                "is_summable": false
            },
            {
                "order_total_id": 326,
                "order_id": 117,
                "code": "delivery",
                "title": "Delivery",
                "value": 0,
                "priority": 1,
                "is_summable": true
            },
            {
                "order_total_id": 328,
                "order_id": 117,
                "code": "total",
                "title": "Order Total",
                "value": 0,
                "priority": 127,
                "is_summable": false
            }
        }
    }
}
```
