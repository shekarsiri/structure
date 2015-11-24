# Structure
Laravel artisan command to create the basic Application Structure.


### Install 

```
composer require shekarsiri/strucutre
```

### Update the config file (config/app.php)

```
'providers' => [
	...,
	ShekarSiri\Structure\StructureServiceProvider::class,
];
```

### Run the command
```
php artisan structure:make SomeApp/Post
php artisan structure:make SomeApp/Product
```


### Results
```

└── SomeApp
    ├── Post
    │   ├── Events
    │   │   ├── PostCreatedEvent.php
    │   │   ├── PostDeletedEvent.php
    │   │   └── PostUpdatedEvent.php
    │   ├── Exceptions
    │   ├── Facades
    │   │   └── Post.php
    │   ├── Jobs
    │   │   ├── PostCreate.php
    │   │   ├── PostDelete.php
    │   │   └── PostUpdate.php
    │   ├── Listeners
    │   ├── Post.php
    │   ├── PostService.php
    │   ├── PostServiceProvider.php
    │   ├── Providers
    │   │   └── PostEventServiceProvider.php
    │   ├── Repositories
    │   │   ├── PostRepository.php
    │   │   └── PostRepositoryEloquent.php
    │   └── Requests
    │       ├── PostCreateRequest.php
    │       └── PostUpdateRequest.php
    └── Product
        ├── Events
        │   ├── ProductCreatedEvent.php
        │   ├── ProductDeletedEvent.php
        │   └── ProductUpdatedEvent.php
        ├── Exceptions
        ├── Facades
        │   └── Product.php
        ├── Jobs
        │   ├── ProductCreate.php
        │   ├── ProductDelete.php
        │   └── ProductUpdate.php
        ├── Listeners
        ├── Product.php
        ├── ProductService.php
        ├── ProductServiceProvider.php
        ├── Providers
        │   └── ProductEventServiceProvider.php
        ├── Repositories
        │   ├── ProductRepository.php
        │   └── ProductRepositoryEloquent.php
        └── Requests
            ├── ProductCreateRequest.php
            └── ProductUpdateRequest.php
        
```