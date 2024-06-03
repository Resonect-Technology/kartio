# Kartio - The Loyalty Cards Master

- This app provides a simple way for business to issue loayalty cards for their customers.
- It was created as a university project for web appliactions course.

# Architecture

## MVC

- Structure of the app is Model-View-Controller
- The app uses Twig as the templating engine `symfony/twig`.

## Design

- Styling is done using Tailwind CSS with preprocessor integrated with Symfony.
- Components are from Daiy UI which is added as a Tailwind plugin. It has to be installed using NPM like this `npm i -D daisyui@latest`

## Database

- Since the author wanted to try MongoDB it was chosen as the main DB.
- The reason is also economical as NoSQL DBs are cheaper to run in the cloud.
- The required composer packages are `mongodb/mongodb` and `doctrine/mongodb-odm-bundle`.
- Instead of ORM it uses ODM but with the same principles as Doctrine ORM.

# Development

- Secrets are only stored in `.env.local`.

# Security

## Forms

Using Symfony's Form Builder offers several advantages, including better separation of concerns by decoupling form logic from business logic, automatic validation integration ensuring data integrity, ease of form creation and management with a fluent interface, built-in security features like CSRF protection, seamless Twig integration for rendering and customizing form layouts, support for localization and translation, the ability to handle complex and dynamic forms, and extensibility through custom form types and events. These features collectively enhance the maintainability, reusability, and robustness of form handling in Symfony applications.

## SQL Injection

Doctrine ODM takes care of preventing injection attacks in MongoDB by using parameterized queries and escaping data, ensuring that user inputs are never directly interpolated into query strings. It abstracts the complexities of database interactions, making it easier for developers to write secure code. Additionally, when used with Symfony's validation and form handling components, it ensures that only sanitized and validated data is persisted, providing robust protection against injection and other security vulnerabilities.
