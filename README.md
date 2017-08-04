# Access
A module for invoiceninja to store client access.
This is a simple but useful module that allows an user to create different entries to store client access.
The username and password are both encrypted, only the user that creates the entry can see.

##To install simply run the code below:

```php artisan module:install soiposervices/access --type=github```

Check if the module has been installed:

```php artisan module:list```

And run the migrations:

```php artisan module:migrate Access```

At this point visit the application and you should have a new menu item “Access”

#Todo
Use a different encryptpion key, store it in the session, and clear it when the user logs out.
