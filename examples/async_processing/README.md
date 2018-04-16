# Async processing of VAT ID validation

A common question we got is how we deal with VAT ID validation when the official [European Commission (EC) VAT Information Exchange System (VIES)](http://ec.europa.eu/taxation_customs/vies) is down or not operational. Often we explain that we have several tools in place to perform many validations offline like `validateVatSum` and our `getHeartBeat()->isAlive()`, but this doesn't solve the problem for many users.

Therefor I've created this simple Async VAT Validation setup using a `queue.php` for adding VAT ID's into a simple SQLite DB queue and `worker.php` which will run as a daemon constantly checking the queue to see if there's a new VAT ID to validate. This worker also implements the "heart beat" checker which puts it into sleep mode for 30 minutes.

The benefit is you can run the worker as a process and have the queue script listening onto a seperate port to which you can easily send your VAT ID's.

**THIS IS A PROOF-OF-CONCEPT!!! DO NOT USE AS-IS IN PRODUCTION!!!**

## Step 1: Initialise the database

We're making use of SQLite for this PoC, but feel free to set it up for whatever backend you feel comfortable with.

```
cd examples/async_processing
sqlite3 vatqueue.db < vatqueue.sql
```

## Step 2: Starting the worker

This will run the worker as a daemon and puts it in the background. You can also run it in Screen or even in Docker if you want.

```
php worker.php &
```

## Step 3: Start the queue listener

We also want a simple interface where we can just point the VAT ID to, and nothing is easier than to use it as a web service. In this case we're using the build-in PHP web server to handle requests, but you can also have it run in Apache, Nginx or even inside a Docker container.

```
php -S localhost:11984 queue.php
```

## Step 4: Add VAT ID's to the queue

Assuming that all steps have been followed as described above, you can just throw VAT ID's to the web service endpoint by using curl or ajax calls.

```
curl "http://localhost:11984/?vatid=BE0811231234"
curl "http://localhost:11984/?vatid=BE0811231235"
curl "http://localhost:11984/?vatid=BE0811231236"
``` 

## Step 5: See the validation

Now you just need to read from the backend to see which VAT ID was valid and which one did not. And as long the `worker.php` is running, it will keep reading the queue until all things are processed.
