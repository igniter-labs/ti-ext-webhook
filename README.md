<p align="center">
    <a href="https://github.com/igniter-labs/ti-ext-webhook/actions"><img src="https://github.com/igniter-labs/ti-ext-webhook/actions/workflows/pipeline.yml/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/igniterlabs/ti-ext-webhook"><img src="https://img.shields.io/packagist/dt/igniterlabs/ti-ext-webhook" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/igniterlabs/ti-ext-webhook"><img src="https://img.shields.io/packagist/v/igniterlabs/ti-ext-webhook" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/igniterlabs/ti-ext-webhook"><img src="https://img.shields.io/packagist/l/igniterlabs/ti-ext-webhook" alt="License"></a>
</p>

## Introduction

The TastyIgniter Webhooks extension is designed to enhance the integration capabilities of your TastyIgniter website with external systems. It allows you to automate data exchange between your TastyIgniter site and third-party applications, such as Zapier, OpenTable, and various POS systems.

Whether you're triggering actions, syncing data, or integrating workflows, this extension makes it easy to extend the functionality of your platform without custom development.

This extension is built on top of the robust [spatie/laravel-webhook-server](https://github.com/spatie/laravel-webhook-server) package, ensuring reliable and secure webhook delivery.

### Features

- Automatically send data to external services when certain events occur on your TastyIgniter site (e.g. new order, customer registration, status updates).
- Fine-tune each webhook with configurable headers, payloads, and delivery methods.
- Include custom headers or secret tokens to authenticate your webhooks securely with external services.
- Automatically retry failed webhook deliveries and log errors for easier debugging and reliability.
- View a detailed history of webhook deliveries, including response codes and payloads, to ensure transparency and traceability.
- Tested and compatible with popular automation platforms like Zapier, Make (formerly Integromat), automate.io, and more.

## Documentation

More documentation can be found on [here](https://github.com/igniter-labs/ti-ext-webhook/blob/master/docs/index.md).

## Changelog

Please see [CHANGELOG](https://github.com/igniter-labs/ti-ext-webhook/blob/master/CHANGELOG.md) for more information on what has changed recently.

## Reporting issues

If you encounter a bug in this extension, please report it using the [Issue Tracker](https://github.com/igniter-labs/ti-ext-webhook/issues) on GitHub.

## Contributing

Contributions are welcome! Please read [TastyIgniter's contributing guide](https://tastyigniter.com/docs/resources/contribution-guide).

## Security vulnerabilities

For reporting security vulnerabilities, please see [our security policy](https://github.com/igniter-labs/ti-ext-webhook/security/policy).

## License

TastyIgniter Visitor Tracker extension is open-source software licensed under the [MIT license](https://github.com/igniter-labs/ti-ext-webhook/blob/master/LICENSE.md).
