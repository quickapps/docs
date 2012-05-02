Allow modules, themes or fields to interact with the QACMS core. A hook is a PHP method that is named **underscored**, e.g.: do_something(), foo_bar().

As cakePHP is a MVC framework, hooks method are separated in three groups:

* Model Hooks
* View Hooks
* Controller Hooks

Hook methods can be invoked by using the `hook` method located in each HookCollection class:

 * HookCollectionBehavior::hook()
   * AppModel::hook() shorcut for use in Model classes.
 * HookCollectionHelper::hook()
   * AppHelper::hook() shorcut for use in Helper classes.
 * HookCollectionComponent::hook()
   * AppController::hook() shorcut for use in Controller actions.

Hook methods may accept only **one parameter**, e.g.:

 * `my_hook_method($param_1, $params_2);` Invalid, second parameter will be always unset.
 * `my_hook_method($param_1);` Correct.
 * `my_hook_method(&$param_1);` Correct, reference parameter for alter purposes.
 * `my_hook_method();` Correct, no parameter expected.

## hook($hook, &$data, $options);
### $hook
Name of the hook to call.

### $data
Data for the triggered callback. **Must be a reference**, some examples:

* ->hook('my_hook_name', array('data_for_hook')); **Invalid** will produce fatal error
* ->hook('my_hook_name', $data = array('data_for_hook')); **Valid**

### $option
Array of options

- `breakOn` Set to the value or values you want the callback propagation to stop on.
   Can either be a scalar value, or an array of values to break on.
   Defaults to `false`.

- `break` Set to true to enabled breaking. When a trigger is broken, the last returned value
   will be returned.  If used in combination with `collectReturn` the collected results will be returned.
   Defaults to `false`.

- `collectReturn` Set to true to collect the return of each object into an array.
   This array of return values will be returned from the hook() call. Defaults to `false`.

### return
Either the last result or all results if collectReturn is on. Or **null** in case of no response.