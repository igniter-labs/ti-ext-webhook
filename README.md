Webhooks extension allows you to perform certain action on your TastyIgniter from somewhere else. 

It turns your site into a powerful optimized webhook system so that your site can communicate with 
your third party apps like Zapier. Under the hood, this extension uses 
[spatie/laravel-webhook-server](https://github.com/spatie/laravel-webhook-server) 
and [spatie/laravel-webhook-client](https://github.com/spatie/laravel-webhook-client) 

### Usage

In the admin user interface:
- Go to **System > Settings > Webhooks Settings** to configure the extension settings
- Go to **Tools > Webhooks** to manage outgoing and incoming webhooks. For example:
- Go to **Tools > Automations** to automate outgoing webhooks when certain events happen. 

For example:
- Create a TastyIgniter customer account as soon as a new signup happens on OpenTable.
- Create a TastyIgniter menu item from a POS system.
- Send data to your POS when a new order is placed on your TastyIgniter website.

### Features

- Receive data to a custom webhook action
- Send data when certain events happen in your TastyIgniter platform.
- Authenticate every webhook sent using a Token.
- Advanced settings for each outgoing/incoming webhook
- Supports JSON and form urlencode
- Supports Zapier, automate.io and more.

### Advanced

**Example of Registering Webhook Events**

Here is an example of an extension registering a webhook event to trigger an outgoing webhook.

```php
public function registerWebhookTypes()
{
    return [
        'events' => [
            'customer' => \IgniterLabs\Webhook\WebhookEvents\Customer::class,
        ],
    ];
}
```

**Example of Registering Webhook Actions**

Here is an example of an extension registering a webhook action to be called when an incoming webhook is received.

```php
public function registerWebhookTypes()
{
    return [
        'actions' => [
            'customer' => \IgniterLabs\Webhook\WebhookActions\Customer::class,
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

**Example of a Webhook Action Class**

A webhook action class is responsible for processing the incoming webhook.

```php
class Customer extends \IgniterLabs\Webhook\WebhookActions\BaseAction
{
    /**
     * Returns information about this action, including name and description.
     */
    public function actionDetails()
    {
        return [
            'name' => 'Customers',
            'description' => 'Create, update or delete a customer.',
        ];
    }

    public function registerEntryPoints()
    {
        return [
            'create' => 'processCreateAction',
            'update' => 'processUpdateAction',
            'delete' => 'processDeleteAction',
        ];
    }

    public function processCreateAction($entryPoint)
    {
        // Do something
    }

    public function processUpdateAction($entryPoint)
    {
        // Do something
    }

    public function processDeleteAction($entryPoint)
    {
        // Do something
    }
}
```

