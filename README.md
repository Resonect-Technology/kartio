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
