## To install project

- composer install

- configure database connection in the .env file

- php artisan migrate --seed

## My thoughts

*In case I would have more time to complete the project, I would bet on:*

- Implement CQRS, as it will be a given advantage as we decouple the modules, which are touching each other often.
For this type of application seems to be really helpful, and we avoid problems with circular dependencies in the future.
- Implement hexagonal architecture. Now the whole skeleton is done, so it will be a big challenge, but I would consider
hex/onion with parts of DDD.
- Audit logs and event sourcing for the system, esp. for duel storing at the infrastructure level
- Pagination for duels history and caching a bit
- Improve the flow of delay stamps as for large scale it cause memory leaks - it is fault of Redis, as every
delayed message is being on a new entry.
