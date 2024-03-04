
## laravel-vue-controller - To access your controller within a vue file 
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

> [!NOTE]  
> if You are using Laravel > 4 Skip the below step because The servie provider is auto discover then no neeed to register this provider 

## Laravel < 4
Once this operation completes, the final step is to add the service provider. Open `config/app.php`, and add a new item to the providers array.

    Yudhees\LaravelVueController\vuecontrollerserviceprovider::class

## Note 
  The service Provider Contains the Default Route , Be Sure that This Route is not registered in any of your route file
  
    Route::post('/vuecontroller',vuecontroller::class)->name('vuecontroller');

## Usage
   
 > [!NOTE] 
> In This Example I am using Inertia.js
       
  In Your `app.js` file simply add this
  
    import {controller} from '../../vendor/yudhees/laravel-vue-controller/compostables/global.js'

## Global

```js
//resources/js/app.js
    import './bootstrap'
    import { createApp, h } from 'vue'
    import { createInertiaApp } from '@inertiajs/vue3'
     import {controller} from '../../vendor/yudhees/laravel-vue-controller/compostables/global.js'  // add this compostable
      createInertiaApp({
       resolve: name => {
       const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
       },
        setup({ el, App, props, plugin }) {
       const app= createApp({ render: () => h(App, props) });
       app.use(plugin);
       app.config.globalProperties.controller = controller; // register controller on global
       app.mount(el);
     },
    })
```
## Controller Fucntion
Controller Functions Accepts Two Argument The First Argument Represents Path of the Controller 
   >[!NOTE]
   > Default prefix path of the Controller is `App\Http\Controller`

The Second Arument Represents  the method name

## Examples
 I Created a UserController using This Command
 
      php artisan make:controller UserController
The Example `UserController` is Look Like this
 ```php
 <?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function users(){
        return response(['users'=> User::all()]); // get all users
    }
    public function userlist(){
        return Inertia::render('userslist'); //render user list table
    }
    public function status($userid){ // change the status of the user 
       try {
         $user = User::find($userid);
         if($user->status)
          $user->update(['status'=> 0]);
          else
          $user->update(['status'=> 1]);
       } catch (\Throwable $th) {
        dd($th);
       }
    }
    public function  destroy($userid){ // destroy the user 

    try {
         User::destroy($userid);
    }
    catch (\Throwable $th) {
        dd($th);
    }
   }
}

```
## Vue Template 
`userslist.vue`
  ```html
  <!-- resources/js/Pages/userlist.vue -->
  <template>
     <div class="table-responsive">
          <table class="table">
                <thead>
                    <tr>
                        <th>SI.NO</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user, index in users" :key="user.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" :checked="user.status"
                                    @change="toggleStatus(user.id)" />
                            </div>

                        </td>
                        <td>
                            <button class="btn btn-danger" @click="deleteuser(user.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</template>
  ```
## Scripts
## Option API
```js
//resources/js/Pages/userlist.vue
export default {
    data() {
        return {
            users: [],
            controllerPath: "UserController", 
            /*
            If The  UserController is inside the admin folder,
            then the required Controller Path is admin/userController
            */
        }
    },
    methods: {
        getUsers() {
            this.controller(this.controllerPath, 'users')
                .then(response => {
                    this.users = response.data.users
                })
        },
        deleteuser(id) {
            this.controller(this.controllerPath, 'destroy', {
                userid: id,     // passing userid as a params to the usercontroller destroy method
            }).then(() => {
                alert('User Deleted Successfully')
                this.getUsers();
            })
        },
        toggleStatus(id) {
            this.controller(this.controllerPath, 'status', {
                userid: id,  // passing userid as a params to the usercontroller status method
            }).then(() => {
                alert('status changed');
            })
        }
    },
    mounted() {
        this.getUsers()
    }
    }
```
## Composition api vue 3
 ```js
//resources/js/Pages/userlist.vue
import { onMounted, ref, getCurrentInstance } from 'vue'
const controllerPath = "UserController"
         /*
          If The  UserController is inside the  admin folder,
          then the required Controller Path is admin/userController
          */
const users = ref([])
 function toggleStatus(id) {
     controller(controllerPath, 'status', {
         userid: id,
     }).then(() => {
         alert('status changed');
     })
 }
 const controller=getCurrentInstance().appContext.config.globalProperties.controller // get controller on globalProperties
 onMounted(() => {
     getUsers()
 })
 function deleteuser(id) {
     controller(controllerPath, 'destroy', {
         userid: id,
     }).then(() => {
         alert('User Deleted Successfully')
         getUsers();
     })
 }
 function getUsers() {
     controller(controllerPath, 'users')
         .then(response => {
             users.value = response.data.users
         })
 }
```
## Vendor Files
   `vuecontroller.php file`
 ```php
 <?php
namespace Yudhees\LaravelVueController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
class vuecontroller {
    public function __invoke(Request $request,$controller,$method){
        $controller =  App::make("App\Http\Controllers\\{$controller}");
        if (!method_exists($controller, $method)) {
            return Response::json(['error' => 'Function does not exist'], 404);
        }
        $params=$request->all();
         return call_user_func_array([$controller, $method], $params);
    }
}

```
## License

laravel-vue-controller is open-source software released under the MIT license. See [LICENSE](LICENSE) for more information.
