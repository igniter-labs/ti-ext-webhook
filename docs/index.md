---
title: "WebHooks"
section: "extensions"
sortOrder: 999
---

## Installation

You can install the extension via composer using the following command:

```bash
composer require igniterlabs/ti-ext-webhook -W
```

Run the database migrations to create the required tables:

```bash
php artisan igniter:up
```

## Getting started

From your TastyIgniter Admin, you can manage settings for webhooks by navigating to the **Manage > Settings > Webhooks Settings** admin page. Here you can configure the following:

- **Verify SSL Certificate:** Enable or disable SSL certificate verification for outgoing webhooks. It's recommended to keep this enabled for security.
- **Webhook Signature Header Name:** Set the name of the header used to send the webhook signature. This is used to verify the authenticity of the webhook request.
- **Webhook Timeout:** Set the timeout duration (in seconds) for outgoing webhooks. If a webhook does not respond within this time, it will be considered failed.
- **Webhook Retry Count:** Set the number of times to retry a failed webhook. If a webhook fails, it will be retried this many times before giving up.
- **Additional HTTP Headers:** You can specify additional HTTP headers to include in outgoing webhook requests. This is useful for passing custom data or authentication tokens.

### Managing Webhooks

You can manage webhooks in the admin user interface by navigating to the **Tools > Webhooks** admin page. Here you can:

- View a list of all outgoing webhooks.
- Create new webhooks by clicking the **Add Webhook** button.
- Edit existing webhooks by clicking the **Edit** button next to each webhook.
- Delete webhooks by clicking the **Delete** button next to each webhook.
- View the details of each webhook requests, including the URL, method, headers, and payload.

Each webhook can be configured with the following settings:

- **Name:** A descriptive name for the webhook.
- **Status:** The status of the webhook (enabled or disabled).
- **Payload URL:** The endpoint URL where the webhook will send data.
- **Secret Signature:** A secret key used to sign the webhook payload for verification.
- **Verify SSL:** Whether to verify the SSL certificate of the payload URL.
- **Events:** The events that will trigger the webhook. You can select from a list of available events, such as:
  - Category: Triggered when a category is created, updated, or deleted.
  - Customers: Triggered when a customer is created, updated, or deleted.
  - Menu items: Triggered when a menu item is created, updated, or deleted.
  - Orders: Triggered when an order is created, updated, or deleted.
  - Reservations: Triggered when a reservation is created, updated, or deleted.
  - Tables: Triggered when a table is created, updated, or deleted.
- **Recent Deliveries:** A list of recent webhook deliveries, including the status of each delivery (success or failure) and the response from the payload URL.
- **Setup Instructions:** A setup instructions for the webhook event, which provides guidance on how to configure the webhook for specific events.

### Automating Webhooks

Using Automation Rules, you can automate outgoing webhooks when certain events happen in your TastyIgniter platform by navigating to the **Tools > Automations** admin page. Here you can:

- Create new automation rules by clicking the **Add Automation Rule** button.
- Select the event that will trigger the automation rule. For example, you can choose the `Order Placed Event` event from the dropdown.
- Enter a name for the automation rule in the **Name** field. This will help you identify the rule later.
- In the **Code** field, provide a unique identifier for the automation rule.
- In the **Status** field, select whether you want the automation rule to be active or inactive.
- Under the **Conditions** tab (optional), you can specify any conditions that must be met for the action to be executed. For example, you can send webhooks only for delivery orders by selecting the `Order attribute` condition. Once selected, click on it under the **Conditions** tab to configure it.
- Under the **Actions** tab, select the `Send payload to Webhooks` action from the dropdown. Once selected, click on it under the **Actions** tab to configure it with the following settings:
  - **Url:** The URL where the webhook will send data.
  - **Signature Key:** The secret key used to sign the webhook payload for verification.
- Click on the **Save** button to save the automation rule.
- After saving, the automation rule will be active and will trigger the outgoing webhook based on the specified event and conditions.

## Usage

This section covers how to integrate the Webhooks extension into your own extension if you need to create custom webhook events or extend existing ones.

### Defining Webhook Events

You can define webhook events by creating a class that extends `IgniterLabs\Webhook\WebhookEvents\BaseEvent`. This class should implement the `eventDetails`, `registerEventListeners`, and `makePayloadFromEvent` methods.

A webhook event class is responsible for preparing the parameters passed to the outgoing webhook.

```php
class Customer extends \IgniterLabs\Webhook\WebhookEvents\BaseEvent
{
    protected string $setupPartial = 'author.extension::customer.setup';

    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name' => 'Customers',
            'description' => 'Customer created, updated or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Igniter\User\Models\Customer',
            'updated' => 'eloquent.updated: Igniter\User\Models\Customer',
            'deleted' => 'eloquent.deleted: Igniter\User\Models\Customer',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $params = [];
        $customer = array_get($args, 0);
        if ($customer instanceof Customer)
            $params['customer'] = $customer->toArray();

        return $params;
    }
}
```

The `setupPartial` property is used to define the setup instructions for the webhook event, which can be displayed in the admin interface when configuring the webhook. This should point to a blade view file. In this example, it points to `author.extension::customer.setup`, which would be a view file located in the `resources/views/customer` directory of your extension.

The `eventDetails` method returns an array containing the name and description of the event. This information is used in the admin interface to display the event details.
The `registerEventListeners` method returns an array of event listeners that will trigger the webhook event. In this example, it listens for the `created`, `updated`, and `deleted` events on the `Igniter\User\Models\Customer` model.
The `makePayloadFromEvent` method is responsible for preparing the payload that will be sent to the webhook. It takes the event arguments and returns an array of parameters that will be included in the webhook request. In this example, it retrieves the customer data from the event arguments and includes it in the payload.

### Registering Webhook Events

Once you have defined your webhook event classes, you need to register them in your extension so that they can be used in the admin interface for configuring webhooks.

You can do this by implementing the `registerWebhookEvents` method in your [Extension class](../extend/extensions#extension-class-methods). This method should return an array of events that you want to register.

```php
public function registerWebhookEvents()
{
    return [
        'customer' => \Author\Extension\WebhookEvents\Customer::class,
    ];
}
```

In this example, the `customer` event is registered with the `\Author\Extension\WebhookEvents\Customer` class. This allows the webhook event to be available in the admin interface for configuring webhooks.

### Events

The Webhooks extension dispatches the following events:

- `igniterlabs.webhook.beforeDispatch`: This event is triggered before a webhook is dispatched. You can use this event to modify the webhook payload or perform any actions before the webhook is sent.

See the [Spatie Webhook Server documentation](https://github.com/spatie/laravel-webhook-server) for more events.
