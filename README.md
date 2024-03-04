[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

## laravel-vue-controller - To access your controller within your vue file 
## Installation

## composer 
     composer require yudhees/laravel-vue-controller
     
## or

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `yudhees/laravel-vue-controller`.

	"require-dev": {
	     "yudhees/laravel-vue-controller": "1.0"
	}


Next, update Composer from the Terminal:

    composer update 

## Note 

   if You are using Laravel > 4 Skip the below step because The servie provider is auto discover then no neeed to register this provider 
   
## Laravel < 4
Once this operation completes, the final step is to add the service provider. Open `config/app.php`, and add a new item to the providers array.

    Yudhees\LaravelVueController\vuecontrollerserviceprovider::class

## Usage
   
   ## Note
   
       In This Example I am using Inertia.js
       
  In Your `app.js` file simply add this ` import {controller} from '../../vendor/yudhees/laravel-vue-controller/compostables/global.js'`
  
    import './bootstrap'
    import { createApp, h } from 'vue'
    import { createInertiaApp } from '@inertiajs/vue3'
    ```diff
    import {controller} from '../../vendor/yudhees/laravel-vue-controller/compostables/global.js'
      createInertiaApp({
       resolve: name => {
       const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
       },
        setup({ el, App, props, plugin }) {
       const app= createApp({ render: () => h(App, props) });
       app.use(plugin);
       `` app.config.globalProperties.controller = controller;``
       app.mount(el);
     },
    })

  
### Migrations

Laravel offers a migration generator, but it stops just short of creating the schema (or the fields for the table). Let's review a couple examples, using `generate:migration`.

    php artisan generate:migration create_posts_table

If we don't specify the `fields` option, the following file will be created within `app/database/migrations`.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('posts', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::drop('posts');
	}

}

```

Notice that the generator is smart enough to detect that you're trying to create a table. When naming your migrations, make them as descriptive as possible. The migration generator will detect the first word in your migration name and do its best to determine how to proceed. As such, for `create_posts_table`, the keyword is "create," which means that we should prepare the necessary schema to create a table.

If you instead use a migration name along the lines of `add_user_id_to_posts_table`, in that case, the keyword is "add," signaling that we intend to add rows to an existing table. Let's see what that generates.

    php artisan generate:migration add_user_id_to_posts_table

This will prepare the following boilerplate:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUserIdToPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('posts', function(Blueprint $table) {

        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::table('posts', function(Blueprint $table) {

        });
	}

}
```

Notice how, this time, we're not doing `Schema::create`.

#### Keywords

When writing migration names, use the following keywords to provide hints for the generator.

- `create` or `make` (`create_users_table`)
- `add` or `insert` (`add_user_id_to_posts_table`)
- `remove` (`remove_user_id_from_posts_table`)
- `delete` or `drop` (`delete_users_table`)

#### Generating Schema

This is pretty nice, but let's take things a step further and also generate the schema, using the `fields` option.

    php artisan generate:migration create_posts_table --fields="title:string, body:text"

Before we decipher this new option, let's see the output:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('posts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
			$table->text('body');
			$table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::drop('posts');
	}

}
```
