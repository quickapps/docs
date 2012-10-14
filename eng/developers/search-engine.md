Search Engine
=============

The search engine system lets users search for specific nodes on your site for particular words, a search request looks as below:  

    http://www.your-domain.com/search/[seach-query]

Where [search-query] may contain one or more of the following keywords.


Keywords
========

### limit
Limit the amount of results returned by the search query.  

##### USAGE
	limit:5

---

### order
Order results by the specified field and the given direction (multiple orders must by separated by `|`).  
**Syntax:** {Model}.{field},{direction}|...  

##### USAGE
	Node.field,asc|Node.field2,desc

---

### term
Filter results by terms ID separed by comma.  

##### USAGE
	term:my-term1,my-term2

---

### vocabulary
Filter results by vocabularies ID separed by comma.
 
##### USAGE
	vocabulary:voc-1,voc2

---

### promote
Set to 1 to display promoted nodes only. Zero (0) will display all unpromoted ones.  

##### USAGE
	promote:1

---

### language
Show nodes matching the given language codes separed by comma. The wildcard `*` means any language.  
	
##### USAGE
Show nodes in: English or French or Spanish

	language:eng,fre,spa
	
Show nodes in any language

    language:*

---

### type
Show nodes of the specified type only. Multiple IDs must be separated by comma. 

##### USAGE
Show nodes of type `artcile` or `page` only
	
    type:article,page

---

### created
Show nodes matching the given creation date range.  
Syntax: created:[<lower date> TO <upper date>]  

* The `TO` word must be UPPERCASE
* lower/upper dates must be any valid PHP's date() expression
* The wildcard `*` means any date

##### USAGE
Show nodes created on 1976-03-06T23:59:59.999Z

    created:[1976-03-06T23:59:59.999Z TO *]
	// OR equivalent:
	created:[1976-03-06T23:59:59.999Z]

Show nodes created on 2012:

    created:[2012-01-01 TO 2012-12-01]
	
Show nodes created between 2008 and today:
	
	created:[2008-01-01 TO NOW]

---

### modified
Same as `created`.

---

### author
Show nodes created by the specified author(s).  
You can speicfy both user's ID or user's Email. Multiple authors must be saparated by comma.  

---

##### USAGE
	author:1,user@email.com,26


***



CCK Search API
==============

Since v1.1, QuickApps CMS allows you to perform conditionals `find()` using any CCK Field as part of your conditions. This awesome
behavior, allows you to use CCK Fields like a regular table-column.  
For instance, lets suppose we have an User entity:


User's db-table instance may looks as below in certain moment:


| id | name         | last_name       | email                 |
|:---|:-------------|:----------------|:----------------------|
|1   |  John        | Locke           | j.locke@example.com   |
|2   |  Kate        | Austen          | k.austen@example.com  |
|20  |  Sayid       | Jarrah          | s.jarrah@example.com  |



Now, lets supose we have attached three new CCK Fields to this entity.

- User's Age: `field_user_age`
- User's Phone: `field_user_phone`
- User's Country: `field_user_coutry`


Note: `field_user_age`, `field_user_phone` and `field_user_coutry` are the machine names of CCK Fields. You can find this information
in the `fields` table.


The new CCK Search API allows you to perform find conditions using any of this three attached fields,
internally your User's db-table now looks as follow (supposing that user have entered some data in these new fields):


| id | name         | last_name       | email                 | field_user_age | field_user_phone | field_user_coutry |
|:---|:-------------|:----------------|:----------------------|:---------------|:-----------------|:------------------|
| 1  |  John        | Locke           | j.locke@example.com   | 50             | 123 362 458      | Utopia            |
| 2  |  Kate        | Austen          | k.austen@example.com  | 30             | 948 158 368      | The Island        |
| 20 |  Sayid       | Jarrah          | s.jarrah@example.com  | 40             | 948 136 745      | Utopia            |


And now for example, you are totally able to search all users where phone matches `948 xxx xxx`.  
In your some of your controllers controller:

    public $uses = array('User');
    ...
    $this->User->find('all',
		array(
			'conditions' => array(
				'User.field_user_phone LIKE' => '948 ___ ___'
			)
		)
	);


Outputs:


| id | name         | last_name       | email                 | field_user_age | field_user_phone | field_user_coutry |
|:---|:-------------|:----------------|:----------------------|:---------------|:-----------------|:------------------|
| 2  |  Kate        | Austen          | k.austen@example.com  | 30             | **948** 158 368  | The Island        |
| 20 |  Sayid       | Jarrah          | s.jarrah@example.com  | 40             | **948** 136 745  | Utopia            |
	

***

You can even perfom logical conditions such as `> >= < <= <>`, for example all users with `age > 30`:

    public $uses = array('User');
    ...
    $this->User->find('all',
		array(
			'conditions' => array(
				'User.field_user_age >' => 30
			)
		)
	);


Outputs:

| id | name         | last_name       | email                 | field_user_age | field_user_phone | field_user_coutry |
|:---|:-------------|:----------------|:----------------------|:---------------|:-----------------|:------------------|
| 1  |  John        | Locke           | j.locke@example.com   | **50**         | 123 362 458      | Utopia            |
| 20 |  Sayid       | Jarrah          | s.jarrah@example.com  | **40**         | 948 136 745      | Utopia            |


***

QuickApps CMS automagically detects if the specified fields is a CCK Field or not. 
Anyway, if for some reason entity has a concrete field (those in entity's db-table) named same as the mahcine-name of any of its attached CCK fields
then you can simply prefix CCK field's machine-name with the `:` symbol to tell QuickApps that you are refering to the CCK field. e.g.:


    $this->User->find('all',
		array(
			'conditions' => array(
				'User.:name LIKE' => '%John%'
			)
		)
	);

If the User's db-table has a `name` column, it will be ignored.
	
***

## Tips & Tricks

Internally QuickApps stores searcheable information for each CCK Field separately,
and one _global_ record which contains the information of every CCK Field plus Entity's concrete fields.


##### Search on any Field

If you need to search some words that may be on any CCK Field, you can use the special field `::` to indicate that you are refering to the whole
search-index-information of the entity and not to an especifict CCK Field.

	$this->User->find('all',
		array(
			'conditions' => array(
				'User.:: LIKE' => '%some words%'
			)
		)
	);

In the User example. The code above will look the `some words` phrase on any CCK field attached to User entity
(field_user_phone, field_user_coutry, field_user_age), plus User's concrete fields.


##### Complex Finds

Similar as in [CakePHP](http://book.cakephp.org/2.0/en/models/retrieving-your-data.html#complex-find-conditions), complex find conditions are
fully supported. 


##### For example

Search all user that own phone numbers begining with `948` (e.g 948 600 500) or
`123` (e.g. 123 555 444), and they live in `Utopia` country:

	$this->User->find('all',
		array(
			'conditions' => array(
				'AND' => array(
					'OR' => array(
						array('User.field_user_phone LIKE' => '948%'),
						array('User.field_user_phone' => '123%')
					),
					array('User.field_user_coutry' => 'Utopain')
				)
			)
		)
	);

Outputs:

| id | name         | last_name       | email                 | field_user_age | field_user_phone | field_user_coutry |
|:---|:-------------|:----------------|:----------------------|:---------------|:-----------------|:------------------|
| 1  |  John        | Locke           | j.locke@example.com   | 50             | **123** 362 458  | **Utopia**        |

