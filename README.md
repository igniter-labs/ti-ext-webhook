Webhooks extension allows you to integrate your TastyIgniter site with external systems

It turns your site into a powerful optimized webhook system so that your site can communicate with your third party apps
like Zapier. Under the hood, this extension uses
[spatie/laravel-webhook-server](https://github.com/spatie/laravel-webhook-server)

For example:

- Send data to OpenTable when a new reservation is made on your TastyIgniter website.
- Send data to your POS when a new order is placed on your TastyIgniter website.

### Usage

In the admin user interface:

- Go to **System > Settings > Webhooks Settings** to configure the extension settings
- Go to **Tools > Webhooks** to manage outgoing webhooks.
- Go to **Tools > Automations** to automate outgoing webhooks when certain events happen.

### Features

- Send data when certain events happen in your TastyIgniter platform.
- Advanced settings for each outgoing webhook
- Supports JSON and form urlencode
- Tested with Zapier, automate.io and more.

### Advanced

**Example of Registering Webhook Events**

Here is an example of an extension registering a webhook event to trigger an outgoing webhook.

```php
public function registerWebhookEvents()
{
    return [
        'events' => [
            'customer' => \IgniterLabs\Webhook\WebhookEvents\Customer::class,
        ],
    ];
}
```

**Example of a Webhook Event Class**

A webhook event class is responsible for preparing the parameters passed to the outgoing webhook.

```php
class Customer extends \IgniterLabs\Webhook\WebhookEvents\BaseEvent
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name' => 'Customers',
            'description' => 'Customer created, updated or deleted.',
            'setup' => '$/igniterlabs/webhook/webhookevents/customer/setup.md',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Admin\Models\Customers_model',
            'updated' => 'eloquent.updated: Admin\Models\Customers_model',
            'deleted' => 'eloquent.deleted: Admin\Models\Customers_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $params = [];
        $customer = array_get($args, 0);
        if ($customer instanceof Customers_model)
            $params['customer'] = $customer->toArray();

        return $params;
    }
}
```

